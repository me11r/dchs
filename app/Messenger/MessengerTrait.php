<?php

namespace App\Messenger;

use App\Models\Messenger\Message;
use App\Role;
use App\User;

trait MessengerTrait
{
    /**
     * @param null $message
     */
    public function sendMessageAboutFormationAction($message = null): void
    {
        if (\Auth::user() && $this->isUserHaveToSendMessageAboutFormationAction()) {
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
        return Role::whereName('emergency_service')->first()->id === \Auth::user()->role_id;
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    private function getUsersToNoticeAboutFormationAction()
    {
        return User::whereRoleId(Role::whereName('dispatcher_od112')->first()->id)->get();
    }

}
