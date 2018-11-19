<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Card112\Card112Resource;
use App\Models\Card112\Card112;

use App\Services\Card112\NotificationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Card112Controller extends Controller
{

    public function sendNotifications(Request $request, NotificationService $notificationService)
    {
        $notificationService->sendNotificationsForGroups(
            $request->get('notificationMessage'),
            (int)$request->get('card112Id'),
            $request->get('notificationGroups', [])
        );

        return response()->json([]);
    }

    public function getCard112(Request $request)
    {
        return response()->json([
            'card112' => new Card112Resource(Card112::with([
                'popupNotifications',
                'popupNotifications.user',
                'popupNotifications.status',
                'popupNotifications.group'])
                ->where('id', '=', $request->get('id'))
                ->first())
        ]);
    }
}
