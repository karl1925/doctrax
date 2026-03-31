<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{External, User};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function readAll() {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['ok' => true]);
    }

    public function clearRead() {
        auth()->user()->notifications()->whereNotNull('read_at')->delete();
        return redirect()->back()->with('success', 'Read notifications cleared');
    }

    public function clearAll() {
        auth()->user()->notifications()->delete(); 
        return redirect()->back()->with('success', 'All notifications cleared.');
    }

    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        // Cursor for read notifications (load older notifications)
        $lastReadId = $request->query('last_read_id');

        // 🔹 1. Fetch latest 10 unread notifications
        $unread = $user->unreadNotifications()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // 🔹 2. Fetch 10 older read notifications using cursor
        $readQuery = $user->readNotifications()
            ->orderByDesc('created_at')
            ->orderByDesc('id'); // tie-breaker for same timestamp

        if ($lastReadId) {
            $readQuery->where('id', '<', $lastReadId);
        }

        $read = $readQuery->take(10)->get();

        // 🔹 3. Preload related data to avoid N+1 queries
        $allNotifications = $unread->concat($read);
        $requestIds = $allNotifications->pluck('data.request_id')->filter()->unique();
        $creatorIds = $allNotifications->pluck('data.created_by')->filter()->unique();

        $requests = External::whereIn('id', $requestIds)->get()->keyBy('id');
        $users = User::whereIn('id', $creatorIds)->get()->keyBy('id');

        // 🔹 4. Map notifications for frontend
        $mapNotification = function ($n, $isNew) use ($requests, $users) {
            $request = $requests[$n->data['request_id']] ?? null;
            $creator = $users[$n->data['created_by']] ?? null;

            return [
                'id' => $n->id,
                'type' => $n->data['subject'] ?? '',
                'subject' => $request?->subject ?? '',
                'message' => $n->data['message'] ?? '',
                'created_by' => $creator?->name ?? '',
                'url' => $n->data['url'] ?? '#',
                'time' => Carbon::parse($n->data['created_at'])->diffForHumans(),
                'is_new' => $isNew,
            ];
        };

        $unreadData = $unread->map(fn($n) => $mapNotification($n, true));
        $readData = $read->map(fn($n) => $mapNotification($n, false));

        // 🔹 5. Determine next cursor for pagination
        $nextReadId = $read->last()?->id;

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'new' => $unreadData,
            'earlier' => $readData,
            'next_read_id' => $nextReadId, // send cursor for next fetch
        ]);
    }

    public function markAsRead(Request $request)
    {
        $id = $request->input('id');

        $notification = Auth::user()->notifications()->find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read', 'id' => $id]);
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted', 'id' => $id]);
    }

}
