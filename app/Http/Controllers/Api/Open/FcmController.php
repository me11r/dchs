<?php

namespace App\Http\Controllers\Api\Open;

use App\Http\Requests\Fcm\RegisterRequest;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
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

    public function sendTest(\Request $request){
        $this->fcmService->sendToMany(
            $tokens = User::whereNotNull('device_token')->pluck('device_token')->toArray(),
            'Тест голова',
            'Тест тело'
        );
    }
}
