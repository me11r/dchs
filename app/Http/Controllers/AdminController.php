<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 18.04.2017
 * Time: 10:16
 */

namespace App\Http\Controllers;


use App\Exceptions\AccessDeniedException;
use App\Office;
use App\Right;
use App\Rights\Group;
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

        $search = $request->input('search');
        $users = User::with('rights');
        if ($search !== null) {
            $users->where('name', 'like', $search . '%');
        }
        if (\Auth::user()->id !== 1) // not admin
        {
            $users->where('id', '<>', 1);
        }
        $users = $users->paginate($this->paginator_rows);
        if ($search !== null) {
            $users->appends(['search' => $search]);
        }
        $this->set('users', $users)->set('search', $search);
    }

    /**
     * @param null $user_id
     * @throws AccessDeniedException
     */
    public function getUserEdit($user_id = null)
    {
        $this->needRight(Right::CAN_MANAGE_USERS);

        $rights = Right::with('group')->orderBy('right_group_id')->get();
        $user = User::findOrNew($user_id);
        if (($user_id !== null) and (!$user->exists)) // not new, but don't exists
        {
            throw new AccessDeniedException();
        }
        $this->set('rights', $rights)->set('user', $user);
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
        //dd($request->input('role'));
        $user->save();

        $user->rights()->sync(array_values($request->input('role', [])));

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
        return redirect('admin/users')->with('_message', ['type' => 'success', 'text' => 'Пароль пользователя успешно изменен']);
    }

    /**
     * @param Request $request
     * @param bool $trashed
     * @throws AccessDeniedException
     */
    public function getOffices(Request $request, $trashed = false)
    {
        $this->needRight(Right::CAN_MANAGE_OFFICES);
        $offices = new Office();
        if ($trashed !== false) {
            $offices = $offices->withTrashed();
        }
        $offices = $offices->get();
        $this->set('offices', $offices)->set('trashed', $trashed);
    }

    /**
     * @param Request $request
     * @param null $office_id
     * @throws AccessDeniedException
     */
    public function getOffice(Request $request, $office_id = null)
    {
        $this->needRight(Right::CAN_MANAGE_OFFICES);
        $office = Office::findOrNew($office_id);
        $this->set('office', $office);
    }

    /**
     * @param Request $request
     * @param null $office_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws AccessDeniedException
     */
    public function postOffice(Request $request, $office_id = null)
    {
        $this->needRight(Right::CAN_MANAGE_OFFICES);
        $office = Office::findOrNew($office_id);
        $rules = [
            'title' => 'required|min:3',
            'code' => ['required', 'size:3', Rule::unique('offices')->ignore($office_id)]
        ];
        if (($office_id !== null) and (!$office->exists)) // not new, but don't exists
        {
            throw new AccessDeniedException();
        }
        $this->validate($request, $rules);
        $office->fill($request->only($office->fillable));
        $office->save();

        return redirect('admin/offices')->with('_message', ['type' => 'success', 'text' => 'Изменения в офисе сохранены']);
    }

    /**
     * @param $office_id
     * @throws AccessDeniedException
     */
    public function getOfficeDelete($office_id)
    {
        $this->needRight(Right::CAN_MANAGE_OFFICES);
        $office = Office::findOrNew($office_id);
        $this->set('office', $office);
    }

    /**
     * @param $office_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws AccessDeniedException
     * @throws \Exception
     */
    public function postOfficeDelete($office_id)
    {
        $this->needRight(Right::CAN_MANAGE_OFFICES);
        $office = Office::findOrNew($office_id);
        $office->delete();

        return redirect('admin/offices')->with('_message', ['type' => 'success', 'Офис успешно удален из списка']);
    }

    public function getCities()
    {
        $this->needRight(Right::CAN_MANAGE_OFFICES);
    }
}