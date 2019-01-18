<?php

namespace App\Http\Controllers;

use App\MessengerRight;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class MessengerRightController extends Controller
{
    public function edit()
    {
        $data['roles'] = Role::select('*')
            ->has('users')
            ->with(['users'])
            ->get();

        $data['users'] = User::select('*')
            ->has('role')
            ->with(['role','messenger_rights','messenger_rights_reverse'])
            ->get();

        return view('admin.messenger-permissions.edit', $data);
    }

    public function store(Request $request)
    {
        $f = $request->all();
        if($request->input('permissions', [])) {
            foreach ($request->permissions as $roleName => $users) {
                foreach ($users as $userId => $recipientIds) {
                    MessengerRight::where('user_id', $userId)->delete();

                    foreach ($recipientIds as $recipientId => $value) {
                        MessengerRight::create([
                            'user_id' => $userId,
                            'can_send_id' => $recipientId,
                        ]);
                    }
                }
            }
        }
        return redirect()->back();
    }
}
