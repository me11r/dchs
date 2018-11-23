<?php

namespace App\Http\Controllers;

use App\Models\MorainicLake;
use App\Models\MorainicLakeReport;
use App\Models\MorainicLakeSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $items = MorainicLakeReport::orderBy('date', 'desc')->paginate($per_page);
//        $items = $this->repository->orderBy('date', 'desc')->paginate($per_page)->unique('date');

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
        if(!Auth::user()->hasRight(['CAN_CREATE_MORAINIC_LAKE_SUMMARIES'])){
            $this->throwAccessDenied();
        }

        $all = $request->all();
        $date = date('Y-m-d');
        $report = MorainicLakeReport::firstOrCreate(['date' => $date]);
        foreach ($request->input('lake', []) as $id => $item) {
            $item['morainic_lake_id'] = $id;
            $item['date'] = $date;

            MorainicLakeSummary::updateOrCreate(['morainic_lake_id' => $id, 'date' => $date],$item);
        }
        return redirect()->route("$this->table.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!Auth::user()->hasRight(['CAN_VIEW_MORAINIC_LAKE_SUMMARIES'])){
            $this->throwAccessDenied();
        }

        $lakesSummary = $this->repository::where('date', $id)->get();
        $lakesSumRaw = $this->repository::where('date', $id);
        $date = $id;
        $report = MorainicLakeReport::where('date', $date)->first();
        $otherRecords = MorainicLakeReport::where('date', '!=', $date)->get();
        $compare_with = $request->compare_with;
        $comparedRecord = MorainicLakeReport::where('date', $compare_with)->first();
        $comparedRecords = $this->repository::where('date', $compare_with);

        $comparedRecordsResult['initial_volume'] = $lakesSumRaw->sum('initial_volume') - $comparedRecords->sum('initial_volume');
        $comparedRecordsResult['current_volume'] = $lakesSumRaw->sum('current_volume') - $comparedRecords->sum('current_volume');
        $comparedRecordsResult['inflow_glacier'] = $lakesSumRaw->sum('inflow_glacier') - $comparedRecords->sum('inflow_glacier');
        $comparedRecordsResult['drainage_total'] = ($lakesSumRaw->sum('drainage_via_evacuation_channel') + $lakesSumRaw->sum('drainage_via_pump') + $lakesSumRaw->sum('drainage_via_siphon')) - ($comparedRecords->sum('drainage_via_evacuation_channel') + $comparedRecords->sum('drainage_via_pump') + $comparedRecords->sum('drainage_via_siphon'));
        $comparedRecordsResult['water_dropped_day'] = $lakesSumRaw->sum('water_dropped_day') - $comparedRecords->sum('water_dropped_day');
        $comparedRecordsResult['water_dropped_total'] = $lakesSumRaw->sum('water_dropped_total') - $comparedRecords->sum('water_dropped_total');
        $comparedRecordsResult['zero_isotherm'] = $lakesSumRaw->sum('zero_isotherm') - $comparedRecords->sum('zero_isotherm');
        return view('morainic-lakes-reports.show', compact(
            'lakesSummary',
            'lakesSumRaw',
            'otherRecords',
            'compare_with',
            'comparedRecords',
            'comparedRecordsResult',
            'comparedRecord',
            'date',
            'report'
        ));
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
        $records = $this->repository::where('date', $id)->get();
        $lakes = MorainicLake::all();
        $date = $id;

        return view("$this->table.edit", compact('title', 'records', 'lakes', 'date'));
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
        if(!Auth::user()->hasRight(['CAN_EDIT_MORAINIC_LAKE_SUMMARIES'])){
            $this->throwAccessDenied();
        }

        foreach ($request->input('lake', []) as $key_id => $item) {
            $item['morainic_lake_id'] = $key_id;
            $item['date'] = $id;

            MorainicLakeSummary::updateOrCreate(['morainic_lake_id' => $key_id, 'date' => $id],$item);
        }

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
