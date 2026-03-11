<?php

namespace App\Http\Controllers;

use App\Models\{External, ExternalHistory, ExternalAttachment, User, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\{RequestForAssignment, RequestForEndorsement, RequestForForwarding, RequestUpdated, NewAttached, RequestAssigned, NewAssignment, RequestAssignedMonitorer, RequestAccepted, RequestCompleted};

class ExternalController extends Controller
{
    public function recording(Request $request)
    {
        $externals = $this->filterExternals($request, function ($query) {
            $query->where('status', 'pending');
        });

        return view('externals.recording', [
            'externals' => $externals,
            'search' => $request->query('search') . '',
            'priority' => $request->query('priority') . '',
            'pagesubject' => 'Requests Accommodation',
        ]);
    }

    public function endorsing(Request $request)
    {
        $externals = $this->filterExternals($request, function ($query) {
            $query->where('status', 'forwarded');
        });

        return view('externals.endorsing', [
            'externals' => $externals,
            'search' => $request->query('search'). '',
            'priority' => $request->query('priority') . '',
        ]);
    }

    public function myTasks(Request $request)
    {
        $externals = $this->filterExternals($request, function ($query) {
            $query->whereNotIn('status', ['pending', 'forwarded', 'completed'])
                  ->where('assigned_to', auth()->id());
        });

        return view('externals.mytasks', [
            'externals' => $externals,
            'search' => $request->query('search') . '',
            'priority' => $request->query('priority') . '',
        ]);
    }

    public function monitoring(Request $request)
    {
        $externals = $this->filterExternals($request, function ($query) {
            $query->where('status', '!=', 'completed');
            if (!auth()->user()->canMonitorRequests()) {
                $query->where(function ($q) {
                    $q->where('creator_id', auth()->id())
                      ->orWhereHas('histories', function ($h) {
                          $h->where('user_id', auth()->id());
                      });
                });
            }
        });

        return view('externals.monitoring', [
            'externals' => $externals,
            'search' => $request->query('search') . '',
            'priority' => $request->query('priority') . '',
        ]);
    }

    public function completed(Request $request)
    {
        $externals = $this->filterExternals($request, function ($query) {
            if (auth()->user()->canMonitorRequests()) {
                $query->where('status', 'completed');
            } else {
                $query->where('status', 'completed')
                      ->whereHas('histories', function ($h) {
                          $h->where('user_id', auth()->id());
                      });
            }
        });

        return view('externals.completed', [
            'externals' => $externals,
            'search' => $request->query('search') . '',
            'priority' => $request->query('priority') . '',
        ]);
    }

    public function archive(Request $request)
    {
        $externals = $this->filterArchivedExternals($request, function ($query) {
            if (auth()->user()->canMonitorRequests()) {
                $query->where('status', 'completed');
            } else {
                $query->where('status', 'completed')
                      ->whereHas('histories', function ($h) {
                          $h->where('user_id', auth()->id());
                      });
            }
        });
        return view('externals.archive', [
            'externals' => $externals,
            'search' => $request->query('search') . '',
            'priority' => $request->query('priority') . '',
        ]);
    }

    private function filterExternals(Request $request, callable $baseFilter)
    {
        $externals = External::withoutTrashed()
            ->with(['history', 'histories', 'creator']);
        $baseFilter($externals);
        $search = $request->query('search');
        if ($search) {
            $externals->where(function ($query) use ($search) {
                $query->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhere('agency', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $priority = $request->query('priority');
        if ($priority) {
            $externals->where('priority', $priority);
        }
        return $externals->orderBy('updated_at', 'desc')->paginate(10);
    }

    private function filterArchivedExternals(Request $request, callable $baseFilter)
    {
        $externals = External::onlyTrashed()
            ->with(['history', 'histories', 'creator']);
        $baseFilter($externals);
        $search = $request->query('search');
        if ($search) {
            $externals->where(function ($query) use ($search) {
                $query->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhere('agency', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $priority = $request->query('priority');
        if ($priority) {
            $externals->where('priority', $priority);
        }
        return $externals->orderBy('updated_at', 'desc')->paginate(10);
    }

    public function create()
    {
        return view('externals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'agency' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'reference' => 'nullable|string|max:1000',
        ]);
        try {
            DB::beginTransaction();
            $external = External::create([
                'creator_id'  => Auth::id(),
                'subject'     => $request->subject,
                'agency'      => $request->agency,
                'contact'     => $request->contact,
                'description' => $request->description,
                'reference'   => $request->reference,
                'priority'    => $request->priority,
                'target_date' => $request->target_date,
                'status'      => auth()->user()->canForwardRequests() ? 'forwarded' : 'pending',
            ]);
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->store('edternals/attachments', 'public');
                    ExternalAttachment::create([
                        'external_id' => $external->id,
                        'file_path'   => $path,
                        'file_name'   => $originalName,
                        'file_type'   => $file->getClientOriginalExtension(),
                        'file_size'   => $file->getSize(),
                    ]);
                }
            }
            DB::commit();
            if(auth()->user()->canForwardRequests()) {
                $role = 'endorser'; // or dynamically
                $endorsers = User::whereHas('settings', function ($query) use ($role) {
                    $query->where('setting', $role);
                })->get();
                foreach ($endorsers as $endorser) {
                    if ($endorser->preference?->external_email_notify_received) {
                        $endorser->notify(new RequestForEndorsement($external->id));
                    }
                }
            } else {
                $role = 'forwarder'; // or dynamically
                $forwarders = User::whereHas('settings', function ($query) use ($role) {
                    $query->where('setting', $role);
                })->get();
                foreach ($forwarders as $forwarder) {
                    if ($forwarder->preference?->external_email_notify_received) {
                        $forwarder->notify(new RequestForForwarding($external->id));
                    }
                }
            }
            ExternalHistoryController::log(auth()->id(), $external->id, 'Received' . (auth()->user()->canForwardRequests() ? ' and forwarded to the ORD ' : ''));
            return redirect()->route('externals.monitoring')
                ->with('success', 'External request has been received ' . (auth()->user()->canForwardRequests() ? 'and forwarded to the ORD ' : '') . 'successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function verify($id)
    {
        $external = External::with(['attachments', 'histories'])->findOrFail($id);
        return view('externals.verify', compact('external'));
    }
    public function preview($id)
    {
        $attachment = ExternalAttachment::findOrFail($id);
        $path = $attachment->file_path;
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found.');
        }
        $mime = Storage::disk('public')->mimeType($path);
        $allowed = ['application/pdf','image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($mime, $allowed)) {
            abort(403, 'Preview not supported for this file type.');
        }
        return response()->file(
            Storage::disk('public')->path($attachment->file_path),
            [
                'Content-Type' => Storage::disk('public')->mimeType($attachment->file_path),
                'Content-Disposition' => 'inline; filename="'.$attachment->file_name.'"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]
        );
    }
    public function download($id)
    {
        $attachment = ExternalAttachment::findOrFail($id);
        $path = $attachment->file_path;
        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'The requested file does not exist on the server.');
        }
        return Storage::disk('public')->download(
            $path, 
            $attachment->file_name
        );
    }
    public static function humanFileSize($bytes, $decimals = 1): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);

        return sprintf("%.{$decimals}f %s", $bytes / pow(1000, $factor), $units[$factor]);
    }
    public function show($id) {
        $external = External::withTrashed()->with(['attachments', 'histories'])->findOrFail($id);
        return view('externals.show', compact('external'));
    }

    public function addUpdate(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:2000',
        ]);

        $external = External::findOrFail($id);
        $external->updated_at = now();
        $external->save();
        ExternalHistory::create([
            'external_id' => $external->id,
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'remarks'     => $request->remarks,
        ]);

        //notify creator if not the one updating
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            if ($creator && $creator->preference?->external_email_notify_updated) {
                $creator->notify(new RequestUpdated($external->subject, $request->remarks));
            }
        }

        //notify monitorers
        $role = 'monitorer'; // or dynamically
        $monitorers = User::whereHas('settings', function ($query) use ($role) {
            $query->where('setting', $role);
        })->get();
        foreach ($monitorers as $monitor) {
            if ($monitor->id !== Auth::id() && $monitor->preference?->external_email_notify_updated) {
                $monitor->notify(new RequestUpdated($external->subject, $request->remarks));
            }
        }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Update Added', $request->remarks);
        return back()->with('success', 'Update added successfully.');
    }

    public function addAttachment(Request $request, $id) {
        $request->validate([
            'attachments'   => 'required',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);
        $external = External::findOrFail($id);
        $external->updated_at = now();
        $external->save();
        $count = 0;
        $attach = "";
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('externals/attachments', 'public');
            $attach .= $file->getClientOriginalName() . '<br>';
            ExternalAttachment::create([
                'external_id' => $external->id,
                'file_path'   => $path,
                'file_name'   => $file->getClientOriginalName(),
                'file_type'   => $file->getClientOriginalExtension(),
                'file_size'   => $file->getSize(),
            ]);
            $count++;
        }
        
        //notify creator if not the one updating
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            if ($creator && $creator->preference?->external_email_notify_updated) {
                $creator->notify(new NewAttached($external->subject, 'Attached:' . $count . ' file(s)'));
            }
        }
        //notify monitorers
        $role = 'monitorer'; // or dynamically
        $monitorers = User::whereHas('settings', function ($query) use ($role) {
            $query->where('setting', $role);
        })->get();
        foreach ($monitorers as $monitor) {
            if ($monitor->id !== Auth::id() && $monitor->preference?->external_email_notify_updated) {
                $monitor->notify(new NewAttached($external->subject, 'Attached: ' . $count . ' file(s)'));
            }
        }

        ExternalHistoryController::log(auth()->id(), $external->id, 'Attached', $attach);

        return back()->with('success', "Attached {$count} file" . ($count > 1 ? 's' : '') . " successfully.");
    }

    public function forward(Request $request, $id)
    {
        $external = External::findOrFail($id);
        if ($external->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be forwarded.');
        }
        $external->status = 'forwarded';
        $external->save();


        $role = 'endorser'; // or dynamically
        $endorsers = User::whereHas('settings', function ($query) use ($role) {
            $query->where('setting', $role);
        })->get();
        foreach ($endorsers as $endorser) {
            if ($endorser->preference?->external_email_notify_received) {
                $endorser->notify(new RequestForEndorsement($external->id));
            }
        }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Forwarded to ORD');
        return redirect()->route('externals.recording')
            ->with('success', 'The request has been forwarded to the ORD successfully.');
    }

    public function endorse(Request $request, $id)
    {
        $external = External::findOrFail($id);
        if ($external->status !== 'forwarded') {
            return back()->with('error', 'Only forwarded requests can be endorsed.');
        }
        $tod = Setting::where('setting', 'todchief')->first()->user_id;
        $afd = Setting::where('setting', 'afdchief')->first()->user_id;
        $external->division = $request->division;
        $external->assigned_to = ($request->division === "TOD" ? $tod : $afd);
        $external->status = 'endorsed';
        $external->save();
        if($request->division === "TOD") {
            $recipient = User::find($tod);
        } else {
            $recipient = User::find($afd);
        }
        if ($recipient && $recipient->preference?->external_email_notify_received) {
            $recipient->notify(new RequestForAssignment($external->id));
        }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Endorsed to ' . $request->division, $request->remarks);
        return redirect()->route('externals.endorsing')
            ->with('success', 'The request has been endorsed to ' . $request->division . ' successfully.');
    }

    public function assign(Request $request, $id)
    {
        $external = External::findOrFail($id);
        $external->assigned_to = $request->personnel;
        $external->status = 'assigned';
        $external->save();
        $pers = User::find($request->personnel);
        //notify assigned personnel
        if ($pers && $pers->preference?->external_email_notify_received) {
            $pers->notify(new NewAssignment($external->subject, $external->reference . '', $request->remarks));
        }
        //notify creator if not the one assigning
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            if ($creator && $creator->preference?->external_email_notify_updated) {
                $creator->notify(new RequestAssigned($external->subject, 'Assigned to ' . $pers->name . '. pending acceptance. ' . $request->remarks));
            }
        }
        //notify monitorers
        $role = 'monitorer'; // or dynamically
        $monitorers = User::whereHas('settings', function ($query) use ($role) {
            $query->where('setting', $role);
        })->get();
        foreach ($monitorers as $monitor) {
            if ($monitor->id !== Auth::id() && $monitor->preference?->external_email_notify_updated) {
                $monitor->notify(new RequestAssignedMonitorer($external->subject, 'Assigned to ' . $pers->name . '. pending acceptance. ' . $request->remarks));
            }
        }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Assigned to ' . $pers->name, $request->remarks);

        return redirect()->route('externals.mytasks')
            ->with('success', 'Request ' . $external->reference . ' has been assigned to ' . $pers->name . '. Awaiting acceptance.');
    }

    public function accept(Request $request, $id)
    {
        $external = External::findOrFail($id);
        $external->status = 'accepted';
        $external->save();
        //notify creator if not the one accepting
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            if ($creator && $creator->preference?->external_email_notify_updated) {
                $creator->notify(new RequestAccepted($external->subject));
            }
        }
        // notify monitorers about acceptance
        $role = 'monitorer'; // or dynamically
        $monitorers = User::whereHas('settings', function ($query) use ($role) {
            $query->where('setting', $role);
        })->get();
        foreach ($monitorers as $monitor) {
            if ($monitor->id !== Auth::id() && $monitor->preference?->external_email_notify_updated) {
                $monitor->notify(new RequestAccepted($external->subject));
            }       
        }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Accepted');
        return redirect()->route('externals.mytasks')
            ->with('success', 'Thanks for accepting the task.');
    }

    public function complete(Request $request, $id)
    {
        $external = External::findOrFail($id);
        $remarks = "";
        $prompt = "Thanks for accommodating this request";

        if ($external->target_date) {
            $target = Carbon::parse($external->target_date);

            if ($target < now()) {
                $daysLate = $target->diffInDays(now());
                $remarks = "$daysLate day" . ($daysLate > 1 ? 's' : '') . " late";
                $prompt .= ", next time please pay attention to the target date. Delivered: $remarks";
            } elseif ($target > now()) {
                $daysEarly = now()->diffInDays($target);
                $remarks = "$daysEarly day" . ($daysEarly > 1 ? 's' : '') . " before the deadline";
                $prompt = "Congratulations! You have delivered this task $remarks";
            } else {
                $remarks = "on time";
                $prompt = "Great! You have delivered this task on time";
            }
        }

        $external->status = 'completed';
        $external->save();

        //notify creator if not the one completing
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            if ($creator && $creator->preference?->external_email_notify_completed) {
                $creator->notify(new RequestCompleted($external->subject, $remarks));
            }
        }
        //notify monitorers
        $role = 'monitorer'; // or dynamically
        $monitorers = User::whereHas('settings', function ($query) use ($role) {
            $query->where('setting', $role);
        })->get();
        foreach ($monitorers as $monitor) {
            if ($monitor->id !== Auth::id() && $monitor->preference?->external_email_notify_completed) {
                $monitor->notify(new RequestCompleted($external->subject, $remarks));
            }
        }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Completed', $remarks);

        return redirect()->route('externals.mytasks')->with('success', $prompt);
    }

    public function destroy($id)
    {
        $external = External::findOrFail($id);
        $external->delete();
        ExternalHistoryController::log(auth()->id(), $external->id, 'Archived');
        return redirect()->route('externals.completed')->with('success', 'External request has been successfully archived.');
    }

    public function restore($id)
    {
        $external = External::onlyTrashed()->findOrFail($id);
        $external->restore();
        ExternalHistoryController::log(
            auth()->id(),
            $external->id,
            'Restored'
        );
        return redirect()
            ->route('externals.completed.archive')
            ->with('success', 'External request has been successfully restored.');
    }
}