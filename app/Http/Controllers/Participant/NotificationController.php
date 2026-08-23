<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(15);
        return view(
            'participant.notifications.index',
            compact('notifications')
        );
    }
    public function read(string $notification)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }
    public function readAll()
    {
        Auth::user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);
        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}
