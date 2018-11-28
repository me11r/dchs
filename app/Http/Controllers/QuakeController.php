<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuakeResource;
use App\Repositories\Contracts\QuakeInterface;
use App\Services\ReportExport\QuakeExcelExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class QuakeController extends Controller
{
    private $repository;

    public function __construct(QuakeInterface $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function index()
    {
        if(!Auth::user()->hasRight(['CAN_VIEW_QUAKES'])){
            $this->throwAccessDenied();
        }

        $items = $this->repository->orderBy('date_almaty', 'DESC')->get();

        return View::make('quakes.index')
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        if(!Auth::user()->hasRight(['CAN_CREATE_QUAKES'])){
            $this->throwAccessDenied();
        }
        return View::make('quakes.add')
            ->render();
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_QUAKES'])){
            $this->throwAccessDenied();
        }
        return View::make('quakes.edit')
            ->with('model', new QuakeResource($this->repository->find($id)))
            ->render();
    }

    public function store(Request $request)
    {
        if(!Auth::user()->hasRight(['CAN_CREATE_QUAKES'])){
            $this->throwAccessDenied();
        }
        $this->repository->create($request->all());
        return redirect(route('quakes.index'));
    }

    public function update(Request $request, $id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_QUAKES'])){
            $this->throwAccessDenied();
        }
        $this->repository->update($request->all(), $id);
        return redirect(route('quakes.index'));
    }

    public function destroy($id)
    {
        if(!Auth::user()->hasRight(['CAN_DELETE_QUAKES'])){
            $this->throwAccessDenied();
        }
        $this->repository->delete($id);
        return redirect(route('quakes.index'));
    }

    public function exportExcel()
    {
        if(!Auth::user()->hasRight(['CAN_VIEW_QUAKES'])){
            $this->throwAccessDenied();
        }

        $fileName = 'ТОО СОМЭ.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $exportService = new QuakeExcelExport();
        $writer = $exportService->getXlsWriter();
        $writer->save('php://output');
    }
}
