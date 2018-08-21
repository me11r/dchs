<?php

namespace App\Http\Controllers;

use App\Enums\FormationOrganisation;
use App\Models\FormationRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FormationRecordController extends Controller
{
    public function singleIndex($organisation)
    {
        $this->createTodayForOrganisation($organisation);

        return View::make('formation-record.single-index')
            ->with('items',
                (new FormationRecord)
                    ->where('organisation', '=', $organisation)
                    ->orderBy('id', 'DESC')
                    ->get())
            ->with('organisationName', FormationOrganisation::getNameByType($organisation));
    }

    public function index()
    {
        foreach (FormationOrganisation::$namesMapping as $organisation => $name) {
            $this->createTodayForOrganisation($organisation);
        }

        return View::make('formation-record.index')
            ->with('items',
                (new FormationRecord)
                    ->where('organisation', '=', FormationOrganisation::DCHS_ALMATY)
                    ->orderBy('id', 'DESC')
                    ->get())
            ->with('organisationName', FormationOrganisation::getNameByType(FormationOrganisation::DCHS_ALMATY));
    }

    public function edit($id)
    {
        $item = (new FormationRecord())->findOrFail($id);
        return View::make('formation-record.single-edit')->with('item', $item);
    }

    public function totalEdit($id)
    {
        $item = (new FormationRecord())->findOrFail($id);
        $items = (new FormationRecord())->where('date', '=', $item->date)
            ->where('organisation', '!=', FormationOrganisation::DCHS_ALMATY)
            ->get();
        return View::make('formation-record.total-edit')
            ->with('item', $item)
            ->with('items', $items);
    }

    public function update($id, Request $request)
    {
        $item = $request->get('items', [])[$id];
        $itemModel = (new FormationRecord())->findOrFail($id);
        $itemModel->update($item);
        return redirect(route('formation-record.edit', ['id' => $itemModel->id]));
    }

    public function totalUpdate($id, Request $request)
    {
        foreach ($request->get('items', []) as $itemId => $item) {
            $itemModel = (new FormationRecord())->find($itemId);
            $itemModel->update($item);
        }
        return redirect(route('formation-record.total-edit', ['id' => $id]));
    }

    private function createTodayForOrganisation($organisation)
    {
        $today = Carbon::today();
        $todayModel = (new FormationRecord())->where('date', $today)->where('organisation', $organisation)->first();
        if (!$todayModel) {
            $todayModel = (new FormationRecord())
                ->fill([
                    'organisation' => $organisation,
                    'date' => $today
                ])
                ->save();
        }
        return $todayModel;
    }
}
