<?php

namespace App\Services;


use App\Http\Requests\Fcm\RegisterRequest;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class FcmService
{
    use AuthenticatesUsers;

    /**
     * @param RegisterRequest $request
     * @return bool
     * @throws AuthorizationException
     */
    public function register(RegisterRequest $request)
    {
        /** @var User $user */
        $user = $this->getUser($request);
        $user->device_token = $request->get('device_token');
        $user->save();

        return true;
    }

    /**
     * @param array $tokens
     * @param string $title
     * @param string $body
     */
    public function sendToMany(array $tokens, string $title, string $body){
        $optionBuilder = new OptionsBuilder();

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification);

        dd($downstreamResponse);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws AuthorizationException
     */
    private function getUser(Request $request)
    {
        if (!$this->attemptLogin($request)) {
            throw new AuthorizationException();
        }

        return auth()->user();
    }
}
