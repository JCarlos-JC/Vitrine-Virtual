<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

// }use App\Models\User;



class NotificationController extends Controller
{

//     public function index()
// {
//     $user = auth()->user();
//     $unreadNotifications = $user->unreadNotifications;

//     return view('notifications.index', [
//         'unreadNotifications' => $unreadNotifications
//     ]);
        public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

}
