<?php

namespace App\Http\Controllers;

use App\Models\{External, ExternalHistory, ExternalAttachment, User, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\External\{NotifyChief , NotifyNew, NotifyUpdate, NotifyAttached, NotifyAssigned, NotifyAssignee, NotifyAccepted, NotifyCompleted, NotifyFollowup};

class ExternalController extends Controller
{
    public function myTasks(Request $request)
    {
        $externals = $this->filterExternals($request, function ($query) {
            $query->whereNotIn('status', ['cancelled', 'completed'])
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
            $query->where('status', '!=', 'cancelled');
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

    public function cancelled(Request $request)
    {
        $externals = $this->filterExternals($request, function ($query) {
            if (auth()->user()->canMonitorRequests()) {
                $query->where('status', 'cancelled');
            } else {
                $query->where('status', 'cancelled')
                    ->whereHas('histories', function ($h) {
                        $h->where('user_id', auth()->id());
                    });
            }
        });
        return view('externals.cancelled', [
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
            'division' => 'required|in:TOD,AFD',
            'reference' => 'nullable|string|max:1000',
        ]);
        $tod = Setting::where('setting', 'todchief')->first()->user_id;
        $afd = Setting::where('setting', 'afdchief')->first()->user_id;
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
                'division'    => $request->division,
                'target_date' => $request->target_date,
                'assigned_to' => ($request->division === "TOD" ? $tod : $afd),
                'status'      => 'pending',
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
            if($request->division === "TOD") {
                $recipient = User::find($tod);
            } else {
                $recipient = User::find($afd);
            }
            if ($recipient->id !== Auth::id()) {
                $recipient->notify(new NotifyChief($external), $recipient->preference?->external_email_notify_received ?? true, auth()->id());
            }
            // $monitorers = User::withoutTrashed()
            //     ->where('id', '!=', Auth::id())
            //     ->whereHas('settings', function($query) {
            //         $query->where('setting', 'monitorer');
            //     })
            //     ->get();
            // foreach ($monitorers as $monitor) {
            //     $monitor->notify(new NotifyNew($external, $monitor->preference?->external_email_notify_received ?? true, auth()->id()));
            // }
            ExternalHistoryController::log(auth()->id(), $external->id, 'Received and endorsed to ' . $request->division);
            return redirect()->route('externals.monitoring')
                ->with('success', 'The request has been received and endorsed to ' . $request->division . ' successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function verify($id)
    {
        $external = External::withTrashed()
            ->with(['attachments', 'histories', 'creator', 'assignedTo'])
            ->findOrFail($id);
        auth()->user()->unreadNotifications
            ->where('data.request_id', $external->id)
            ->each(function ($notification) {
                $notification->markAsRead();
            });
        $external = External::with(['attachments', 'histories'])->findOrFail($id);
        if($external->assigned_to !== auth()->id() && !$external->histories->where('user_id', auth()->id())->count() && $external->creator_id !== auth()->id()) {
            abort(403, 'You are not authorized to access this request.');
        }
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
        $external = External::withTrashed()
            ->with(['attachments', 'histories'])
            ->findOrFail($id);
        auth()->user()->unreadNotifications
            ->where('data.request_id', $external->id)
            ->each(function ($notification) {
                $notification->markAsRead();
            });
        if($external->assigned_to !== auth()->id() && !$external->histories->where('user_id', auth()->id())->count() 
            && $external->creator_id !== auth()->id()) {
            abort(403, 'You are not authorized to access this request.');
        }
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
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            $creator->notify(new NotifyUpdate($external, $creator->preference?->external_email_notify_updated ?? true, auth()->id()));
        }
        // $monitorers = User::withoutTrashed()
        //     ->where('id', '!=', Auth::id())
        //     ->whereHas('settings', function($query) {
        //         $query->where('setting', 'monitorer');
        //     })
        //     ->get();
        // foreach ($monitorers as $monitor) {
        //     $monitor->notify(new NotifyUpdate($external, $monitor->preference?->external_email_notify_updated ?? true, auth()->id()));
        // }
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
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            $creator->notify(new NotifyAttached($external, $creator->preference?->external_email_notify_updated ?? true, auth()->id()));
        }
        // $monitorers = User::withoutTrashed()
        //     ->where('id', '!=', Auth::id())
        //     ->whereHas('settings', function($query) {
        //         $query->where('setting', 'monitorer');
        //     })
        //     ->get();
        // foreach ($monitorers as $monitor) {
        //     $monitor->notify(new NotifyUpdate($external, $monitor->preference?->external_email_notify_updated ?? true, auth()->id()));
        // }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Attached', $attach);
        return back()->with('success', "Attached {$count} file" . ($count > 1 ? 's' : '') . " successfully.");
    }

    public function assign(Request $request, $id)
    {
        $external = External::with('creator','assignedTo')->findOrFail($id);
        $external->assigned_to = $request->personnel;
        $external->status = 'assigned';
        $external->save();
        $pers = User::find($request->personnel);
        $pers->notify(new NotifyAssignee($external, $pers->preference?->external_email_notify_received ?? true, auth()->id()));
        //notify creator if not the one assigning
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            $creator->notify(new NotifyAssigned($external, $creator->preference?->external_email_notify_updated ?? true, auth()->id()));
        }
        //notify monitorers
        // $monitorers = User::withoutTrashed()
        //     ->where('id', '!=', Auth::id())
        //     ->whereHas('settings', function($query) {
        //         $query->where('setting', 'monitorer');
        //     })
        //     ->get();
        // foreach ($monitorers as $monitor) {
        //     $monitor->notify(new NotifyAssigned($external, $monitor->preference?->external_email_notify_updated ?? true, auth()->id()));
        // }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Assigned to ' . $pers->name, $request->remarks);

        return redirect()->route('externals.mytasks')
            ->with('success', 'Request ' . $external->reference . ' has been assigned to ' . $pers->name . '. Awaiting acceptance.');
    }

    public function accept(Request $request, $id)
    {
        $external = External::with('creator','assignedTo')->findOrFail($id);
        $external->status = 'accepted';
        $external->save();
        //notify creator if not the one accepting
        if ($external->creator_id !== Auth::id()) {
            $creator = User::find($external->creator_id);
            $creator->notify(new NotifyAccepted($external, $creator->preference?->external_email_notify_updated ?? true, auth()->id()));
        }
        // notify monitorers about acceptance
        // $monitorers = User::withoutTrashed()
        //     // ->where('id', '!=', Auth::id())
        //     ->whereHas('settings', function($query) {
        //         $query->where('setting', 'monitorer');
        //     })
        //     ->get();
        // foreach ($monitorers as $monitor) {
        //     $monitor->notify(new NotifyAccepted($external, $monitor->preference?->external_email_notify_updated ?? true, auth()->id()));
        // }
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
                $creator->notify(new NotifyCompleted($external, $monitor->preference?->external_email_notify_completed ?? true, auth()->id()));
            }
        }
        //notify monitorers
        // $role = 'monitorer'; // or dynamically
        // $monitorers = User::whereHas('settings', function ($query) use ($role) {
        //     $query->where('setting', $role);
        // })->get();
        // foreach ($monitorers as $monitor) {
        //     if ($monitor->id !== Auth::id() && $monitor->preference?->external_email_notify_completed) {
        //         $monitor->notify(new NotifyCompleted($external, $monitor->preference?->external_email_notify_completed ?? true, auth()->id()));
        //     }
        // }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Completed', $remarks);

        return redirect()->route('externals.mytasks')->with('success', $prompt);
    }

    public function followUp(Request $request, $id) {
        $external = External::findOrFail($id);
        $assigned = $external->assignedTo;
        if ($assigned) {
            $assigned->notify(new NotifyFollowup($external, $assigned->preference?->external_email_notify_updated ?? true, $request->remarks ?? "", auth()->id()));
        }
        ExternalHistoryController::log(auth()->id(), $external->id, 'Follow-Up', $request->remarks ?? "");
        return redirect()->back()->with('success', 'Notification sent to the assigned personnel.');
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