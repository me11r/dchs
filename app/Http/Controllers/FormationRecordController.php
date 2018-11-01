<?php

namespace App\Http\Controllers;

use App\AirRescueReport;
use App\Dictionary\CityArea;
use App\DistrictManager;
use App\Enums\FormationOrganisation;
use App\FormationDistrictManager;
use App\FormationDistrictManagerItem;
use App\Models\FormationRecord;
use App\Models\Staff;
use App\OperDutyShift;
use App\OperDutyShiftStaff;
use App\OperDutyShiftStaffItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FormationRecordController extends Controller
{
    public function singleIndex(Request $request, $organisation)
    {
        $perPage = $request->get('per_page', 10);
        $this->createTodayForOrganisation($organisation);

        return View::make('formation-record.single-index')
            ->with('per_page', $perPage)
            ->with('items',
                (new FormationRecord)
                    ->where('organisation', '=', $organisation)
                    ->orderBy('id', 'DESC')
                    ->paginate($perPage))
            ->with('organisationName', FormationOrganisation::getNameByType($organisation));
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        foreach (FormationOrganisation::$namesMapping as $organisation => $name) {
            $this->createTodayForOrganisation($organisation);
        }

        return View::make('formation-record.index')
            ->with('per_page', $perPage)
            ->with('items',
                (new FormationRecord)
                    ->where('organisation', '=', FormationOrganisation::DCHS_ALMATY)
                    ->orderBy('id', 'DESC')
                    ->paginate($perPage))
            ->with('organisationName', FormationOrganisation::getNameByType(FormationOrganisation::DCHS_ALMATY));
    }

    public function edit($id)
    {
        $item = (new FormationRecord())->findOrFail($id);
        $fields = (new FormationRecord())->getRows(); // собираем поля для таблицы

        return View::make('formation-record.single-edit')
            ->with('fields', $fields)
            ->with('item', $item);
    }

    public function totalEdit($id)
    {
        $item = (new FormationRecord())->findOrFail($id);
        $fields = (new FormationRecord())->getRows(); // собираем поля для таблицы

        $formationDistrictManager = FormationDistrictManager::date($item->date)->first();
        $date = $item->date;
        $cityAreas = CityArea::all();

        if($formationDistrictManager){
            foreach ($cityAreas as $key => $area) {
                foreach ($formationDistrictManager->items as $ppl) {
                    $cityAreas[$key] = $area;
                }
            }
        }

        $dutyShiftItems = OperDutyShiftStaffItem::date($date)->with(['staff', 'shift'])->get();
        $items = (new FormationRecord())->where('date', '=', $item->date)
            ->whereNotIn('organisation', [
                FormationOrganisation::DCHS_ALMATY,
//                FormationOrganisation::AIR_RESCUE,
                FormationOrganisation::DISTRICT_MANAGERS,
            ])
            ->get();

        $airRescueReport = AirRescueReport::whereDate('created_at', $item->date)
            ->with(['tech'])
            ->first();

        foreach ($items as $item){
            if($item->organisation == 'air_rescue' && $airRescueReport){
                $item->head = $airRescueReport->staff_head;
                $item->staff_total = $airRescueReport->staff_total;
                $item->staff_action = $airRescueReport->staff_action;
                $item->staff_duty_shift = $airRescueReport->staff_duty_shift;
                $item->tech_main_action = $airRescueReport->tech()->where('status', 'action')->count();
                $item->tech_main_reserve = $airRescueReport->tech()->where('status', 'reserve')->count();
                $item->tech_special_action = 0;
                $item->tech_special_reserve = 0;
                $item->tech_additional_action = 0;
                $item->tech_additional_reserve = 0;
                $item->tech_other_action = 0;
                $item->tech_other_reserve = 0;
                $item->gsm_gasoline = $item->jet_fuel_action ?? 0;
                $item->gsm_diesel = 0;
                $item->radio_stations = $airRescueReport->radio_stations ?? 0;
                $item->personal_respiratory_protection = $airRescueReport->personal_respiratory_protection ?? 0;
                $item->personal_protection = $airRescueReport->personal_protection ?? 0;
                $item->other_protection = $airRescueReport->other_protection ?? 0;
                $item->save();
            }
        }

        return View::make('formation-record.total-edit')
            ->with('item', $item)
            ->with('fields', $fields)
            ->with('cityAreas', $cityAreas)
            ->with('formationDistrictManager', $formationDistrictManager)
            ->with('dutyShiftItems', $dutyShiftItems)
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
        $todayModel = FormationRecord::firstOrCreate([
            'organisation' => $organisation,
            'date' => $today,
        ]);
        /*$todayModel = (new FormationRecord())->where('date', $today)->where('organisation', $organisation)->first();
        if (!$todayModel) {
            $todayModel = (new FormationRecord())
                ->fill([
                    'organisation' => $organisation,
                    'date' => $today
                ])
                ->save();
        }*/
        return $todayModel;
    }

    public function staffCreateEdit(Request $request, $date, $operShift_id = 1)
    {
        $busyStaff = OperDutyShiftStaffItem::date($date)
            ->where('shift_id', '<>', $operShift_id)
            ->pluck('staff_id')
            ->toArray();

        $data['staff'] = OperDutyShiftStaff::whereNotIn('id', $busyStaff)->get();
        $data['ods'] = OperDutyShift::all();
        $data['shift_id'] = $operShift_id;
        $data['date'] = $date;

        if($request->isMethod('post')){
            $all = $request->all();

            OperDutyShiftStaffItem::date($date)
                ->where('shift_id', $operShift_id)
                ->delete();

            foreach ($request->input('staff', []) as $rank => $staff_arr) {
                foreach ($staff_arr['staff_id'] as $id) {
                    OperDutyShiftStaffItem::create([
                        'shift_id' => $operShift_id,
                        'staff_id' => $id,
                        'rank' => $rank,
                        'date' => $date,
                    ]);
                }
            }
        }

        return \view('formation-record.staff.create-edit', $data);
    }

    public function districtManagersCreateEdit(Request $request, $date)
    {
        $data['report'] = FormationDistrictManager::whereDate('date', $date)->first();
        $data['staff'] = DistrictManager::all();
        $data['districts'] = CityArea::all();
        $data['date'] = $date;

        if($request->isMethod('post')){
            $all = $request->all();

            $report = FormationDistrictManager::firstOrCreate([
                'date' => $request->date
            ]);

            $report->items()->delete();

            foreach ($request->input('manager_id', []) as $city_area_id => $staff_arr) {
                foreach ($staff_arr as $id) {
                    $report->items()->create([
                        'manager_id' => $id,
                        'city_area_id' => $city_area_id,
                    ]);
                }
            }
        }

        return \view('formation-record.district-managers.create-edit', $data);
    }
}
