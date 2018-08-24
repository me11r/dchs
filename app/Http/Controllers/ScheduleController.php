<?php

namespace App\Http\Controllers;

use App\Dictionary\FireLevel;
use App\FireDepartment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    private $repository;
    private $fire_dept;

    public function __construct(Schedule $repository, FireDepartment $fireDepartment)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->fire_dept = $fireDepartment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->fire_dept->all();

        return view('schedule.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fire_departments = FireDepartment::all();
        $vehicle_types = VehicleType::all();
        $title = 'Создать запись';
        return view('vehicle.edit', compact('items', 'fire_departments', 'vehicle_types','title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect()->route('vehicles.index');
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
        $result['fire_department'] = FireDepartment::find($id);
        $result['fire_departments'] = FireDepartment::all();
        $result['fire_levels'] = FireLevel::all();
        $result['schedules'] = $result['fire_department']
            ->schedules()
            ->orderBy('dict_fire_level_id')
            ->get();
        $result['title'] = 'Редактировать запись: '.$result['fire_department']->name;
        return view('schedule.edit', $result);
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
        $all = $request->all();
        $fireDept = FireDepartment::find($id);

        $fireDept->schedules()->delete();

        if($request->fire_department_id) {

            foreach ($request->fire_department_id as $key => $input) {
                Schedule::create([
                    'fire_department_main_id' => $fireDept->id,
                    'fire_department_id' => $input,
                    'dict_fire_level_id' => $request->input("dict_fire_level_id.$key"),
                    'is_reserved' => false,
                    'department' => $request->input("department.$key"),
                ]);
            }
        }

        return redirect()->route('schedules.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $repo = $this->repository->find($id);
        $repo->delete();
        return back();
    }
}
