<?php

namespace App\Http\Controllers;
use App\Interfaces\NotificationInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
    }
    public function markAsRead($notificationId)
    {

        $user = Auth::user();

        // Marcar la notificación como leída
        $notification = $user->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }


    }
}
