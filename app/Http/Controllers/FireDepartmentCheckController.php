<?php

namespace App\Http\Controllers;

use App\FireDepartmentCheck;
use foo\bar;
use Illuminate\Http\Request;

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
        $data['items'] = FireDepartmentCheck::paginate($data['per_page']);
        return view('fire-department-checks.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fire-department-checks.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        FireDepartmentCheck::create($request->all());
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
        $data['record'] = FireDepartmentCheck::find($id);
        return view('fire-department-checks.edit',$data);
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
        $data['record'] = FireDepartmentCheck::find($id);
        $data['record']->date = $request->date;
        $data['record']->user = $request->user;
        $data['record']->fire_dept = $request->fire_dept;
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
