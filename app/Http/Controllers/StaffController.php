<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class StaffController extends Controller
{

    private $repository;
    private $table = 'staff';

    public function __construct(Staff $repository)
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

        $items = $this->repository->orderBy('department_id')->paginate($per_page);

        return view("$this->table.index", compact('items', 'per_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fire_departments = FireDepartment::all();
        $title = 'Создать запись';
        $statuses = (new Staff())->statuses();

        return view("$this->table.edit", compact('items', 'fire_departments','title', 'statuses'));
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
        return redirect()->route("$this->table.index");
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
        $fire_departments = FireDepartment::all();
        $title = 'Редактировать запись';
        $record = $this->repository::find($id);
        $statuses = $record->statuses();
        return view("$this->table.edit", compact('items', 'fire_departments','title', 'record', 'statuses'));
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
        return redirect()->route("$this->table.index");
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
