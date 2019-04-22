<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Repositories\Contracts\SalvageInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalvageController extends Controller
{
    private $repository;

    public function __construct(SalvageInterface $salvage)
    {
        parent::__construct();
        $this->repository = $salvage;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = 20;
        $filter_department = $request->filter_department;
        $fullAccess = Auth::user()->currentRole() !== 'dispatcher_pch';
        $fire_departments = FireDepartment::all();
        $user = Auth::user();

        $items = $fullAccess ? $this->repository : Auth::user()->salvage();

        if($filter_department) {
            $items = $items->where('fire_department_id', '=', $filter_department);
        }

        $items = $items->orderBy('fire_department_id')
            ->paginate($per_page);

        return view('salvage.index', compact(
            'items',
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
        if(Auth::user()->currentRole() !== 'dispatcher_pch'){
            return redirect()->route('salvage.index');
        }
        else{
            $fire_department = FireDepartment::find(Auth::user()->fire_department_id);
        }

        $title = 'Создать запись';
        return view('salvage.edit', compact('items', 'title', 'fire_department'));
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
        return redirect()->route('salvage.index');
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
        if(Auth::user()->currentRole() !== 'dispatcher_pch' && Auth::user()->currentRole() !== 'admin'){
            return redirect()->route('salvage.index');
        }
        else {
            $fire_department = FireDepartment::find(Auth::user()->fire_department_id);
        }
        $title = 'Редактировать запись';
        $record = $this->repository->find($id);
        return view('salvage.edit', compact( 'title', 'record', 'fire_department'));
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
        return redirect()->route('salvage.index');
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
