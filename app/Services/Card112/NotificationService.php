<?php
/**
 * Created by PhpStorm.
 * User: nbah1990
 * Date: 09-Nov-18
 * Time: 10:15
 */

namespace App\Services\Card112;

use App\Entities\Fcm\FcmMessageData;
use App\Entities\Fcm\FcmMessage;
use App\Enums\FcmMessageType;
use App\Enums\NotificationStatusType;
use App\Jobs\SendFcmMessage;
use App\Models\Card112\Card112;
use App\Models\Notification\Notification;
use App\Models\Notification\NotificationGroup;
use App\User;

class NotificationService
{
    /**
     * @param string $message
     * @param int $cardId
     * @param array $groupIds
     */
    public function sendNotificationsForGroups(string $message, int $cardId, array $groupIds): void
    {
        $model = $this->setMessageForCard112($message, $cardId);

        $groups = (new NotificationGroup())->whereIn('id', $groupIds)->get();
        foreach ($groups as $group) {
            foreach ($group->users()->get() as $user) {
                $notification = new Notification();
                $notification->title = 'Карточка 112 №' . $model->id;
                $notification->body = $message;
                $notification->user_id = $user->id;
                $notification->notification_group_id = $group->id;
                $notification->notification_status_id = $user->device_token ? NotificationStatusType::SENT : NotificationStatusType::TOKEN_NOT_FOUND;
                $notification->send_date = date('Y-m-d H:i:s');
                $notification->save();

                $model->popupNotifications()->attach($notification->id);

                if ($user->device_token) {
                    $this->makeJob($user, $notification, $model);
                }
            }
        }
    }

    /**
     * @param string $message
     * @param int $cardId
     * @return Card112|mixed
     */
    private function setMessageForCard112(string $message, int $cardId)
    {
        $card = (new Card112())->find($cardId);
        $card->notification_message = $message;
        $card->notifications_sent = true;
        $card->save();

        return $card;
    }


    /**
     * @param User $user
     * @param Notification $notification
     * @param Card112 $card112
     */
    private function makeJob(User $user, Notification $notification, Card112 $card112)
    {
        dispatch(new SendFcmMessage(
            (new FcmMessage())
                ->setTitle($notification->title)
                ->setBody($notification->body)
                ->setToken($user->device_token)
                ->setAdditionalData(
                    (new FcmMessageData())
                        ->setTitle($notification->title)
                        ->setBody($notification->body)
                        ->setInfoId($card112->id)
                        ->setMessageId($notification->id)
                        ->setMessageType(FcmMessageType::CARD112_NOTIFICATION)
                )
        ));
    }
}
