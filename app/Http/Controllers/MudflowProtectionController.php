<?php

namespace App\Http\Controllers;

use App\Http\Resources\MudflowProtectionResource;
use App\Models\GaugingStation;
use App\Models\MudflowProtection;
use App\Models\River;
use App\MudflowProtectionBlock;
use App\Repositories\Contracts\MudflowProtectionInterface;
use App\Repositories\Contracts\RiverInterface;
use App\Services\ReportExport\MudflowExcelExport;
use App\Services\ReportExport\ReportMudflowWord;
use Carbon\Carbon;
use foo\bar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class MudflowProtectionController extends Controller
{
    private $repository;
    private $mudflowProtection;

    public function __construct(RiverInterface $repository, MudflowProtectionInterface $mudflowProtection)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->mudflowProtection = $mudflowProtection;
    }

    public function exportExcel($date)
    {
        $fileName = 'Казселезащита.docx';

        $exportService = new ReportMudflowWord($date);
        $writer = $exportService->getWriter('Word2007');
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }

    public function indexByDate($date)
    {
        $rivers = $this->repository->with([
            'gaugingStations',
            'gaugingStations.mudflowProtection'
        ])->get();

        $block = MudflowProtectionBlock::where('date', $date)->first();

        $records = MudflowProtection::where('date', $date)
            ->get()
            ->keyBy('gauging_station_id');

        $dateHuman = Carbon::parse($date)->format('d.m.Y');

        return View::make('mudflow.index')
            ->with('rivers', $rivers)
            ->with('block', $block)
            ->with('records', $records)
            ->with('date', $date)
            ->with('dateHuman', $dateHuman)
            ->render();
    }

    public function list(Request $request)
    {
        $data['now'] = now()->format('Y-m-d');
        $data['per_page'] = $request->per_page ?? 15;

        $data['records'] = MudflowProtection::groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate($data['per_page']);

        return view('mudflow.list', $data);
    }

    public function create($date, $gaugingStationId)
    {
        $data['date'] = $date;
        $data['model'] = json_encode(null);
        $data['formRoute'] = "/mudflow-protection/{$date}/{$gaugingStationId}/store";
        $data['gaugingStation'] = GaugingStation::find($gaugingStationId);

        return view('mudflow.edit',$data);
    }

    public function createEditBlock(Request $request, $date)
    {
        $data['date'] = $date;

        $data['record'] = MudflowProtectionBlock::where('date', $date)->first();

        if ($request->isMethod('POST')) {
            $data['record'] = MudflowProtectionBlock::updateOrCreate([
                'id' => $request->id,
            ],[
                'date' => $request->date,
                'text_header' => $request->text_header,
                'text_footer' => $request->text_footer,
            ]);
            return back();
        }

        return view('mudflow.block',$data);
    }

    public function show($date)
    {
        $rivers = $this->repository->with([
            'gaugingStations',
            'gaugingStations.mudflowProtection'
        ])->get();

        $block = MudflowProtectionBlock::where('date', $date)->first();

        $records = MudflowProtection::where('date', $date)
            ->get()
            ->keyBy('gauging_station_id');

        $dateHuman = Carbon::parse($date)->format('d.m.Y');

        return View::make('mudflow.show')
            ->with('rivers', $rivers)
            ->with('block', $block)
            ->with('records', $records)
            ->with('date', $date)
            ->with('dateHuman', $dateHuman)
            ->render();
    }

    public function edit($date, $id)
    {
        $data['date'] = $date;
        $data['model'] = MudflowProtection::findOrFail($id);
        $data['formRoute'] = "/mudflow-protection/{$date}/{$id}/update";
        $data['gaugingStation'] = $data['model']->gaugingStation;

        return view('mudflow.edit', $data);
    }

    public function delete($date, $id)
    {
        $data['model'] = MudflowProtection::destroy($id);

        return redirect("/mudflow-protection/{$date}");
    }

    public function store(Request $request, $date, $id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_MUDFLOW_PROTECTION'])){
            $this->throwAccessDenied();
        }

        $all = $request->all();
        $all['date'] = Carbon::parse($all['date'])->format('Y-m-d');

        $block = MudflowProtectionBlock::firstOrCreate(['date' => $all['date']]);

        $data = MudflowProtection::create($all);

        return redirect("/mudflow-protection/{$date}");
    }

    public function update(Request $request, $date, $id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_MUDFLOW_PROTECTION'])){
            $this->throwAccessDenied();
        }

        $all = $request->all();
        $all['date'] = Carbon::parse($all['date'])->format('Y-m-d');

        $block = MudflowProtectionBlock::firstOrCreate(['date' => $all['date']]);

        $this->mudflowProtection->update($all, $id);
        return back();
    }
}