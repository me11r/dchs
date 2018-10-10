<?php

namespace App\Services;


use App\Http\Requests\Fcm\RegisterRequest;
use App\User;
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
     * @throws ValidationException
     */
    public function register(RegisterRequest $request)
    {
        /** @var User $user */
        $user = $this->getUser($request);
        $user->device_token = $request->get('device_token');
        $user->save();

        return true;
    }

    public function sendToMany(array $tokens,string $title,string $body){
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
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    private function validateUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'unique:users',
            ]
        );

        return $validator;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Model|null
     * @throws ValidationException
     */
    private function getUser(Request $request)
    {
        if (!$this->attemptLogin($request)) {
            $validator = $this->validateUser($request);
            if (!$validator->fails()) {
                $user = User::create([
                    'name' => $request->get('email'),
                    'email' => $request->get('email'),
                    'password' => bcrypt($request->get('password'))
                ]);
            } else {
                throw new ValidationException($validator);
            }
        } else {
            $user = auth()->user();
        }

        return $user;
    }
}
