<?php

namespace App\Http\Controllers;

use App\PopupNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PopupNotificationController extends Controller
{
    public function check()
    {
        $user = Auth::user();
        $notifications = PopupNotification::where('receiver_id', $user->id)
            ->where('is_viewed', false)
            ->get();
        ;

        foreach ($notifications as $notification) {
            if (!$notification->is_permanent) {
                $notification->is_viewed = true;
                $notification->save();
            }
        }

        return response()->json(['notifications' => $notifications]);
    }

    public function mark_viewed(Request $request)
    {
        $notification = PopupNotification::setViewed($request->id);

        return response()->json([]);
    }
}
