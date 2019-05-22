<?php

namespace App\Http\Controllers;


use App\Exceptions\AccessDeniedException;
use App\FireDepartment;
use App\Models\ServiceType;
use App\Office;
use App\Right;
use App\Rights\Group;
use App\Role;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends AuthorizedController
{
    protected $paginator_rows = 30;

    /**
     * @param Request $request
     * @throws AccessDeniedException
     */
    public function getUsers(Request $request)
    {
        $this->needRight(Right::CAN_MANAGE_USERS);

        $perpage = $request->get('per_page', 20);
        $search = $request->input('search');
        $users = User::with('rights');
        if ($search !== null) {
            $users->where('name', 'like', $search . '%');
        }
        if (\Auth::user()->id !== 1) // not admin
        {
            $users->where('id', '<>', 1);
        }
        $users = $users->paginate($perpage);
        if ($search !== null) {
            $users->appends(['search' => $search]);
        }
        $this->set('users', $users)->set('per_page', $perpage)->set('search', $search);
    }

    /**
     * @param null $user_id
     * @throws AccessDeniedException
     */
    public function getUserEdit($user_id = null)
    {
        $this->needRight(Right::CAN_MANAGE_USERS);

        $rights = Right::with('group')->orderBy('right_group_id')->get();
        $roles = Role::all();
        $fire_departments = FireDepartment::all();
        $service_types = ServiceType::all();
        $user = User::findOrNew($user_id);
        if (($user_id !== null) and (!$user->exists)) // not new, but don't exists
        {
            throw new AccessDeniedException();
        }
        $this->set('rights', $rights)
            ->set('roles', $roles)
            ->set('service_types', $service_types)
            ->set('fire_departments', $fire_departments)
            ->set('user_record', $user);
    }

    /**
     * @param null $user_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws AccessDeniedException
     */
    public function postUserEdit($user_id = null, Request $request)
    {
        $this->needRight(Right::CAN_MANAGE_USERS);

        $rules = [
            'fullname' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user_id)],
            'phone_mobile' => ['numeric', 'nullable', Rule::unique('users')->ignore($user_id)],
            'phone_landline' => 'numeric|nullable',
            'phone_extended' => 'numeric|nullable',
        ];
        if ($user_id === null) {
            $rules['password'] = 'required|min:6|confirmed';
        }
        $this->validate($request, $rules);

        $user = User::findOrNew($user_id);
        if (($user_id !== null) and (!$user->exists)) // not new, but don't exists
        {
            throw new AccessDeniedException();
        }
        if ($user_id === 1 and \Auth::user()->id !== 1) {
            throw new AccessDeniedException();
        }
        $userdata = $request->only($user->fillable);
        $userdata['name'] = $request->input('fullname');
        $user->fill($userdata);
        if ($request->input('password') !== null) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

//        $user->rights()->sync(array_values($request->input('role', [])));

        return redirect('admin/users')->with('_message', ['type' => 'success', 'text' => 'Пользователь успешно сохранен в системе']);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws AccessDeniedException
     */
    public function getUserLock($user_id)
    {
        $this->needRight(Right::CAN_MANAGE_USERS);
        if ($user_id === 1) {
            return redirect()->back()->with('_message', ['type' => 'warning', 'text' => 'Администратор не может быть заблокирован']);
        }
        $user = User::findOrFail($user_id);
        $this->set('user', $user);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws AccessDeniedException
     */
    public function postUserLock($user_id)
    {
        $this->needRight(Right::CAN_MANAGE_USERS);
        if ($user_id === 1) {
            return redirect()->back()->with('_message', ['type' => 'warning', 'text' => 'Администратор не может быть заблокирован']);
        }
        $user = User::findOrFail($user_id);
        $user->rights()->toggle(Right::CAN_LOGIN);

        return redirect('admin/users')->with('_message', ['type' => 'success', 'text' => 'Блокировка изменена']);
    }

    public function getImpersonate($user_id)
    {
        if (($user_id === 1) || (\Auth::user()->id !== 1)) {
            return redirect()->back()->with('_message', ['type' => 'danger', 'text' => 'Только администратор может пользоватся этой функцией']);
        }
        $user = User::findOrFail($user_id);
        $this->set('user', $user);
    }

    public function postImpersonate($user_id)
    {
        if (($user_id === 1) || (\Auth::user()->id !== 1)) {
            return redirect()->back()->with('_message', ['type' => 'danger', 'text' => 'Только администратор может пользоватся этой функцией']);
        }
        $user = User::findOrFail($user_id);
        \Auth::loginUsingId($user->id);
        return redirect('/');
    }

    public function getPassword($user_id)
    {
        if (($user_id === 1) || (\Auth::user()->id !== 1)) {
            return redirect()->back()->with('_message', ['type' => 'danger', 'text' => 'Только администратор может пользоватся этой функцией']);
        }
        $user = User::findOrFail($user_id);
        $this->set('user', $user);
    }

    public function postPassword(Request $request, $user_id)
    {
        if (($user_id === 1) || (\Auth::user()->id !== 1)) {
            return redirect()->back()->with('_message', ['type' => 'danger', 'text' => 'Только администратор может пользоватся этой функцией']);
        }
        $rules['password'] = 'required|min:6|confirmed';
        $this->validate($request, $rules);

        $user = User::findOrFail($user_id);
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return redirect('admin/users')->with('_message', ['type' => 'success', 'text' => 'Пароль пользователя успешно изменен']);
    }

    public function delete($user_id)
    {
        $user = User::destroy($user_id);
        return redirect('admin/users')->with('_message', ['type' => 'success', 'text' => 'Пароль пользователя успешно удален']);
    }

}
