<?php

namespace App\Http\Controllers;

use App\Http\Resources\MudflowProtectionResource;
use App\Repositories\Contracts\MudflowProtectionInterface;
use App\Repositories\Contracts\RiverInterface;
use App\Services\ReportExport\MudflowExcelExport;
use Illuminate\Support\Facades\Auth;
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

    public function exportExcel()
    {
        $rivers = $this->repository->with([
            'gaugingStations',
            'gaugingStations.mudflowProtection'
        ])->get();

        $fileName = 'Казселезащита.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new MudflowExcelExport();
        $writer->getXlsWriter();

        return View::make('mudflow.index')
            ->with('rivers', $rivers)
            ->render();
    }

    public function index()
    {
        if(!Auth::user()->hasRight(['CAN_VIEW_MUDFLOW_PROTECTION'])){
            $this->throwAccessDenied();
        }

        $rivers = $this->repository->with([
            'gaugingStations',
            'gaugingStations.mudflowProtection'
        ])->get();

        return View::make('mudflow.index')
            ->with('rivers', $rivers)
            ->render();
    }

    public function create()
    {
        abort(418, 'Раздел в разработке');
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        return View::make('mudflow.edit')
            ->with('model', new MudflowProtectionResource($this->mudflowProtection->with(['gaugingStation'])->find($id)))
            ->render();
    }

    public function store(Request $request)
    {
        abort(418, 'Раздел в разработке');
    }

    public function update(Request $request, $id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_MUDFLOW_PROTECTION'])){
            $this->throwAccessDenied();
        }
        $this->mudflowProtection->update($request->all(), $id);
        return redirect(route('mudflowProtection.index'));
    }

    public function destroy($id)
    {
        abort(418, 'Раздел в разработке');
    }
}