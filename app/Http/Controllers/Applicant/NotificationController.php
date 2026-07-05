<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(15);
        return view('applicant.notifications.index', compact('notifications'));
    }

    public function getUnreadCount(Request $request)
    {
        $unreadCount = $request->user()->unreadNotifications()->count();
        $latestUnread = $request->user()->unreadNotifications()->first();

        return response()->json([
            'unread_count' => $unreadCount,
            'latest_unread' => $latestUnread ? [
                'id' => $latestUnread->id,
                'charity_type_name' => $latestUnread->data['charity_type_name'] ?? 'Application Update',
                'message' => $latestUnread->data['message'] ?? '',
                'created_at' => $latestUnread->created_at->diffForHumans(),
            ] : null,
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
