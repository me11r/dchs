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
        $online = \DB::raw('IF((DATE_SUB(NOW(), INTERVAL 5 MINUTE) < last_connect_at), 1, 0) as online');
        $me = \Auth::user();
        $users = (new User())
            ->select(['id', 'email', 'name', 'last_connect_at', $online])
            ->where('id', '<>', $me->id)
            ->orderBy('online', 'desc')
            ->orderBy('id')
            ->get();
        $unread = $this->getUnreadCount($me->id);
        foreach ($users as &$user) {
            $user->unread_count = 0;
            if (isset($unread[$user->id])){
                $user->unread_count = $unread[$user->id]['cnt'];
            }
        }
        return response()->json(['users' => $users], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getUnreadCount($user_id)
    {
        $cgr = \DB::raw('count(*) as cnt');
        $messages = (new Message())
            ->where('reciever_id', $user_id)
            ->where('is_viewed', false)
            ->groupBy(['sender_id'])
            ->select(['sender_id', $cgr])
            ->get()
            ->keyBy('sender_id');
        return $messages;
    }

    public function postMessage(Request $request)
    {
        $me = \Auth::user();
        $to = $request->get('to');
        $text = $request->get('message');
        $type = $request->get('type', 'text');
        $file_id = $request->get('file_id', null);
        foreach ($to as $reciever) {
            $message = new Message([
                'message' => $text,
                'sender_id' => $me->id,
                'message_type'=> $type,
                'file_id' => $file_id,
                'reciever_id' => (int)$reciever
            ]);
            $message->save();
        }

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
            ->orderBy('id', 'desc')
            ->limit(150)
            ->get();

        $this->markAsRead($me, $user_id);

        return response()->json(['messages' => $messages]);
    }

    public function getAnyUnread(Request $request)
    {
        $me = \Auth::user();
        $unread = (new Message())
            ->where('reciever_id', $me->id)
            ->where('is_viewed', false)
            ->count();
        return response()->json(['unread' => $unread]);
    }

    protected function markAsRead(User $me, int $user_id)
    {
        $messages = (new Message())

            ->where(function ($query) use ($me, $user_id) {
                return $query->where('sender_id', $user_id)
                    ->where('reciever_id', $me->id);
            })
            ->update(['is_viewed' => true]);
        return $messages;
    }
}
