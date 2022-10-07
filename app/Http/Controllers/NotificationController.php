<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications;
        return response()->json($user);
    }
}
