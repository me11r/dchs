<?php


namespace App\Http\Controllers\Api\Messenger;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    public function getUserList(Request $request)
    {
        $me = \Auth::user();
        $users = (new User())
            ->where('id', '<>', $me->id)
            ->orderBy('last_connect_at', 'desc')
            ->orderBy('id')
            ->get(['id', 'name', 'last_connect_at']);
        return response()->json(['users' => $users], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
