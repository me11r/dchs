<?php


namespace App\Http\Controllers\Api\Messenger;


use App\Http\Controllers\Controller;
use App\Models\Messenger\Message;
use App\User;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    public function getUserList(Request $request)
    {
        $me = \Auth::user();
        $users = (new User())
            ->where('id', '<>', $me->id)
            ->withCount('unreadMessages')
            ->orderBy('last_connect_at', 'desc')
            ->orderBy('id')
            ->get(['id', 'name', 'last_connect_at']);
        return response()->json(['users' => $users], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function postMessage(Request $request)
    {
        $me = \Auth::user();
        $to = (int)$request->get('to');
        $text = $request->get('message');
        $message = new Message([
            'message' => $text,
            'sender_id' => $me->id,
            'reciever_id' => $to
        ]);
        $message->save();

        return response()->json(['status' => 'ok']);
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request, $user_id)
    {
        $me = \Auth::user();
        $messages = (new Message())
            ->where(function ($query) use ($me, $user_id) {
                return $query->where('sender_id', $me->id)
                    ->where('reciever_id', $user_id);
            })
            ->orWhere(function ($query) use ($me, $user_id) {
                return $query->where('sender_id', $user_id)
                    ->where('reciever_id', $me->id);
            })
            ->orderBy('id', 'asc')
            ->limit(30)
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
