<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Jobs\SendFcmMessages;
use App\Models\Ticket101\Ticket101Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function ticket101Send(Request $request)
    {
        $id = (int)$request->get('notification_id');
        $model = (new Ticket101Notification())
            ->where('id', '=', $id)
            ->with(['service', 'service.headUser', 'ticket101'])
            ->first();
        if ($model && $model->service->headUser && $model->service->headUser->device_token) {
            $user = $model->service->headUser;

            dispatch(new SendFcmMessages(
                [$user->device_token],
                'Карточка 101 №' . $model->ticket101_id,
                $model->ticket101->location,
                null,
                $model->id
            ));

            return response()->json([
                'success' => true,
                'time' => date('Y-m-d H:i:s'),
                'name' => $user->name . '(' . $user->email . ')'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Для службы "' . $model->service->name . '" не указан руководитель, либо у руководителя нет привязанного устройства.'
        ]);
    }

    public function ticket112Send(Request $request)
    {
        $id = (int)$request->get('notification_id');
        $model = (new Ticket101Notification())
            ->where('id', '=', $id)
            ->with(['service', 'service.headUser', 'ticket101'])
            ->first();
        if ($model && $model->service->headUser && $model->service->headUser->device_token) {
            $user = $model->service->headUser;

            dispatch(new SendFcmMessages(
                [$user->device_token],
                'Карточка 112 №' . $model->ticket112_id,
                $model->ticket112->location
            ));

            return response()->json([
                'success' => true,
                'time' => date('Y-m-d H:i:s'),
                'name' => $user->name . '(' . $user->email . ')'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Для службы "' . $model->service->name . '" не указан руководитель, либо у руководителя нет привязанного устройства.'
        ]);
    }
}
