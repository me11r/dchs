<?php

namespace App\Messenger;

use App\Models\Messenger\Message;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;

trait MessengerTrait
{
    /**
     * @param null $message
     */
    public function sendMessageAboutFormationAction($message = null): void
    {
        if (Auth::user() && $this->isUserHaveToSendMessageAboutFormationAction()) {
            $users = $this->getUsersToNoticeAboutFormationAction();

            if ($users->count() > 0) {
                if (!$message) {
                    $message = 'Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в строевой записке / оперативной информации';
                }

                /** @var User $user */
                foreach ($users as $user) {
                    (new Message([
                        'message' => $message,
                        'sender_id' => \Auth::user()->id,
                        'reciever_id' => $user->id
                    ]))->save();
                }
            }
        }
    }

    /**
     * @return bool
     */
    private function isUserHaveToSendMessageAboutFormationAction(): bool
    {
        return Auth::user()->hasRight('CAN_SEND_NOTIFICATION_FORMATION_RECORD', true);
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    private function getUsersToNoticeAboutFormationAction()
    {
        return User::whereHas('role.rights', function ($q){
            $q->where('name', 'CAN_RECEIVE_NOTIFICATION_FORMATION_RECORD');
        })->get();
    }

}
