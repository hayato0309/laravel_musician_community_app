<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;

class NotificationController extends Controller
{
    public function index()
    {
        $unread_notifications = auth()->user()->unreadNotifications
            ->where('type', '<>', 'App\Notifications\UserRegisteredNotification');

        $read_notifications = auth()->user()->notifications
            ->where('type', '<>', 'App\Notifications\UserRegisteredNotification')
            ->where('read_at', '<>', NULL);

        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('unread_notifications', 'read_notifications'));
    }
}
