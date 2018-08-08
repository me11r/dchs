<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UserController extends AuthorizedController
{

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

}