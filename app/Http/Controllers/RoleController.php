<?php

namespace App\Http\Controllers;

use App\Right;
use App\Rights\Group;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $view = 'roles';

    public function index(Request $request)
    {
        $data['per_page'] = $request->get('per_page', 20);
        $data['records'] = Role::paginate($data['per_page']);
        $data['title'] = 'Список ролей';
        $data['create_path'] = "/admin/{$this->view}/create";
        $data['edit_path'] = "/admin/{$this->view}/edit/";
        $data['delete_path'] = "/admin/{$this->view}/delete/";

        return view("{$this->view}.index", $data);
    }

    public function create()
    {
        $rights = Right::all();
        $data = [
            'title' => 'Создать роль: ',
            'right_groups' => Group::all(),
            'rights' => $rights,
        ];
        $data['store_path'] = "/admin/{$this->view}/add-edit";
        $data['back_path'] = "/admin/{$this->view}";

        return view("{$this->view}.add-edit", $data);
    }

    public function edit($id)
    {
        $record = Role::findOrFail($id);
        $rights = Right::all();
        $data = [
            'title' => 'Редактировать роль: '.$record->title,
            'record' => $record,
            'right_groups' => Group::all(),
            'rights' => $rights,
        ];
        $data['store_path'] = "/admin/{$this->view}/add-edit";
        $data['back_path'] = "/admin/{$this->view}";

        return view("{$this->view}.add-edit", $data);
    }

    public function store(Request $request)
    {
        $data = $request->except('right');
        $record = Role::firstOrCreate(['id' => $request->id], $data);
        $rights = $request->right;

        if($rights){
            $record->rights()->sync($rights);
        }

        return redirect("admin/{$this->view}")->with('_message', ['type' => 'info', 'text' => 'Данные сохранены']);
    }

    public function delete(Request $request, $id)
    {
        $record = Role::find($id);
        $record->delete();

        return redirect("admin/{$this->view}")->with('_message', ['type' => 'info', 'text' => 'Роль удалена']);
    }

}

