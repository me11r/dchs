<?php

namespace App\Http\Controllers;

use App\Models\Notification\NotificationGroup;
use App\User;
use Illuminate\Http\Request;

class NotificationGroupsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $items = NotificationGroup::with(['users'])->paginate($perPage);

        return response(view('dictionary.notification-groups.index', [
            'items' => $items,
            'per_page' => $perPage
        ]));
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create()
    {
        $item = new NotificationGroup();
        return response(view('dictionary.notification-groups.edit', [
            'item' => $item,
            'selectedUsers' => [],
            'users' => $this->getUsersOptions()
        ]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $group = new NotificationGroup();
        $group->fill($request->only('name'));
        $group->save();
        $group->users()->sync($request->get('users'));

        return redirect(route('notification-groups.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = (new NotificationGroup())->find($id);

        return response(view('dictionary.notification-groups.edit', [
            'item' => $item,
            'selectedUsers' => $item->users()->get()->toArray(),
            'users' => $this->getUsersOptions()
        ]));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $group = (new NotificationGroup())->find($id);
        $group->fill($request->only('name'));
        $group->save();
        $group->users()->sync($request->get('users'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $group = (new NotificationGroup())->find($id);
        $group->delete();

        return redirect()->back();
    }

    private function getUsersOptions()
    {
        return (new User())
            ->orderBy('name')
            ->get()
            ->toArray();
//            ->map(function (User $item) {
//                return
//                    [
//                        'id' => $item->id,
//                        'name' => $item->name .
//                            '(' .
//                            implode(',', array_filter(
//                                [$item->email, $item->call_name, $item->position],
//                                function ($value) {
//                                    return (bool)$value;
//                                })) .
//                            ')'
//                    ];
//            });
    }
}
