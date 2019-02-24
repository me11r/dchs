<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\FireDepartmentCheck;
use App\Models\Staff;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FireDepartmentCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['per_page'] = $request->per_page ?? 15;

        $items = (new FireDepartmentCheck())->groupBy('date')->orderBy('date', 'DESC');

        $data['items'] = $items->paginate($data['per_page']);
        return view('fire-department-checks.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->hasRight(['CAN_CREATE_CHECK_FD'])){
            $this->throwAccessDenied();
        }
        $date = date('Y-m-d');
        $count = FireDepartmentCheck::with(['fire_department'])->where('date', '=', $date)->count();
        if ($count > 0) {
            return redirect(route('fire-department-checks.update-by-date', $date));
        }

        $data['fire_depts'] = FireDepartment::all();
        return view('fire-department-checks.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->hasRight(['CAN_CREATE_CHECK_FD'])){
            $this->throwAccessDenied();
        }
        $date = $request->date ? $request->date : date('Y-m-d');
        foreach ($request->get('items', []) as $item) {
            $item['date'] = $date;
            FireDepartmentCheck::create($item);
        }
        return redirect('fire-department-checks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_CHECK_FD'])){
            $this->throwAccessDenied();
        }
        $data['record'] = FireDepartmentCheck::with(['fire_department'])->find($id);
        $data['fire_depts'] = FireDepartment::all();
        return view('fire-department-checks.edit',$data);
    }

    public function editByDay ($date)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_CHECK_FD'])){
            $this->throwAccessDenied();
        }
        $items = FireDepartmentCheck::with(['fire_department'])
            ->where('date', '=', $date)
            ->get()
            ->keyBy('id');

        $data['dspt'] = $items->where('is_dspt', '=', true)->all();
        $data['not_dspt'] = $items->where('is_dspt', '=', false)->all();

        $data['fire_depts'] = FireDepartment::all();
        $data['date'] = $date;

        $data['fire_dept_id'] = Auth::user()->fire_department_id ?? 0;

        return view('fire-department-checks.edit',$data);
    }

    public function updateByDay(Request $request, $date)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_CHECK_FD'])){
            $this->throwAccessDenied();
        }
        $items = $request->get('items', []);
        $ids = array_map(function($item) {
            return $item['id'];
        }, $items);

        $existsItems = FireDepartmentCheck::with(['fire_department'])->where('date', '=', $date)->get();
        foreach ($existsItems as $existsItem) {
            if (!\in_array($existsItem->id, $ids, false)){
                $existsItem->delete();
            }
        }

        foreach ($items as $item) {
            $item['date'] = $date;

            $model = FireDepartmentCheck::where('id', '=', $item['id'])->first();
            if (!$model){
                $model = new FireDepartmentCheck();
            }

            $model->fill($item);
            $model->save();
        }
//        $f = $request->all();
//        $data['record'] = FireDepartmentCheck::find($id);
//        $data['record']->fire_department_id = $request->fire_department_id;
//        $data['record']->time_begin = $request->time_begin;
//        $data['record']->time_end = $request->time_end;
//        $data['record']->responsible_person = $request->responsible_person;
//        $data['record']->note = $request->note;
//        $data['record']->save();

        return redirect('fire-department-checks');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_CHECK_FD'])){
            $this->throwAccessDenied();
        }
        $f = $request->all();
        $data['record'] = FireDepartmentCheck::find($id);
        $data['record']->fire_department_id = $request->fire_department_id;
        $data['record']->time_begin = $request->time_begin;
        $data['record']->time_end = $request->time_end;
        $data['record']->responsible_person = $request->responsible_person;
        $data['record']->note = $request->note;
        $data['record']->save();

        return redirect('fire-department-checks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FireDepartmentCheck::destroy($id);
        return back();
    }
}
