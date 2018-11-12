<?php

namespace App\Http\Controllers\Api\Open;

use App\Enums\NotificationStatusType;
use App\Http\Requests\Fcm\RegisterRequest;
use App\Jobs\SendFcmMessages;
use App\Models\Notification\Notification;
use App\Ticket101;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\FcmService;
use Illuminate\Validation\ValidationException;

class FcmController extends Controller
{
    private $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function register(RegisterRequest $request)
    {
        $result = false;

        try {
            $result = $this->fcmService->register($request);
        } catch (AuthorizationException $e) {
            return response()
                ->json(['success' => $result, 'message' => 'Incorrect email or password'])
                ->setStatusCode(401);
        }

        return response()->json(['success' => $result, 'message' => '']);
    }

    public function displayInfo(Request $request, int $infoId)
    {
        $ticket = Ticket101::find($infoId);
        $content = $ticket ? view('open.fcm.info', compact('ticket'))->render() : 'Ticket not found';

        return response()->json([
            'content' => $content,
            'type' => 'html'
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function markMessageAsDelivered(Request $request)
    {
        $notification = (new Notification())->where('id', '=', (int)$request->get('message_id'))->first();
        if ($notification) {
            $notification->notification_status_id = NotificationStatusType::DELIVERED;
            $notification->receive_date = date('Y-m-d H:i:s');
            $notification->save();
        }
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function sendTest(Request $request)
    {
        dispatch(new SendFcmMessages(
            User::whereNotNull('device_token')->pluck('device_token')->toArray(),
            'title',
            'body',
            null,
            \random_int(10000, 99999)
        ));
    }
}
