<?php

namespace App\Http\Controllers;

use App\Http\Resources\MudflowProtectionResource;
use App\Models\GaugingStation;
use App\Models\MudflowProtection;
use App\Models\River;
use App\Repositories\Contracts\MudflowProtectionInterface;
use App\Repositories\Contracts\RiverInterface;
use App\Services\ReportExport\MudflowExcelExport;
use Carbon\Carbon;
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
        $fileName = 'Казселезащита.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $exportService = new MudflowExcelExport($date);
        $writer = $exportService->getXlsWriter();
        $writer->save('php://output');
    }

    public function indexByDate($date)
    {
        $rivers = $this->repository->with([
            'gaugingStations',
            'gaugingStations.mudflowProtection'
        ])->get();

        $records = MudflowProtection::where('date', $date)
            ->get()
            ->keyBy('gauging_station_id');

        $dateHuman = Carbon::parse($date)->format('d.m.Y');

        return View::make('mudflow.index')
            ->with('rivers', $rivers)
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

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
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
        $this->mudflowProtection->update($all, $id);
        return back();
    }
}