<?php

namespace App\Services;


use App\Entities\Fcm\FcmMessage;
use App\Http\Requests\Fcm\RegisterRequest;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use \FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
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
     * @param null|string $action
     * @param null|int $infoId
     * @return DownstreamResponse
     */
    public function sendToMany(array $tokens, string $title, string $body, ?string $action = null, ?int $infoId = null): DownstreamResponse
    {
        $optionBuilder = new OptionsBuilder();

        $notificationBuilder = (new PayloadNotificationBuilder($title))
            ->setBody($body)
            ->setTitle($title);
        if ($action !== null) {
            $notificationBuilder->setClickAction($action);
        }
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'title' => $title,
            'body' => $body,
        ]);
        if ($infoId !== null) {
            $dataBuilder->addData([
                'infoId' => $infoId
            ]);
        }

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        /** @var DownstreamResponse $downstreamResponse */
        $downstreamResponse = FCM::sendTo(
            $tokens,
            $option,
            $notification,
            $data
        );

        $this->modifyTokens($downstreamResponse->tokensToModify());

        return $downstreamResponse;
    }


    /**
     * @param FcmMessage $fcmMessage
     * @return DownstreamResponse
     */
    public function sendMessage(FcmMessage $fcmMessage): DownstreamResponse
    {
        $optionBuilder = new OptionsBuilder();

        $notificationBuilder = (new PayloadNotificationBuilder($fcmMessage->getTitle()))
            ->setBody($fcmMessage->getBody())
            ->setTitle($fcmMessage->getTitle());

        if ($fcmMessage->getClickAction()) {
            $notificationBuilder->setClickAction($fcmMessage->getClickAction());
        }
        $dataBuilder = new PayloadDataBuilder();

        if ($fcmMessage->getAdditionalData()) {
            $dataBuilder->addData([
                'title' => $fcmMessage->getAdditionalData()->getTitle(),
                'body' => $fcmMessage->getAdditionalData()->getBody(),

                'infoId' => $fcmMessage->getAdditionalData()->getInfoId(),
                'messageId' => $fcmMessage->getAdditionalData()->getMessageId(),
                'messageType' => $fcmMessage->getAdditionalData()->getMessageType()
            ]);
        }

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        /** @var DownstreamResponse $downstreamResponse */
        $downstreamResponse = FCM::sendTo(
            $fcmMessage->getToken(),
            $option,
            $notification,
            $data
        );

        $this->modifyTokens($downstreamResponse->tokensToModify());

        return $downstreamResponse;
    }

    /**
     * @param array $tokensToModify
     */
    private function modifyTokens(array $tokensToModify): void
    {
        foreach ($tokensToModify as $oldToken => $newToken) {
            $user = User::where('device_token', '=', $oldToken)->first();
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
