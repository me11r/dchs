<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\NormNumber;
use App\NormPsp;
use App\NormPspDepartment;
use App\NormType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NormPspController extends Controller
{
    private $view = 'norms-psp';

    public function index(Request $request)
    {
        $records = NormPsp::select('*');
        $data['per_page'] = $request->get('per_page', 10);
        $data['card_type'] = $this->view;
        $data['records'] = $records->orderBy('id', 'desc')->paginate($data['per_page']);
        $data['can_delete'] = Auth::user()->hasRight('CAN_DELETE_NORMS_PSP');
        return view("card.$this->view.index", $data);
    }

    public function edit(Request $request, $id)
    {
        $data['record'] = NormPsp::find($id);
        $data['norm_numbers'] = NormNumber::all();
        $data['norm_types'] = NormType::all();
        $data['fire_department'] = $data['record']->fire_department; //Auth::user()->department ? Auth::user()->department : json_encode(null);
        $data['can_select_fd'] = Auth::user()->hasRight('CAN_SELECT_FD_NORMS_PSP');
        $data['fire_departments'] = FireDepartment::all();
        $data['departments'] = $data['record']->departments;

        return view("card.$this->view.create-edit", $data);
    }

    public function update(Request $request, $id)
    {
        $model = $request->except(['departments']);
        $data['record'] = NormPsp::find($id);
        $data['record']->fill($model);
        $data['record']->save();

        if($request->departments) {
            $data['record']->departments()->delete();
            foreach ($request->departments as $item) {
                NormPspDepartment::create([
                    'name' => $item,
                    'norm_id' => $data['record']->id,
                ]);
            }
        }

        return back();
    }

    public function create(Request $request)
    {
        if($request->isMethod('post')){
            $data['record'] = NormPsp::create([
                'date' => $request->date,
                'time_begin' => $request->time_begin,
                'time_end' => $request->time_end,
                'fire_department_id' => $request->fire_department_id,
                'department' => $request->department,
                'norm_number_id' => $request->norm_number_id,
                'norm_type_id' => $request->norm_type_id,
                'responsible_person' => $request->responsible_person,
            ]);

            if($request->departments) {
                $data['record']->departments()->delete();
                foreach ($request->departments as $item) {
                    NormPspDepartment::create([
                        'name' => $item,
                        'norm_id' => $data['record']->id,
                    ]);
                }
            }

            return redirect()->route('norms-psp.edit', $data['record']->id);
        }
        $data = [];
        $data['fire_departments'] = FireDepartment::all();
        $data['norm_numbers'] = NormNumber::all();
        $data['norm_types'] = NormType::all();
        $data['can_select_fd'] = Auth::user()->hasRight('CAN_SELECT_FD_NORMS_PSP');
        $data['fire_department'] = Auth::user()->department ? Auth::user()->department : json_encode(null);

        return view("card.$this->view.create-edit", $data);
    }

    public function delete($id)
    {
        NormPsp::destroy($id);

        return back();
    }
}
