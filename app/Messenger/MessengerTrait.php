<?php

namespace App\Messenger;

use App\Models\Messenger\Message;
use App\Role;
use App\User;

trait MessengerTrait
{
    public function sendMessageAboutFormationAction($message = null)
    {
        if ($this->isUserHaveToSendMessageAboutFormationAction()) {
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

    private function isUserHaveToSendMessageAboutFormationAction()
    {
        return Role::whereName('emergency_service')->first()->id === \Auth::user()->role_id;
    }

    private function getUsersToNoticeAboutFormationAction()
    {
        return User::whereRoleId(Role::whereName('dispatcher_od112')->first()->id)->get();
    }

}
