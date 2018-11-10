<?php
/**
 * Created by PhpStorm.
 * User: nbah1990
 * Date: 09-Nov-18
 * Time: 10:15
 */

namespace App\Services\Ticket101;

use App\Entities\Fcm\FcmMessageData;
use App\Entities\Fcm\FcmMessage;
use App\Enums\FcmMessageType;
use App\Enums\NotificationStatusType;
use App\Jobs\SendFcmMessage;
use App\Models\Notification\Notification;
use App\Models\Notification\NotificationGroup;
use App\Ticket101;
use App\User;

class NotificationService
{
    /**
     * @param string $message
     * @param int $ticketId
     * @param array $groupIds
     */
    public function sendNotificationsForGroups(string $message, int $ticketId, array $groupIds)
    {
        $ticket = $this->setMessageForTicket101($message, $ticketId);

        $groups = (new NotificationGroup())->whereIn('id', $groupIds)->get();
        foreach ($groups as $group) {
            foreach ($group->users()->get() as $user) {
                $notification = new Notification();
                $notification->title = 'Карточка 101 №' . $ticket->id;
                $notification->body = $message;
                $notification->user_id = $user->id;
                $notification->notification_group_id = $group->id;
                $notification->notification_status_id = $user->device_token ? NotificationStatusType::SENT : NotificationStatusType::TOKEN_NOT_FOUND;
                $notification->send_date = date('Y-m-d H:i:s');
                $notification->save();

                $ticket->popup_notifications()->attach($notification->id);

                if ($user->device_token) {
                    $this->makeJob($user, $notification, $ticket);
                }
            }
        }
    }

    /**
     * @param string $message
     * @param int $ticketId
     * @return Ticket101|mixed
     */
    private function setMessageForTicket101(string $message, int $ticketId)
    {
        $ticket = (new Ticket101())->find($ticketId);
        $ticket->notification_message = $message;
        $ticket->notifications_sent = true;
        $ticket->save();

        return $ticket;
    }


    /**
     * @param User $user
     * @param Notification $notification
     * @param Ticket101 $ticket
     */
    private function makeJob(User $user, Notification $notification, Ticket101 $ticket)
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
                        ->setInfoId($ticket->id)
                        ->setMessageId($notification->id)
                        ->setMessageType(FcmMessageType::TICKET101_NOTIFICATION)
                )
        ));
    }
}
