<?php

namespace App\Http\Controllers;

use App\Models\{Document, DocumentParticipant, DocumentAttachment, DocumentHistory, User};
use App\Http\Controllers\DocumentHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function show($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function forSigning(Request $request)
    {
        $documents = Document::withoutTrashed()
            ->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id())
                    ->where('status', 'active');
            })
            ->orderBy('updated_at', 'desc')
            ->where('status', 'pending')
            ->with(['owner', 'attachments']); // Eager load relationships
        if ($request->filled('priority')) {
            $documents = $documents->where('priority', $request->query('priority'));
        }
        $documents = $documents->paginate(20);
        return view('documents.forsigning', [
            'documents' => $documents
        ]);
    }

    public function inProgress()
    {
        $documents = Document::withoutTrashed()->with('participants')
            ->where('creator_id', auth()->id())
            ->where('status', 'pending')
            ->paginate(20);
        return view('documents.inprogress', [
            'documents' => $documents
        ]);
    }

    public function forRevision()
    {
        $documents = Document::withoutTrashed()->where('status', 'revision')
            ->paginate(20);

        return view('documents.forrevision', [
            'documents' => $documents
        ]);
    }

    public function completed()
    {
        $documents = Document::withoutTrashed()->with(['participants','attachments'])->where('creator_id', auth()->id())->where('status', 'approved')->paginate(20);
        return view('documents.completed', [
            'documents' => $documents
        ]);
    }

    public function history()
    {
        $userId = Auth::id();
        $documents = Document::withoutTrashed()->whereNot('creator_id', $userId) // Exclude documents created by the user
            ->whereHas('history', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('action', '!=', 'queue'); 
            })
            ->orderByDesc(
                DocumentHistory::select('created_at')
                    ->whereColumn('document_histories.document_id', 'documents.id')
                    ->latest()
                    ->take(1)
            )
            ->paginate(20);

        return view('documents.history', [
            'documents' => $documents
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'reference'  => 'nullable|string',
            'priority'     => 'required|in:normal,high,urgent',
            'target_date'  => 'nullable|date',
            'participants'    => 'required|array|min:1',
            'participants.*'  => 'required|exists:users,id',
            'attachments'  => 'required|array|min:1',
            'attachments.*'=> 'file|mimes:pdf,doc,docx,jpg,png|max:10240', // 10MB limit
        ]);

        try {
            DB::beginTransaction();

            // 2. Create the main Document record
            $document = Document::create([
                'creator_id'     => Auth::id(),
                'title'       => $request->title,
                'description' => $request->description,
                'reference' => $request->reference,
                'priority'    => $request->priority,
                'target_date' => $request->target_date,
                'status'      => 'pending', // Initial status
            ]);

            // 3. Create the Workflow (Reviewers) in the specific order provided
            foreach ($request->participants as $index => $reviewerId) {
                DocumentParticipant::create([
                    'document_id' => $document->id,
                    'user_id'     => $reviewerId,
                    'order'       => $index + 1,
                    'status'      => ($index === 0) ? 'active' : 'queued', // Set first reviewer to active
                ]);
            }

            // 4. Handle File Attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->store('documents/attachments', 'public');

                    DocumentAttachment::create([
                        'document_id' => $document->id,
                        'file_path'   => $path,
                        'file_name'   => $originalName,
                        'file_type'   => $file->getClientOriginalExtension(),
                        'file_size'   => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            DocumentHistoryController::log(auth()->id(), $document->id, 'Created');
            return redirect()->route('documents.inprogress')
                ->with('success', 'Document routing workflow has been launched successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $docid)
    {
        $document = Document::with(['participants', 'attachments'])->findOrFail($docid);

        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:normal,high,urgent',
            'target_date' => 'nullable|date',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
            'keep_attachments' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // 2. Update the main Document record
            $document->update([
                'creator_id'  => Auth::id(),
                'title'       => $request->title,
                'description' => $request->description,
                'reference'   => $request->reference,
                'priority'    => $request->priority,
                'target_date' => $request->target_date,
                'status'      => 'pending', // Initial status
            ]);

            // 3. Update Workflow (Participants)
            // We delete old participants and recreate to maintain the new "order" sequence
            $document->participants()->delete();

            foreach ($request->participants as $index => $reviewerId) {
                DocumentParticipant::create([
                    'document_id' => $document->id,
                    'user_id'     => $reviewerId,
                    'order'       => $index + 1,
                    'status'      => ($index === 0) ? 'active' : 'queued', // Set first reviewer to active
                ]);
            }

            // 4. Handle Attachments
            // A. Remove attachments not included in 'keep_attachments'
            $keepIds = $request->input('keep_attachments', []);
            $attachmentsToDelete = $document->attachments()->whereNotIn('id', $keepIds)->get();

            foreach ($attachmentsToDelete as $file) {
                Storage::disk('public')->delete($file->file_path);
                $file->delete();
            }

            // B. Process New Uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('documents/attachments', 'public');
                    
                    $document->attachments()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            DocumentHistoryController::log(auth()->id(), $document->id, 'Resubmitted after Revision');

            return redirect()->route('documents.inprogress')
                ->with('success', 'Document updated and resubmitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update document: ' . $e->getMessage());
        }
    }

    public function create()
    {
        // Retrieve all users except the current authenticated user to act as signers/recipients
        $users = User::withoutTrashed()
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email']);

        return view('documents.editor', compact('users'));
    }

    public function edit($id)
    {
        $users = User::withoutTrashed()->where('id', '!=', Auth::id())
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email']);

        $document = Document::with(['participants', 'attachments'])->findOrFail($id);
        return view('documents.editor', compact(['document', 'users']));
    }

    public function sign($id)
    {
        $doc = Document::with(['participants','history', 'history.user'])->find($id);
        return view('documents.sign', compact('doc'));
    }

    public function process(Request $request, $docid)
    {
        $document = Document::with('participants')->findOrFail($docid);
        $user = Auth::user();
        $acted = '';
        
        // 1. Validation
        $request->validate([
            'action' => 'required|in:approved,rejected,revision',
            'comment' => 'nullable|string|max:500',
        ]);

        // 2. Verify if this user is the current active participant
        $currentParticipant = $document->participants()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
        if (!$currentParticipant) {
            return redirect()->back()->with('error', 'You do not have permission to sign this document at this time.');
        }

        try {
            DB::transaction(function () use ($request, $document, $currentParticipant) {
                $action = $request->input('action');
                $comment = $request->input('comment');
                if ($action === 'approved') {
                    // Update current participant to completed
                    $currentParticipant->update([
                        'status' => 'approved',
                        'signed_at' => now(),
                    ]);

                    // Look for the next participant in the sequence
                    $nextParticipant = $document->participants()
                        ->where('order', '>', $currentParticipant->order)
                        ->orderBy('order', 'asc')
                        ->first();

                    if ($nextParticipant) {
                        // Move to next person
                        $nextParticipant->update(['status' => 'active']);
                        $acted = 'Approved & Forwarded to ' . $nextParticipant->user->name;

                    } else {
                        // No one left, document is fully completed
                        $document->update(['status' => 'approved']);
                        $acted = 'Approved & Finalized';
                    }

                } elseif ($action === 'rejected') {
                    // Mark participant as rejected and kill the document flow
                    $currentParticipant->update([
                        'status' => 'rejected',
                    ]);

                    $document->update(['status' => 'rejected']);
                    $acted = 'Rejected';

                } elseif ($action === 'revision') {
                    // Send back to creator for revision
                    $currentParticipant->update([
                        'status' => 'revision', 
                    ]);

                    $document->update(['status' => 'revision']);
                    $acted = 'Returned for Revision';

                }
                
                DocumentHistoryController::log(auth()->id(), $document->id, $acted, $comment);

            });

            return redirect()->route('dashboard')->with('success', $acted);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function preview($id)
    {
        $attachment = DocumentAttachment::findOrFail($id);
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
        $attachment = DocumentAttachment::findOrFail($id);
        $path = $attachment->file_path;
        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'The requested file does not exist on the server.');
        }
        return Storage::disk('public')->download(
            $path, 
            $attachment->file_name
        );
    }

    public function replace(Request $request, $id)
    {
        // 1. Validate the incoming request
        $request->validate([
            'attachment' => 'required|file|max:10240', // 10MB limit
        ]);

        // 2. Find the existing attachment record
        $attachment = DocumentAttachment::findOrFail($id);

        try {
            DB::transaction(function () use ($request, $attachment) {
                // 3. Delete the old file from physical storage
                if (Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }

                // 4. Store the new file
                $newFile = $request->file('attachment');
                $originalName = $newFile->getClientOriginalName();
                $newPath = $newFile->store('documents/attachments', 'public');

                // 5. Update the database record with new metadata
                $attachment->update([
                    'file_path' => $newPath,
                    'file_name' => $originalName,
                    'file_type' => $newFile->getClientOriginalExtension(),
                    'file_size' => $newFile->getSize(),
                ]);
            });
            DocumentHistoryController::log(auth()->id(), $attachment->document_id, 'Replaced Attachment');
            return back()->with('success', 'File replaced successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to replace file: ' . $e->getMessage());
        }
    }

    public function rejected()
    {
        $documents = Document::with('history')->where('status', 'rejected')->paginate(20);
        return view('documents.rejected', compact('documents'));
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
}