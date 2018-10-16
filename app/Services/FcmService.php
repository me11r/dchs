<?php

namespace App\Services;


use App\Http\Requests\Fcm\RegisterRequest;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use \FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Response\DownstreamResponse;

class FcmService
{
    use AuthenticatesUsers;

    /**
     * @param RegisterRequest $request
     * @return bool
     * @throws AuthorizationException
     */
    public function register(RegisterRequest $request): bool
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
     * @return DownstreamResponse
     */
    public function sendToMany(array $tokens, string $title, string $body): DownstreamResponse
    {
        $optionBuilder = new OptionsBuilder();

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        /** @var DownstreamResponse $downstreamResponse */
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification);
        $this->modifyTokens($downstreamResponse->tokensToModify());

        return $downstreamResponse;
    }

    /**
     * @param array $tokensToModify
     */
    private function modifyTokens(array $tokensToModify): void
    {
        foreach ($tokensToModify as $oldToken => $newToken) {
            $user = (new User)->where('device_token', '=', $oldToken)->first();
            if ($user) {
                $user->device_token = $newToken;
                $user->save();
            }
        }
    }

    /**
     * @param Request $request
     * @return User
     * @throws AuthorizationException
     */
    private function getUser(Request $request): User
    {
        if (!$this->attemptLogin($request)) {
            throw new AuthorizationException('Incorrect email or password');
        }

        return auth()->user();
    }
}
