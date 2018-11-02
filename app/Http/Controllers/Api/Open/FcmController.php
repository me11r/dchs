<?php

namespace App\Http\Controllers\Api\Open;

use App\Http\Requests\Fcm\RegisterRequest;
use App\Jobs\SendFcmMessages;
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
        $content = <<<DEMO
<b>Вери импортант инфо</b><p>For <i>{$infoId}</i></p>
DEMO;
        return response()->json([
            'content' => $content,
            'type' => 'html'
        ], 200, [], JSON_UNESCAPED_UNICODE);
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
