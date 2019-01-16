<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\GuardNumber;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(Request $request)
    {
        $per_page = 20;
        $search = $request->search;
        $filter_department = $request->filter_department;

        $items = Auth::id() == 1 ? $this->repository : Auth::user()->staff();

        if($search) {
            $items = $items
                ->whereHas('department', function ($q) use ($search) {
                    $q->where('title', "like", "$search%");
                })
                ->orWhere(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%$search%")
                        ->orWhere('position', 'like', "$search%")
                        ->orWhere('surname', 'like', "$search%")
                        ->orWhere('patronymic', 'like', "$search%")
                        ->orWhere('rank', 'like', "$search%");
                });
        }

        if($filter_department) {
            $items = $items->where('department_id', $filter_department);
        }

        $items = $items->orderBy('department_id')
            ->orderBy('name')
            ->paginate($per_page);


        /*if(Auth::id() == 1){
            $items = $this->repository
                ->orderBy('department_id')
                ->orderBy('name')
                ->paginate($per_page);
        }
        else{
            $items = Auth::user()
                ->staff()
                ->orderBy('department_id')
                ->orderBy('name')
                ->paginate($per_page);
        }*/

        $user = Auth::user();
        $fire_departments = FireDepartment::all();

        return view("$this->table.index", compact(
            'items',
            'per_page',
            'user',
            'search',
            'filter_department',
            'fire_departments'));
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

        $title = 'Создать запись';
        $statuses = (new Staff())->statuses();
        $guardNumbers = GuardNumber::all();

        return view("$this->table.edit", compact('items', 'fire_departments','title', 'statuses', 'guardNumbers'));
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
        if(Auth::id() == 1){
            $fire_departments = FireDepartment::all();
        }
        else{
            $fire_departments = FireDepartment::where('id', Auth::user()->fire_department_id)->get();
        }
        $title = 'Редактировать запись';
        $record = $this->repository::find($id);
        $statuses = $record->statuses();
        $guardNumbers = GuardNumber::all();

        return view("$this->table.edit", compact('items', 'fire_departments','title', 'record', 'statuses', 'guardNumbers'));
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
