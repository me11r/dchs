<?php

namespace App\Http\Controllers;

use App\Jobs\SendFcmMessages;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;

class TestController extends Controller
{
    public function fcm(Request $request)
    {
        $title = $request->get('title');
        $body = $request->get('body');
        $token = $request->get('token');
        $info = $request->get('info');

        if ($title && $body && $token) {
            $this->dispatch(new SendFcmMessages(
                [$token],
                $title,
                $body,
                null,
                $info
            ));
        }

        return View::make('test.fcm', ['request' => $request->all()]);
    }
}
