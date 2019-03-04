<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\VehicleClass;
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
    public function index(Request $request)
    {
        $per_page = 20;
        $search = $request->search;
        $filter_department = $request->filter_department;
        $fullAccess = Auth::user()->hasRight('VEHICLES_FULL_VIEW_ACCESS');
        $fire_departments = FireDepartment::all();
        $user = Auth::user();

        $items = $fullAccess ? $this->repository : Auth::user()->vehicles();

        if($search) {
            $items = $items
                ->whereHas('fireDepartment', function ($q) use ($search) {
                    $q->where('title', "like", "$search%");
                })
                ->orWhere(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%$search%")
                        ->orWhere('number_old', 'like', "$search%")
                        ->orWhere('number', 'like', "$search%")
                        ->orWhere('base', 'like', "$search%");
                });
        }

        if($filter_department) {
            $items = $items->where('fire_department_id', $filter_department);
        }

        $items = $items->orderBy('fire_department_id')
            ->paginate($per_page);

        return view('vehicle.index', compact(
            'items',
            'search',
            'user',
            'fire_departments',
            'filter_department',
            'per_page'
        ));
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
        $vehicle_classes = VehicleClass::all();
        $title = 'Создать запись';
        return view('vehicle.edit', compact(
            'items',
            'fire_departments',
            'vehicle_types',
            'vehicle_classes',
            'title'));
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
        $vehicle_classes = VehicleClass::all();
        $title = 'Редактировать запись';
        $record = Vehicle::find($id);
        return view('vehicle.edit', compact('items',
            'fire_departments',
            'vehicle_types',
            'vehicle_classes',
            'title',
            'record'));
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
