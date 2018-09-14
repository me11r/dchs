<?php

namespace App\Http\Controllers;

use App\Models\MorainicLake;
use App\Models\MorainicLakeReport;
use App\Models\MorainicLakeSummary;
use Illuminate\Http\Request;

class MorainicLakeSummaryController extends Controller
{

    private $repository;
    private $table = 'morainic-lakes-summaries';

    public function __construct(MorainicLakeSummary $repository)
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

        $items = MorainicLakeReport::all();
//        $items = $this->repository->paginate($per_page);

        return view("$this->table.index", compact('items', 'per_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = $this->repository->all();
        $lakes = MorainicLake::all();
        $title = 'Создать запись';
//        $tech = (new FormationTechReport)->with('formation_tech_items')->where('form_id', $form_id)->get()->keyBy('dept_id');


        return view("$this->table.edit", compact('items', 'title', 'lakes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $all = $request->all();
        foreach ($request->input('lake', []) as $key => $item) {
            $date = date('Y-m-d');
            $item['morainic_lake_id'] = $key;
            $item['date'] = $date;

//            MorainicLakeSummary::updateOrCreate(['morainic_lake_id' => $key, 'date' => $date],$item);
            $this->repository->create($item);
        }
//        $this->repository->create($request->all());
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
        $title = 'Редактировать запись';
        $record = $this->repository::find($id);
        $lakes = MorainicLake::all();

        return view("$this->table.edit", compact('title', 'record', 'lakes'));
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
