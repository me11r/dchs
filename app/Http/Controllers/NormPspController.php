<?php

namespace App\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\FireDepartment;
use App\NormNumber;
use App\NormPsp;
use App\NormPspDepartment;
use App\NormType;
use App\Right;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NormPspController extends Controller
{
    private $view = 'norms-psp';

    public function index(Request $request)
    {
        $records = NormPsp::orderBy('id', 'desc');
        $userFd = Auth::user()->fire_department_id;

        $data['per_page'] = $request->get('per_page', 20);
        $data['card_type'] = $this->view;
        $data['norm_types'] = NormType::all();
        $data['norm_numbers'] = NormNumber::all();
        $data['filter_fd'] = $request->filter_fd;
        $data['search'] = $request->search;
        $data['can_delete'] = Auth::user()->hasRight('CAN_DELETE_NORMS_PSP');
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        $data['norm_type_id'] = $request->norm_type_id;
        $data['norm_number_id'] = $request->norm_number_id;

        if ($userFd) {
            $data['fire_departments'] = FireDepartment::where('id', $userFd)
                ->sortByCustomOrder()
                ->get();
        } else {
            $data['fire_departments'] = FireDepartment::recommend()
                ->sortByCustomOrder()
                ->get();
        }

        if ($userFd) {
            $records = $records->where('fire_department_id', $userFd);
        }

        if ($request->filter_fd) {
            $records = $records->where('fire_department_id', $request->filter_fd);
        }

        if ($request->norm_type_id) {
            $records = $records->where('norm_type_id', $request->norm_type_id);
        }

        if ($request->norm_number_id) {
            $records = $records->where('norm_number_id', $request->norm_number_id);
        }

        if($data['date_from'] && $data['date_to']) {
            $records = $records->whereBetween('date', [$data['date_from'], $data['date_to']]);
        }

        if ($request->search) {
            $records = $records->where('responsible_person', "like", "%{$request->search}%")
                ->orWhere(function ($q) use ($request) {
                    $q->whereHas('norm_number', function ($qq) use ($request) {
                        $qq->where('name', "like", "%{$request->search}%");
                    })->orWhereHas('norm_type', function ($qqq) use ($request) {
                        $qqq->where('name', "like", "%{$request->search}%");
                    })
                    ;
                })
            ;
        }


        $data['records'] = $records->paginate($data['per_page']);
        return view("card.$this->view.index", $data);
    }

    public function edit(Request $request, $id)
    {
        $data['record'] = NormPsp::find($id);
        $data['norm_numbers'] = NormNumber::all();
        $data['norm_types'] = NormType::all();
        $data['fire_department'] = $data['record']->fire_department; //Auth::user()->department ? Auth::user()->department : json_encode(null);
        $data['can_select_fd'] = Auth::user()->hasRight('CAN_SELECT_FD_NORMS_PSP');
        $data['fire_departments'] = FireDepartment::sortByCustomOrder()->get();
        $data['departments'] = $data['record']->departments;

        /*user doesn't have rights to see card*/
        if (!Right::userFireDepartmentMatch($data['record']->fire_department_id)) {
            throw new AccessDeniedException();
        }

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
                'gdzs_included_30' => $request->gdzs_included_30,
                'note' => $request->note,
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
        $data['fire_departments'] = FireDepartment::recommend()->sortByCustomOrder()->get();
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
