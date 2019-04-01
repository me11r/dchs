<?php

namespace App\Http\Controllers;

use App\AirRescueReport;
use App\CheckpointShiftStaff;
use App\CheckpointShiftStaffItem;
use App\CheckpointShiftStaffReport;
use App\Dictionary\CityArea;
use App\DistrictManager;
use App\DutyPersonsService;
use App\Enums\FormationOrganisation;
use App\Exceptions\AccessDeniedException;
use App\FormationDistrictManager;
use App\FormationDistrictManagerItem;
use App\Models\FormationRecord;
use App\Models\Staff;
use App\OperDutyShift;
use App\OperDutyShiftStaff;
use App\OperDutyShiftStaffItem;
use App\OperDutyShiftStaffReport;
use App\Services\ReportExport\FormationRecordWordExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class FormationRecordController extends Controller
{
    public function singleIndex(Request $request, $organisation)
    {
        $perPage = $request->get('per_page', 10);
        $user = Auth::user();
        $this->createTodayForOrganisation($organisation);

        return View::make('formation-record.single-index')
            ->with('per_page', $perPage)
            ->with('user', $user)
            ->with('organisation', $organisation)
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

        $dutyPersonsService = DutyPersonsService::whereDate('date',$date)->first();

        $dutyPersonsServiceArr = [
            ['name' => 'Департамент полиции 102', 'value' => $dutyPersonsService->police_dept102 ?? null],
            ['name' => 'Скорая медицинская помощь 103', 'value' => $dutyPersonsService->ambulance103 ?? null],
            ['name' => 'Служба газа 104', 'value' => $dutyPersonsService->gas_service104 ?? null],
        ];


        if($formationDistrictManager){
            foreach ($cityAreas as $key => $area) {
                foreach ($formationDistrictManager->items_active()->get() as $ppl) {
                    $cityAreas[$key] = $area;
                }
            }
        }

        $dutyShiftItems = OperDutyShiftStaffItem::date($date)
            ->whereNull('inactive_type')
            ->with(['staff'])
            ->get();

        $dutyShiftItemsInactive = OperDutyShiftStaffItem::date($date)
            ->whereNotNull('inactive_type')
            ->with(['staff'])
            ->get();

        $dutyShiftCheckpointItems = CheckpointShiftStaffItem::date($date)
            ->whereNull('inactive_type')
            ->with(['staff'])
            ->get();

        $dutyShiftCheckpointItemsInactive = CheckpointShiftStaffItem::date($date)
            ->whereNotNull('inactive_type')
            ->with(['staff'])
            ->get();

        $items = (new FormationRecord())->where('date', '=', $item->date)
            ->whereNotIn('organisation', [
                FormationOrganisation::DCHS_ALMATY,
                FormationOrganisation::DISTRICT_MANAGERS,
            ])
            ->get();

        $airRescueReport = AirRescueReport::byDate($item->date)
            ->with(['tech'])
            ->first();

        foreach ($items as $item){
            if($item->organisation == 'air_rescue' && $airRescueReport){
                $item->head = $airRescueReport->staff_head;
                $item->head_count = $airRescueReport->staff_head_count;
                $item->head_phone = $airRescueReport->staff_head_phone;
                $item->senior_shift_name = $airRescueReport->senior_shift_name;
                $item->staff_total = $airRescueReport->staff_total;
                $item->staff_action = $airRescueReport->staff_action;
                $item->staff_duty_shift = $airRescueReport->staff_duty_shift;
                $item->staff_duty_shift_8hours = $airRescueReport->staff_duty_shift_8hours;
                $item->tech_main_action = $airRescueReport->getTechString('action');//$airRescueReport->tech()->where('status', 'action')->count();
                $item->tech_main_reserve = $airRescueReport->getTechString('reserve');//$airRescueReport->tech()->where('status', 'reserve')->count();
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

        $data = [
            'formationMainRecord' => $item,
            'fields' => $fields,
            'cityAreas' => $cityAreas,
            'formationDistrictManager' => $formationDistrictManager,
            'dutyShiftItems' => $dutyShiftItems,
            'dutyShiftItemsInactive' => $dutyShiftItemsInactive,
            'dutyShiftCheckpointItems' => $dutyShiftCheckpointItems,
            'dutyShiftCheckpointItemsInactive' => $dutyShiftCheckpointItemsInactive,
            'formationRecords' => $items,
            'dutyPersonsServiceArr' => $dutyPersonsServiceArr,
        ];

        Cache::put('formation_record_journal_data', $data, 3600);

        return View::make('formation-record.total-edit')
            ->with('item', $item)
            ->with('fields', $fields)
            ->with('cityAreas', $cityAreas)
            ->with('formationDistrictManager', $formationDistrictManager)
            ->with('dutyShiftItems', $dutyShiftItems)
            ->with('dutyShiftItemsInactive', $dutyShiftItemsInactive)
            ->with('dutyShiftCheckpointItems', $dutyShiftCheckpointItems)
            ->with('dutyShiftCheckpointItemsInactive', $dutyShiftCheckpointItemsInactive)
            ->with('dutyPersonsServiceArr', $dutyPersonsServiceArr)
            ->with('items', $items);
    }

    public function update($id, Request $request)
    {
        $item = $request->get('items', [])[$id];
        $itemModel = (new FormationRecord())->findOrFail($id);

        $item['date'] = Carbon::parse($request->date)->format('Y-m-d');

        if($itemModel->approved && !Auth::user()->hasRight(['CAN_EDIT_APPROVED_FORMATION_RECORD'])){
            $this->throwAccessDenied();
        }

        $itemModel->update($item);
        return redirect(route('formation-record.edit', ['id' => $itemModel->id]));
    }

    public function totalUpdate($id, Request $request)
    {
        $f = $request->all();
        foreach ($request->get('items', []) as $itemId => $item) {
            $itemModel = (new FormationRecord())->find($itemId);
            $itemModel->update($item);
        }
        return redirect(route('formation-record.total-edit', ['id' => $id]));
    }

    public function create(Request $request, $organisation)
    {
        if($request->isMethod('post')) {
            $date = Carbon::parse($request->date)->format('Y-m-d');

            $model = $this->createTodayForOrganisation($organisation, $date);

            return redirect("/formation-record/{$model->id}/edit");
        }
        else {
            $date = today();
            $data['date'] = $date;
            $data['organisation'] = $organisation;
            $data['organisationName'] = FormationOrganisation::getNameByType($organisation);
            return view('formation-record.single-create',$data);
        }

    }

    private function createTodayForOrganisation($organisation, $date = null)
    {
        $today = $date ? $date : Carbon::today();
        $todayModel = FormationRecord::firstOrCreate([
            'organisation' => $organisation,
            'date' => $today,
        ]);
        return $todayModel;
    }

    public function staffCreateEdit(Request $request, $date, $operShift_id = 1)
    {
        $busyStaff = OperDutyShiftStaffItem::date($date)
            ->whereHas('report', function ($q) use ($operShift_id) {
                $q->where('shift_id', '<>', $operShift_id);
            })
            ->pluck('staff_id')
            ->toArray();

        $data['staff'] = OperDutyShiftStaff::whereNotIn('id', $busyStaff)->get();
        $data['shift'] = OperDutyShift::find($operShift_id);
        $data['ods'] = OperDutyShift::all();
        $data['shift_id'] = $operShift_id;
        $data['date'] = $date;
        $data['report'] = OperDutyShiftStaffReport::date($date)
            ->where('shift_id', $operShift_id)
            ->first();

        if($request->isMethod('post')){
            $all = $request->all();

            $this->hasRightToEdit();

            $report = OperDutyShiftStaffReport::firstOrCreate([
                'date' => $date,
                'shift_id' => $operShift_id,
            ]);

            $report->items()->delete();

//            OperDutyShiftStaffItem::date($date)
//                ->where('shift_id', $operShift_id)
//                ->where('report_id', $operShift_id)
//                ->delete();

            foreach ($request->input('staff', []) as $rank => $staff_arr) {
                foreach ($staff_arr['staff_id'] as $id) {
                    OperDutyShiftStaffItem::create([
//                        'shift_id' => $operShift_id,
                        'staff_id' => $id,
                        'report_id' => $report->id,
                        'rank' => $rank,
                        'date' => $date,
                    ]);
                }
            }

            foreach ($request->input('staff_inactive', []) as $rank => $staff_arr) {
                foreach ($staff_arr['staff_id'] as $key => $id) {

                    $inactiveType = $request->input("staff_inactive.{$rank}.inactive_type.{$key}", null);
                    $dateFrom = $request->input("staff_inactive.{$rank}.date_from.{$key}", null);
                    $dateTo = $request->input("staff_inactive.{$rank}.date_to.{$key}", null);
                    $comment = $request->input("staff_inactive.{$rank}.comment.{$key}", null);

                    OperDutyShiftStaffItem::create([
//                        'shift_id' => $operShift_id,
                        'report_id' => $report->id,
                        'staff_id' => $id,
                        'rank' => $rank,
                        'date' => $date,
                        'inactive_type' => $inactiveType,
                        'date_from' => $dateFrom ? Carbon::parse($dateFrom)->format('Y-m-d') : null,
                        'date_to' => $dateTo ? Carbon::parse($dateTo)->format('Y-m-d') : null,
                        'comment' => $comment,
                    ]);
                }
            }

            $report->note = $request->note;
            $report->save();
        }

        return \view('formation-record.staff.create-edit', $data);
    }

    public function staffCheckpointCreateEdit(Request $request, $date, $operShift_id = 1)
    {
        $busyStaff = CheckpointShiftStaffItem::date($date)
            ->whereHas('report', function ($q) use ($operShift_id) {
                $q->where('shift_id', '<>', $operShift_id);
            })
            ->pluck('staff_id')
            ->toArray();

        $data['staff'] = CheckpointShiftStaff::whereNotIn('id', $busyStaff)->get();
        $data['shift'] = OperDutyShift::find($operShift_id);
        $data['ods'] = OperDutyShift::all();
        $data['shift_id'] = $operShift_id;
        $data['date'] = $date;
        $data['report'] = CheckpointShiftStaffReport::date($date)
            ->where('shift_id', $operShift_id)
            ->first();

        if($request->isMethod('post')){
            $all = $request->all();

            $this->hasRightToEdit();

            $report = CheckpointShiftStaffReport::firstOrCreate([
                'date' => $date,
                'shift_id' => $operShift_id,
            ]);

            $report->items()->delete();

            foreach ($request->input('staff', []) as $rank => $staff_arr) {
                foreach ($staff_arr['staff_id'] as $id) {
                    CheckpointShiftStaffItem::create([
                        'staff_id' => $id,
                        'report_id' => $report->id,
                        'rank' => $rank,
                        'date' => $date,
                    ]);
                }
            }

            foreach ($request->input('staff_inactive', []) as $rank => $staff_arr) {
                foreach ($staff_arr['staff_id'] as $key => $id) {

                    $inactiveType = $request->input("staff_inactive.{$rank}.inactive_type.{$key}", null);
                    $dateFrom = $request->input("staff_inactive.{$rank}.date_from.{$key}", null);
                    $dateTo = $request->input("staff_inactive.{$rank}.date_to.{$key}", null);
                    $comment = $request->input("staff_inactive.{$rank}.comment.{$key}", null);

                    CheckpointShiftStaffItem::create([
                        'report_id' => $report->id,
                        'staff_id' => $id,
                        'rank' => $rank,
                        'date' => $date,
                        'inactive_type' => $inactiveType,
                        'date_from' => $dateFrom ? Carbon::parse($dateFrom)->format('Y-m-d') : null,
                        'date_to' => $dateTo ? Carbon::parse($dateTo)->format('Y-m-d') : null,
                        'comment' => $comment,
                    ]);
                }
            }

            $report->note = $request->note;
            $report->save();
        }

        return \view('formation-record.staff-checkpoint.create-edit', $data);
    }

    public function districtManagersCreateEdit(Request $request, $date)
    {
        $data['report'] = FormationDistrictManager::whereDate('date', $date)->first();
        $data['staff'] = DistrictManager::all();
        $data['districts'] = CityArea::all();
        $data['date'] = $date;

        if($request->isMethod('post')){
            $all = $request->all();

            $this->hasRightToEdit();

            $report = FormationDistrictManager::firstOrCreate([
                'date' => $request->date
            ]);

            $report->items()->delete();

            foreach ($request->input('manager_id', []) as $city_area_id => $staff_arr) {
                foreach ($staff_arr as $key => $id) {

                    $inactiveType = $request->input("inactive_type.{$city_area_id}.$id", null);
                    $dateFrom = $request->input("date_from.{$city_area_id}.$id", null);
                    $dateTo = $request->input("date_to.{$city_area_id}.$id", null);
                    $comment = $request->input("comment.{$city_area_id}.$id", null);

                    $report->items()->create([
                        'manager_id' => $id,
                        'city_area_id' => $city_area_id,
                        'inactive_type' => $inactiveType,
                        'date_from' => $dateFrom ? Carbon::parse($dateFrom)->format('Y-m-d') : null,
                        'date_to' => $dateTo ? Carbon::parse($dateTo)->format('Y-m-d') : null,
                        'comment' => $comment,
                    ]);
                }
            }
        }

        return \view('formation-record.district-managers.create-edit', $data);
    }

    public function dutyPersonsServicesCreateEdit(Request $request, $date)
    {
        $data['report'] = DutyPersonsService::whereDate('date', $date)->first();
        $data['date'] = $date;

        if($request->isMethod('post')){

            $this->hasRightToEdit();

            $report = DutyPersonsService::updateOrCreate([
                'date' => $request->date
            ],[
                'date' => $request->date,
                'police_dept102' => $request->police_dept102,
                'ambulance103' => $request->ambulance103,
                'gas_service104' => $request->gas_service104,
            ]);

            return back();

        }

        return \view('formation-record.duty-persons-services.create-edit', $data);
    }

    public function approve(Request $request, $id)
    {
        $formationRecord = FormationRecord::find($id);

        if(Auth::user()->hasRight(['CAN_APPROVE_FORMATION_RECORD'])){

            $formationRecord->approved = true;
            $formationRecord->save();
        }
        else{
            throw new AccessDeniedException();
        }

        return back();
    }

    public function hasRightToEdit()
    {
        $formationRecord = FormationRecord::whereDate('date', today())
            ->where('organisation', 'dchs_almaty')
            ->where('approved', true)
            ->first();

        if(!Auth::user()->hasRight(['CAN_EDIT_APPROVED_FORMATION_RECORD']) && $formationRecord){

            $this->throwAccessDenied();
        }

        return true;
    }

    public function saveTotalAsDocx()
    {
        if ($data = Cache::get('formation_record_journal_data')){
            $exportService = new FormationRecordWordExport($data);
            $writer = $exportService->getWriter();
            $date = $data['formationMainRecord']->created_at->addDay()->format('d.m.Y');
            $fileName = 'Журнал строевых записок ДЧС г.Алматы (' . $date . ').docx';

            $writer->save(public_path($fileName));
            return response()->download(public_path($fileName));
        }

        return dd('Кеш не заполнен');
    }

    public function destroy($id)
    {
        FormationRecord::destroy($id);
        return back();
    }
}
