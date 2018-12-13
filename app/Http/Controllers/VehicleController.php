<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{

    private $repository;

    public function __construct(Vehicle $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = 20;

        if(Auth::id() == 1){
            $items = $this->repository->orderBy('fire_department_id')->paginate($per_page);
        }
        else{
            $items = Auth::user()->vehicles()->orderBy('fire_department_id')->paginate($per_page);
        }

        return view('vehicle.index', compact('items', 'per_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::id() == 1){
            $fire_departments = FireDepartment::all();
        }
        else{
            $fire_departments = FireDepartment::where('id', Auth::user()->fire_department_id)->get();
        }

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
        if(Auth::id() == 1){
            $fire_departments = FireDepartment::all();
        }
        else{
            $fire_departments = FireDepartment::where('id', Auth::user()->fire_department_id)->get();
        }
        $vehicle_types = VehicleType::all();
        $title = 'Редактировать запись';
        $record = Vehicle::find($id);
        return view('vehicle.edit', compact('items', 'fire_departments', 'vehicle_types','title', 'record'));
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
        $repo = $this->repository->find($id);
        $repo->update($request->all());
        return redirect()->route('vehicles.index');
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
