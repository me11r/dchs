<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UserController extends AuthorizedController
{
    public function getCard($user_id)
    {

    }

    public function getPassword()
    {
        $this->set('user', \Auth::user());
    }

    public function postPassword(Request $request)
    {
        $rules['password'] = 'required|min:6|confirmed';
        $this->validate($request, $rules);

        $user = \Auth::user();
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return redirect('/')->with('_message', ['type' => 'success', 'text' => 'Пароль пользователя успешно изменен']);
    }

    public function getTelegram()
    {
        $user = \Auth::user();
        if ($user->telegram_chat_id !== null)
        {
            return redirect('https://t.me/glotusbot');
        }
        if ($user->telegram_code === null)
        {
            $user->telegram_code = random_int(10000000, 99999999);
            $user->save();
        }
    }
}