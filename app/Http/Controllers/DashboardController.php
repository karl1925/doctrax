<?php

namespace App\Http\Controllers;

use App\Models\{Document, External};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $exstats['endorsedAFD'] = External::withoutTrashed()->where('status', 'pending')->where('division', 'AFD')->get()->count();
        $exstats['endorsedTOD'] = External::withoutTrashed()->where('status', 'pending')->where('division', 'TOD')->get()->count();
        $activeRequests = External::withoutTrashed()->where('status', '!=', 'completed');
        if (!auth()->user()->canMonitorRequests()) {
            $activeRequests->where(function ($query) {
                $query->where('creator_id', auth()->id())
                    ->orWhere('assigned_to', auth()->id())
                    ->orWhereHas('histories', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
            });
        }
        $activeRequests = $activeRequests->orderBy('updated_at', 'desc')->get();
        $authTasks = External::withoutTrashed()->whereIn('status', ['assigned', 'accepted'])->where('assigned_to', auth()->id())->get()->count();
        
        $stats = Document::selectRaw("
            COUNT(CASE WHEN EXISTS (
                SELECT 1 FROM document_participants dp
                WHERE dp.document_id = documents.id
                AND dp.user_id = ?
                AND dp.status = 'active'
            ) AND documents.status = 'pending' THEN 1 END) as forSigning,
            SUM(creator_id = ? AND status = 'pending') AS inProgress,
            SUM(creator_id = ? AND status = 'revision') AS forRevision,
            SUM(creator_id = ? AND status = 'rejected'
                AND MONTH(created_at) = MONTH(CURRENT_DATE)
                AND YEAR(created_at) = YEAR(CURRENT_DATE)
            ) AS rejected,
            SUM(creator_id = ? AND status = 'approved'
                AND MONTH(created_at) = MONTH(CURRENT_DATE)
                AND YEAR(created_at) = YEAR(CURRENT_DATE)
            ) AS completed
        ", [$userId, $userId, $userId, $userId, $userId])
        ->first()
        ->toArray();

        $actionRequired = Document::whereHas('participants', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('status', 'active')
                ->whereNot('priority', 'normal');
        })
        ->where('status', 'pending')
        ->with(['owner', 'attachments']) 
        ->get();
        $needsRevision = Document::with('owner')
            ->where('status', 'revision')
            ->orderBy('created_at', 'desc')
            ->get();
        $recentDocuments = Document::with(['owner', 'participants.user'])
            ->where('creator_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        return view('dashboard', [
            'stats'           => $stats,
            'exstats'         => $exstats, 
            'authTasks'         => $authTasks, 
            'activeRequests'  => $activeRequests,
            'actionRequired'  => $actionRequired,
            'needsRevision'   => $needsRevision,
            'recentDocuments' => $recentDocuments,
        ]);
    }

    public function manual() {
        return view('manual');
    }
}