<?php

namespace App\Http\Controllers;

use App\Aircraft;
use App\AirRescueReport;
use App\AirRescueReportPersonsItem;
use App\AirRescueReportTechItem;
use App\FireDepartment;
use App\Formation\Migrations;
use App\Formation\Operations;
use App\Formation\Resources;
use App\FormationMedicalReport;
use App\FormationMudflowReport;
use App\FormationOdPersonItem;
use App\FormationPersonsReport;
use App\FormationReport;
use App\FormationSaversReport;
use App\FormationTechReport;
use App\GuardNumber;
use App\Models\FormationPersonsItem;
use App\Models\FormationRecord;
use App\Models\FormationTechItem;
use App\Models\Staff;
use App\Models\Vehicle;
use App\OperationalGroupSchedule;
use App\Reports\Report;
use App\Right;
use App\Services\FormationService;
use App\StaffCpps;
use App\Ticket101Other;
use App\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spipu\Html2Pdf\Html2Pdf;

class FormationController extends AuthorizedController
{
    public function before()
    {
        parent::before();
        return $this->needAnyRight([
            Right::CAN_ACCESS_FORMATION_REPORT_101,
            Right::CAN_ACCESS_FORMATION_REPORT_ROSO,
            Right::CAN_ACCESS_FORMATION_REPORT_CMK,
            Right::CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION,
            Right::CAN_ACCESS_FORMATION_REPORT_AIR_RESCUE,
            Right::CAN_ACCESS_FORMATION_REPORT_ORTSERT
        ]);
    }

    public function get101(Request $request)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);
        $perpage = $request->get('per_page', 10);
        $today = date('Y-m-d');
        $has_today = ((new FormationReport)->where('report_date', $today)->count() > 0);
        $department_id = Auth::user()->fire_department_id;

        $read_only = Auth::user()->hasRight(Right::CAN_READ_ONLY_FORMATION);
        if($read_only || Auth::user()->isRole('admin')){
            $department_id = FireDepartment::select('*')->first()->id;
        }


        if (!$has_today) {
            (new FormationReport)
                ->fill(['report_date' => $today])
                ->save();
        }
        $this
            ->set('department_id', $department_id)
            ->set('today', $today)
            ->set('per_page', $perpage)
            ->set('read_only', $read_only)
            ->set('reports', (new FormationReport)->orderBy('report_date','desc')->paginate($perpage));
    }

    public function getAirRescue(Request $request)
    {
        $data['per_page'] = $request->get('per_page', 10);
        $data['reports'] = AirRescueReport::orderBy('id', 'desc')->paginate($data['per_page']);
        $data['today'] = now();

        return view('formation.air-rescue.index', $data);
    }

    public function getAirRescueView(Request $request, $id)
    {
        $data['report'] = AirRescueReport::find($id);
        $data['total_persons_count'] = $data['report']->staff_total;
        $data['total_persons_head_count'] = $data['report']->staff_head;
        $data['total_persons_active_count'] = $data['report']->staff_total;
        $data['total_persons_available_count'] = $data['report']->staff_action;
        $data['total_persons_oper_count'] = $data['report']->staff_duty_shift;

        $data['tech_active'] = $data['report']->tech()->status('action')->get();
        $data['tech_reserve'] = $data['report']->tech()->status('reserve')->get();
        $data['tech_repair'] = $data['report']->tech()->status('repair')->get();
        $data['tech_helicopters_active'] = $data['report']
            ->tech()
            ->status('action')
            ->get();

        return view('formation.air-rescue.show', $data);
    }

    public function getAirRescueCreate()
    {
        $data['staff'] = Staff::all();
        $data['tech'] = Aircraft::with(['aircraft_type'])->get();

        return view('formation.air-rescue.add-edit', $data);
    }

    public function getAirRescueEdit(Request $request, $id)
    {
        $data['staff'] = Staff::all();
        $data['tech'] = Aircraft::with(['aircraft_type'])->get();
        $data['model'] = AirRescueReport::find($id);

        return view('formation.air-rescue.add-edit', $data);
    }

    public function getAirRescueEditCreate(Request $request)
    {
        $id = $request->id;

        $report = AirRescueReport::firstOrNew(['id' => $id]);
        $report->jet_fuel_action = $request->jet_fuel_action;
        $report->jet_fuel_reserved = $request->jet_fuel_reserved;
        $report->radio_stations = $request->radio_stations;
        $report->personal_respiratory_protection = $request->personal_respiratory_protection;
        $report->personal_protection = $request->personal_protection;
        $report->other_protection = $request->other_protection;
        $report->staff_total = $request->staff_total;
        $report->staff_action = $request->staff_action;
        $report->staff_duty_shift = $request->staff_duty_shift;
        $report->staff_head = $request->staff_head;

        $report->save();

        $f = $request->all();

        if($request->tech){
            AirRescueReportTechItem::where('report_id', $report->id)
                ->delete();
            $f = $request->all();
            foreach ($request->tech as $type => $inputs) {
                foreach ($inputs as $input_key => $input) {
                    if($input_key != 'aircraft_id'){
                        continue;
                    }

                    foreach ($input as $input_index => $id) {
                        $date_from = ($inputs['date_from'][$input_key] ?? null) ? Carbon::parse($inputs['date_from'][$input_key]) : null;
                        $date_to = ($inputs['date_to'][$input_key] ?? null) ? Carbon::parse($inputs['date_to'][$input_key]) : null;

                        $simplex = $inputs['simplex'][$input_index] ?? 0;
                        $vsu3 = $inputs['vsu3'][$input_index] ?? 0;
                        $vsu5 = $inputs['vsu5'][$input_index] ?? 0;
                        $vsu10 = $inputs['vsu10'][$input_index] ?? 0;
                        $winch = $inputs['winch'][$input_index] ?? 0;
                        $sur = $inputs['sur'][$input_index] ?? 0;
                        $external_suspension = $inputs['external_suspension'][$input_index] ?? 0;

                        if($id){
                            AirRescueReportTechItem::create([
                                'aircraft_id' => $id,
                                'report_id' => $report->id,
                                'department' => 1,
                                'status' => $type,
                                'reserve' => $inputs['reserve'][$input_key] ?? null,
                                'comment' => $inputs['comment'][$input_key] ?? null,
                                'date_from' => $date_from,
                                'date_to' => $date_to,
                                'simplex' => $simplex,
                                'vsu3' => $vsu3,
                                'vsu5' => $vsu5,
                                'vsu10' => $vsu10,
                                'winch' => $winch,
                                'sur' => $sur,
                                'external_suspension' => $external_suspension,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect('/formation/air-rescue')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }

    public function getAddToday()
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);
        $today = date('Y-m-d');
        $this
            ->set('today', $today)
            ->set('has_today', (new FormationReport)->where('report_date', $today)->count() > 0)
            ->set('report', (new FormationReport)->where('report_date', $today)->get());

    }

    public function postAddToday(Request $request)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $tomorrow = date('Y-m-d', strtotime('tomorrow'));

        $has_today = ((new FormationReport)->where('report_date', $tomorrow)->count() > 0);
        if (!$has_today) {
            (new FormationReport)
                ->fill(['report_date' => $tomorrow])
                ->save();
        }

        return redirect('/formation/101');
    }

    public function getAdd101Persons(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $read_only = Auth::user()->hasRight(Right::CAN_READ_ONLY_FORMATION) && !Auth::user()->isAdmin();

        $belongsToDept = Auth::user()->fire_department_id;

        if($belongsToDept){
            $departments = FireDepartment::where('id', $belongsToDept)->get();
        } else {
            $departments = FireDepartment::where('id', '<>', 19)->get();
        }

        $fieldlist = [
            'ГДЗС',
        ];
        $this->set('fieldlist', $fieldlist);

        $staff = Staff::where('department_id', $dept_id)->orderBy('name')->get();
        $guardNumbers = GuardNumber::all();

        $this->set('staff', $staff);

        $model = (new FormationPersonsReport())->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationPersonsReport();
        }

        $od_staff = $model->getODStaff();

        $this->set('model', $model);

        if($read_only){
            $staff_table['head_guards'] = $model->formation_person_items()->where('rank', 'head_guards')->get();
            $staff_table['commander_squads'] = $model->formation_person_items()->where('rank', 'commander_squads')->get();
            $staff_table['drivers'] = $model->formation_person_items()->where('rank', 'drivers')->get();
            $staff_table['privates'] = $model->formation_person_items()->where('rank', 'privates')->get();
            $staff_table['dispatchers'] = $model->formation_person_items()->where('rank', 'dispatchers')->get();
            $staff_table['vacation'] = $model->formation_person_items()->where('rank', 'vacation')->get();
            $staff_table['study'] = $model->formation_person_items()->where('rank', 'study')->get();
            $staff_table['maternity'] = $model->formation_person_items()->where('rank', 'maternity')->get();
            $staff_table['sick'] = $model->formation_person_items()->where('rank', 'sick')->get();
            $staff_table['business_trip'] = $model->formation_person_items()->where('rank', 'business_trip')->get();
            $staff_table['other'] = $model->formation_person_items()->where('rank', 'other')->get();
            $staff_table['total_active'] = $model->formation_person_items()->where('status', 'active')->count();
            $staff_table['total_inactive'] = $model->formation_person_items()->where('status', '!=','active')->count();
        }

        $this->set('departments', $departments)
            ->set('report', (new FormationReport)->find($form_id))
            ->set('form_id', $form_id)
            ->set('od_staff', $od_staff)
            ->set('staff_table', $staff_table ?? null)
            ->set('read_only', $read_only)
            ->set('guardNumbers', $guardNumbers)
            ->set('dept_id', $dept_id);
    }

    public function postAdd101Persons(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $formationReport = FormationReport::find($form_id);

        //todo: временно отключено
        /*$canEditReport = $formationReport->canEditReport();
        if(!$canEditReport && Auth::id() != 1){
            return redirect('/formation/101')->with('_message', ['type' => 'danger', 'text' => 'Отчет может быть сохранен только в период 18:00-19:00, 08:00-09:00']);
        }*/

        $model = (new FormationPersonsReport())
            ->where('form_id', $form_id)
            ->where('dept_id', $dept_id)
            ->first();

        if ($model === null) {
            $model = new FormationPersonsReport();
        }

        $all = [
            'total' => $request->total,
            'form_id' => $form_id,
            'dept_id' => $dept_id,
            'active' => $request->total_active,
            'head_guards' => count($request->input('staff.head_guards.staff_id', [])),
            'trainee' => count($request->input('staff.trainee.staff_id', [])),
            'commander_squads' => count($request->input('staff.commander_squads.staff_id', [])),
            'drivers' => count($request->input('staff.drivers.staff_id', [])),
            'privates' => count($request->input('staff.privates.staff_id', [])),
            'dispatchers' => count($request->input('staff.dispatchers.staff_id', [])),
            'vacation' => count($request->input('staff.vacation.staff_id', [])),
            'study' => count($request->input('staff.study.staff_id', [])),
            'maternity' => count($request->input('staff.maternity.staff_id', [])),
            'sick' => count($request->input('staff.sick.staff_id', [])),
            'business_trip' => count($request->input('staff.business_trip.staff_id', [])),
            'other' => count($request->input('staff.other.staff_id', [])),
            'gas_smoke_protection_service' => $request->gas_smoke_protection_service,
        ];
        $model->fill($all)->save();

        $f = $request->all();

        if($request->staff){
            FormationOdPersonItem::where('report_id', $model->id)
                ->delete();
            FormationPersonsItem::where('report_id', $model->id)
                ->delete();

            if($model->fireDepartment->title == 'ОД'){

                foreach ($request->staff as $type => $inputs) {
                    foreach ($inputs['staff_id'] as $input_key => $input) {

                        if(!in_array($type, ['vacation', 'study', 'maternity', 'sick', 'business_trip', 'other', 'trainee'])){
                            $data['status'] = 'active';
                        }
                        else{
                            $data['status'] = 'inactive';
                        }

                        $date_from = ($inputs['date_from'][$input_key] ?? null) ? Carbon::parse($inputs['date_from'][$input_key]) : null;
                        $date_to = ($inputs['date_to'][$input_key] ?? null) ? Carbon::parse($inputs['date_to'][$input_key]) : null;

                        FormationOdPersonItem::create([
                            'staff_id' => $input,
                            'report_id' => $model->id,
                            'comment' => $inputs['comment'][$input_key] ?? null,
                            'date_from' => $date_from,
                            'date_to' => $date_to,
                            'rank' => $type,
                            'table_name' => $type,
                            'status' => $data['status'],
                            'trainee_type' => $inputs['trainee_type'][$input_key] ?? null,
                        ]);
                    }
                }
            }
            else{

                foreach ($request->staff as $type => $inputs) {
                    foreach ($inputs['staff_id'] as $input_key => $input) {

                        if(!in_array($type, ['vacation', 'study', 'maternity', 'sick', 'sick_leave', 'business_trip', 'other', 'trainee'])){
                            $data['status'] = 'active';
                        }
                        else{
                            $data['status'] = 'inactive';
                        }

                        $date_from = ($inputs['date_from'][$input_key] ?? null) ? Carbon::parse($inputs['date_from'][$input_key]) : null;
                        $date_to = ($inputs['date_to'][$input_key] ?? null) ? Carbon::parse($inputs['date_to'][$input_key]) : null;

                        $f = $inputs;

                        FormationPersonsItem::create([
                            'staff_id' => $input,
                            'report_id' => $model->id,
                            'comment' => $inputs['comment'][$input_key] ?? null,
                            'date_from' => $date_from,
                            'date_to' => $date_to,
                            'rank' => $type,
                            'status' => $data['status'],
                            'guard_number_id' => $inputs['guard_number_id'][$input_key] ?? null,
                            'trainee_type' => $inputs['trainee_type'][$input_key] ?? null,
                        ]);
                    }
                }
            }
        }
        return redirect('/formation/101')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }

    public function getAdd101Tech(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);
        $read_only = Auth::user()->hasRight(Right::CAN_READ_ONLY_FORMATION) && !Auth::user()->isAdmin();

        $belongsToDept = Auth::user()->fire_department_id;
        if($belongsToDept){
            $departments = FireDepartment::where('id', $belongsToDept)->get();
        } else {
            $departments = FireDepartment::where('id', '!=', 19)->get();
        }

        $model = (new FormationTechReport)->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationTechReport();
        }
        $this->set('model', $model);

        if($read_only){
            $tech_table['action'] = $model->formation_tech_items()->where('status', 'action')->get();
            $tech_table['reserve'] = $model->formation_tech_items()->where('status', 'reserve')->get();
            $tech_table['repair'] = $model->formation_tech_items()->where('status', 'repair')->get();
        }

        $staff = Staff::where('department_id', $dept_id)->get();

        $this->set('departments', $departments)
            ->set('report', (new FormationReport)->find($form_id))
            ->set('form_id', $form_id)
            ->set('read_only', $read_only)
            ->set('tech_table', $tech_table ?? null)
            ->set('staff', $staff)
            ->set('vehicles', Vehicle::with(['vehicleType', 'fireDepartment'])->where('fire_department_id',$dept_id)->get())
            ->set('dept_id', $dept_id);

    }

    public function postAdd101Tech(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $all = $request->all();
        $except = $request->except('tech');
        $formationReport = FormationReport::find($form_id);
        $canEditReport = $formationReport->canEditReport();

        if($formationReport->is_approved){
            $this->needRight(Right::CAN_APPROVE_FORMATION_REPORT_101);
        }

        /*if(!$canEditReport && Auth::id() != 1){
            return redirect('/formation/101')->with('_message', ['type' => 'danger', 'text' => 'Отчет может быть сохранен только в период 18:00-19:00, 08:00-09:00']);
        }*/

        $model = FormationTechReport::updateOrCreate([
                'form_id' => $form_id,
                'dept_id' => $dept_id,
            ], $except);

        if($request->tech){
            FormationTechItem::where('formation_tech_report_id', $model->id)
                ->delete();
            foreach ($request->tech as $type => $inputs) {
                foreach ($inputs['vehicle_id'] as $input_key => $input) {
                    $date_from = ($inputs['date_from'][$input_key] ?? null) ? Carbon::parse($inputs['date_from'][$input_key]) : null;
                    $date_to = ($inputs['date_to'][$input_key] ?? null) ? Carbon::parse($inputs['date_to'][$input_key]) : null;
                    FormationTechItem::create([
                        'vehicle_id' => $input,
                        'formation_tech_report_id' => $model->id,
                        'department' => ($type != 'repair' && $type != 'reserve') ? $inputs['department'][$input_key] : null,
                        'status' => $type,
                        'reserve' => $inputs['reserve'][$input_key] ?? null,
                        'comment' => $inputs['comment'][$input_key] ?? null,
                        'date_from' => $date_from,
                        'date_to' => $date_to,
                    ]);
                }

            }
        }
        return redirect('/formation/101')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }

    public function getView101(Request $request, $form_id, FormationService $formationService)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $tech_items_count = [
            'tech_action_type_1' => 0,
            'tech_action_type_2' => 0,
            'tech_reserve_type_1' => 0,
            'tech_reserve_type_2' => 0,
            'tech_repair_type_1' => 0,
            'tech_repair_type_2' => 0,
        ];

        $report = (new FormationReport)->find($form_id);

        $latest = FormationReport::latest()->first();
        $searchDate = $searchDate = ($latest && $latest->id === $report->id) ? now() : Carbon::parse($report->created_at)->addHours(6);
        $operGroupSchedule = OperationalGroupSchedule::date($searchDate)->first();
        $operGroup = $operGroupSchedule ? $operGroupSchedule->group->name : '';

        $dept13_people = [];
        $dept_od_people = [];

        $people_fieldlist = [
            'В карауле по списку л/с',
            'На лицо личного состава' => [
                'Всего',
                'Нач. караулов',
                'Ком. отделений',
                'Водители',
                'Ряд. состав',
                'Диспетчеров',
            ],
            'Отсутствуют' => [
                'Отпуск',
                'Учебный',
                'Декрет',
                'Больные',
                'Командировка',
                'Другие причины',
            ],
            'ГДЗС'
        ];

        $tech_fieldlist = [
//            null,
            'Аппараты',
            'Мотопомпы' => [
                'Водяная',
                'Грязевая',
            ],
            'Пожарная техника' => [
                'В боевом расчете' => [
                    'Тип основного пожарного а/м',
                    'Марка спец. пожарных а/м, мотоциклов'
                ],
                'В резерве' => [
                    'Тип основного пожарного а/м',
                    'Марка спец. пожарных а/м, мотоциклов'
                ],
                'На ремонте' => [
                    'Тип основного пожарного а/м',
                    'Марка спец. пожарных а/м, мотоциклов'
                ],
            ],
            'Имеется на автомобилях в боевом расчете' => [
                'Рукавов' => [
                    '125 мм',
                    '75 мм',
                    '77 мм',
                    '51 мм',
                ],
                'Лафетн. Ств. стац',
                'Лафетн. Ств. переносной',
                'ГПС-600',
                'Пурга',
                'Переносная радиосатнция',
                'Электрофонари',
                'Прожектора',
                'ТОК',
                'Л-1',
                'Ранцевые аппараты',
                'Лопаты',
                'Хлопушки',
                'Спасательные  веревки',
                'Пенообразователя',
            ],
            'Пенообразователя на складе',
            'количество неисправных водоисточников' => [
                'Пожарные гидранты' => [
                    'Уличный',
                    'Объектовый',
                ],
                'ПВ',
            ],
            'В боевом расчете' => [
                'Бензин',
                'Дизель'
            ],
            'В резерве' => [
                'Бензин',
                'Дизель'
            ],
            'генератор',
            'дымосос',
            'гирсы',
            'ИУП',
            'Ф.И.О Начальника караула или лица его подменяющего'
        ];
        $people_fields = [
            'total',
            'active',
            'head_guards',
            'commander_squads',
            'drivers',
            'privates',
            'dispatchers',
            'vacation',
            'study',
            'maternity',
            'sick',
            'business_trip',
            'other',
            'gas_smoke_protection_service',
//            'sick_leave',
        ];
        $tech_fields = [
//            null,
            'device',
            'motor_water_pump',
            'motor_mud_pump',
        ];
        $tech_fields2 = [
            'firehose_125',
            'firehose_75',
            'firehose_77',
            'firehose_51',
            'barrel_stationary',
            'barrel_portable',
            'pgs_600',
            'purga',
            'radio_station_portable',
            'flashlight',
            'searchlight',
            'tok',
            'l1',
            'knapsack_devices',
            'shovel',
            'flapper',
            'life_rope',
            'foamer',
            'foamer_in_stock',
            'damaged_hydrant_street',
            'damaged_hydrant_object',
            'damaged_pv',
            'active_gasoline',
            'active_diesel',
            'reserved_gasoline',
            'reserved_diesel',
            'generator',
            'exhauster',
            'girs',
            'iup',
//            'head_guard_id',
        ];

        $excludedIds = $formationService->getExcludedDepartments()->pluck('id');

        $departments = new FireDepartment();
        $departments = $departments->whereNotIn('title', ['ОД']);
        if (auth()->user()->fire_department_id){
            $departments = $departments->where('id', '=', auth()->user()->fire_department_id);
        }
        $departments = $departments->get();

        $this->set('departments', $departments);
        $this->set('excluded_departments', FireDepartment::whereIn('id', $excludedIds)->get());

        $this->set('report', $report);

        $tech = (new FormationTechReport)->with('formation_tech_items')->where('form_id', $form_id)->get()->keyBy('dept_id');
        $people = (new FormationPersonsReport)->with('formation_person_items')->where('form_id', $form_id)->get()->keyBy('dept_id');

        $dispatchers = FormationPersonsItem::byRankAndForm('dispatchers', $form_id)->get()->sortBy(function ($q){
            return $q->staff->department_id;
        });
        $vacation = FormationPersonsItem::byRankAndForm('vacation', $form_id)->get()->sortBy(function ($q){
            return $q->staff->department_id;
        });
        $sick = FormationPersonsItem::byRankAndForm('sick', $form_id)->get()->sortBy('staff_id')->sortBy(function ($q){
            return $q->staff->department_id;
        });
        $sick_leave = FormationPersonsItem::byRankAndForm('sick_leave', $form_id)->get()->sortBy('staff_id')->sortBy(function ($q){
            return $q->staff->department_id;
        });
        $business_trip = FormationPersonsItem::byRankAndForm('business_trip', $form_id)->get()->sortBy(function ($q){
            return $q->staff->department_id;
        });

        $inactive_tech = $rawPeople = FormationTechItem::whereHas('formation_tech_report', function ($q) use ($form_id){
            $q->where('form_id', $form_id);
        })->where('status', 'repair')
            ->get();

        $inactive_tech_cnt = [];
        foreach ($inactive_tech as $inactive_tech_item){
            if(in_array($inactive_tech_item->vehicle->name, $inactive_tech_cnt)){
                $inactive_tech_cnt[$inactive_tech_item->vehicle->name] = ++$inactive_tech_cnt[$inactive_tech_item->vehicle->name];
            }
            else{
                $inactive_tech_cnt[$inactive_tech_item->vehicle->name] = 1;
            }
        }

        $formationCard101Others = Ticket101Other::whereHas('ride_type', function ($q) use ($report){
            $q->where('name', 'Расстановка');
        })
            ->whereDate('created_at', $report->report_date)
            ->get();

        // лс ПЧ-13
        if(isset($people[13])){
            foreach ($people[13]->formation_person_items as $item) {
                if($item->status == 'active'){
                    $dept13_people[$item->rank][] = $item->staff;
                }
            }
        }

        // лс ОД
        if(isset($people[19])){
            foreach ($people[19]->formation_person_items_od as $item) {
                if($item->status == 'active'){
                    $dept_od_people[$item->rank][] = $item->staff();
                }
            }
        }

        foreach ($departments as $key => $dep) {
            if(isset($tech[$dep->id]) && $tech[$dep->id]->formation_tech_items->count()){
                $departments[$key]->tech_action = $tech[$dep->id]->formation_tech_items()->status('action')->with('vehicle')->get();
                $departments[$key]->tech_reserve = $tech[$dep->id]->formation_tech_items()->status('reserve')->with('vehicle')->get();
                $departments[$key]->tech_repair = $tech[$dep->id]->formation_tech_items()->status('repair')->with('vehicle')->get();
            }
            else{
                $departments[$key]->tech_action = [];
                $departments[$key]->tech_reserve = [];
                $departments[$key]->tech_repair = [];
            }
        }

        $ttl_count = [];
        $tech_fields_temp[] = 'firehose_125';
        $tech_fields_temp[] = 'firehose_75';
        $tech_fields_temp[] = 'firehose_77';
        $tech_fields_temp[] = 'firehose_51';
        $tech_fields_temp[] = 'barrel_stationary';
        $tech_fields_temp[] = 'barrel_portable';
        $tech_fields_temp[] = 'pgs_600';
        $tech_fields_temp[] = 'purga';
        $tech_fields_temp[] = 'radio_station_portable';
        $tech_fields_temp[] = 'flashlight';
        $tech_fields_temp[] = 'searchlight';
        $tech_fields_temp[] = 'tok';
        $tech_fields_temp[] = 'l1';
        $tech_fields_temp[] = 'knapsack_devices';
        $tech_fields_temp[] = 'shovel';
        $tech_fields_temp[] = 'flapper';
        $tech_fields_temp[] = 'life_rope';
        $tech_fields_temp[] = 'foamer';
        $tech_fields_temp[] = 'foamer_in_stock';
        $tech_fields_temp[] = 'damaged_hydrant_street';
        $tech_fields_temp[] = 'damaged_hydrant_object';
        $tech_fields_temp[] = 'damaged_pv';
        $tech_fields_temp[] = 'active_gasoline';
        $tech_fields_temp[] = 'active_diesel';
        $tech_fields_temp[] = 'reserved_gasoline';
        $tech_fields_temp[] = 'reserved_diesel';
        $tech_fields_temp[] = 'generator';
        $tech_fields_temp[] = 'exhauster';
        $tech_fields_temp[] = 'girs';
        $tech_fields_temp[] = 'iup';
        $tech_fields_temp[] = 'head_guard_id';

        foreach ($departments as $dep) {
            if(isset($tech[$dep->id]) && $tech[$dep->id]->formation_tech_items->count()) {
                $tech_items_count['tech_action_type_1'] += count($tech[$dep->id]->formation_tech_items()->status('action')->whereHas('vehicle', function ($query) {
                    $query->where('vehicle_type_id', '=', 1);
                })->get());
                $tech_items_count['tech_action_type_2'] += count($tech[$dep->id]->formation_tech_items()->status('action')->whereHas('vehicle', function ($query) {
                    $query->where('vehicle_type_id', '=', 2);
                })->get());
                $tech_items_count['tech_reserve_type_1'] += count($tech[$dep->id]->formation_tech_items()->status('reserve')->whereHas('vehicle', function ($query) {
                    $query->where('vehicle_type_id', '=', 1);
                })->get());
                $tech_items_count['tech_reserve_type_2'] += count($tech[$dep->id]->formation_tech_items()->status('reserve')->whereHas('vehicle', function ($query) {
                    $query->where('vehicle_type_id', '=', 2);
                })->get());
                $tech_items_count['tech_repair_type_1'] += count($tech[$dep->id]->formation_tech_items()->status('repair')->whereHas('vehicle', function ($query) {
                    $query->where('vehicle_type_id', '=', 1);
                })->get());
                $tech_items_count['tech_repair_type_2'] += count($tech[$dep->id]->formation_tech_items()->status('repair')->whereHas('vehicle', function ($query) {
                    $query->where('vehicle_type_id', '=', 2);
                })->get());
            }


            if(isset($tech[$dep->id])){
                foreach ($tech_fields_temp as $item) {
                    if($item != null){
                        if(!isset($ttl_count[$item])){
                            $ttl_count[$item] = 0;
                        }

                        if($item != 'head_guard_id'){
                            $ttl_count[$item] += (float)$tech[$dep->id]->$item ?? 0;
                        }
                        else{
                            $ttl_count[$item]++;
                        }
                    }
                }
            }
        }

        $sumArray = $formationService->getSumArrayByDepartmentsArray($departments->where('id', '!=', 13), $people_fields, $tech_fields, $people, $tech);
        $user = Auth::user();

        $dataToReport = [
            'people' => $people,
            'tech' => $tech,
            'people_fields' => $people_fields,
            'tech_fields' => $tech_fields,
            'tech_fields2' => $tech_fields2,
            'people_fl' => $people_fieldlist,
            'tech_fl' => $tech_fieldlist,
            'tech_items_count' => $tech_items_count,
            'ttl_count' => $ttl_count,
            'sumArray' => $sumArray,
            'departments' => $departments,
            'excluded_departments' => FireDepartment::whereIn('id', $excludedIds)->get(),
            'report' => $report,
            'inactive_tech_cnt' => $inactive_tech_cnt,
            'inactive_tech' => $inactive_tech,
            'formationCard101Others' => $formationCard101Others,
        ];

        Cache::put('report101_data', $dataToReport, 3600);

        $this->set('people', $people)
            ->set('form_id', $form_id)
            ->set('od_staff', (new FormationPersonsReport())->getODStaff())
            ->set('tech', $tech)
            ->set('dept13_people', $dept13_people)
            ->set('dept_od_people', $dept_od_people)
            ->set('user', $user)
            ->set('operGroup', $operGroup)
            ->set('people_fields', $people_fields)
            ->set('tech_fields', $tech_fields)
            ->set('tech_fields2', $tech_fields2)
            ->set('people_fl', $people_fieldlist)
            ->set('tech_fl', $tech_fieldlist)
            ->set('tech_items_count', $tech_items_count)
            ->set('ttl_count', $ttl_count)
            ->set('dispatchers', $dispatchers)
            ->set('formationCard101Others', $formationCard101Others)
            ->set('report', $report)
            ->set('vacation', $vacation)
            ->set('sick', $sick)
            ->set('sick_leave', $sick_leave)
            ->set('guard_numbers', GuardNumber::all())
            ->set('business_trip', $business_trip)
            ->set('inactive_tech', $inactive_tech)
            ->set('inactive_tech_cnt', $inactive_tech_cnt)
            ->set('canEditOd', Auth::user()->hasRight('CAN_EDIT_OD_FORMATION'))
            ->set('sumArray', $sumArray);
    }

    public function getServicesList(Request $request)
    {
        $formation_tech_reports = FormationTechReport::todayRecords()->count();
        $formation_person_reports = FormationPersonsReport::todayRecords()->count();
        $formation_medical_reports = FormationMedicalReport::todayRecords()->count();
        $formation_mudflow_reports = FormationMudflowReport::todayRecords()->count();
        $formation_savers_reports = FormationSaversReport::todayRecords()->count();

        $medical_filled = FormationRecord::todayRecord('medical')->filled()->count();
        $mudflow_filled = FormationRecord::todayRecord('mudflow')->filled()->count();
        $savers_filled = FormationRecord::todayRecord('roso')->filled()->count();
        $air_filled = FormationRecord::todayRecord('air_rescue')->filled()->count();
        $ort_sert_filled = FormationRecord::todayRecord('ort_sert')->filled()->count();
        $emergency_filled = FormationRecord::todayRecord('emergency_almaty')->filled()->count();

        $this->set('formation_tech_reports', $formation_tech_reports);
        $this->set('formation_person_reports', $formation_person_reports);
        $this->set('formation_medical_reports', $formation_medical_reports);
        $this->set('formation_mudflow_reports', $formation_mudflow_reports);
        $this->set('formation_savers_reports', $formation_savers_reports);

        $this->set('medical_filled', $medical_filled);
        $this->set('mudflow_filled', $mudflow_filled);
        $this->set('savers_filled', $savers_filled);
        $this->set('air_filled', $air_filled);
        $this->set('ort_sert_filled', $ort_sert_filled);
        $this->set('emergency_filled', $emergency_filled);
    }

    public function getMudflow(Request $request)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION);
        $perPage = $request->get('per_page', 10);
        $today = Carbon::today();
        $has_today = ((new FormationMudflowReport())->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationMudflowReport())
                ->fill(['report_date' => $today])
                ->save();
        }
        $this
            ->set('per_page', $perPage)
            ->set('reports', FormationMudflowReport::orderBy('created_at', 'desc')->paginate($perPage))
            ->set('today', $today);
    }

    public function getEditMudflow(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION);

        $this->set('report', FormationMudflowReport::findOrFail($id));
    }

    public function postEditMudflow(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION);

        $report = FormationMudflowReport::findOrFail($id);
        $report->fill($request->all())->saveOrFail();
        return redirect('/formation/mudflow')->with('_message', [
            'type' => 'success',
            'text' => 'Строевая записка сохранена успешно'
        ]);
    }

    public function getMedical(Request $request)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_CMK);
        $perPage = $request->get('per_page', 10);
        $today = Carbon::today();
        $has_today = ((new FormationMedicalReport())->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationMedicalReport())
                ->fill(['report_date' => $today])
                ->save();
        }
        $this->set('reports', FormationMedicalReport::orderBy('created_at', 'desc')->paginate($perPage))
            ->set('today', $today)
            ->set('per_page', $perPage);
    }

    public function getEditMedical(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_CMK);

        $this->set('report', FormationMedicalReport::findOrFail($id));
    }

    public function postEditMedical(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_CMK);

        $report = FormationMedicalReport::findOrFail($id);
        $report->fill($request->all())->saveOrFail();
        return redirect('/formation/medical')->with('_message', [
            'type' => 'success',
            'text' => 'Строевая записка сохранена успешно'
        ]);
    }

    public function getSavers(Request $request)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);
        $perPage = $request->get('per_page', 10);
        $today = Carbon::today();
        $has_today = ((new FormationSaversReport())->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationSaversReport())
                ->fill(['report_date' => $today])
                ->save();
        }
        $this->set('reports', FormationSaversReport::orderBy('created_at')->paginate($perPage))
            ->set('today', $today)
            ->set('per_page', $perPage)
        ;
    }

    public function getEditSavers(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $this->set('report', FormationSaversReport::findOrFail($id));
    }

    public function postEditSavers(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = FormationSaversReport::findOrFail($id);
        $report->fill($request->all())->saveOrFail();
        return redirect('/formation/savers')->with('_message', [
            'type' => 'success',
            'text' => 'Строевая записка сохранена успешно'
        ]);
    }

    public function getSaversOperationsList(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = FormationSaversReport::with('operations')->findOrFail($id);
        $operations = $report->operations;
        $this->set('parent', $report)
            ->set('reports', $operations);
    }

    public function getSaversOperation(Request $request, $parent_id, $id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = Operations::findOrNew($id);
        $this->set('report', $report);
    }

    public function postSaversOperation(Request $request, $parent_id, $id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = Operations::findOrNew($id);
        $report->fill($request->all());
        /** @var FormationSaversReport $parent */
        $parent = FormationSaversReport::findOrFail($parent_id);
        $parent->operations()->save($report);
        return redirect('/formation/savers/events/' . $parent_id)->with('_message', [
            'type' => 'success',
            'text' => "Успешно сохранено",
        ]);
    }

    public function getSaversMigrationsList(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = FormationSaversReport::with('migrations')->findOrFail($id);
        $operations = $report->migrations;
        $this->set('parent', $report)
            ->set('reports', $operations);
    }

    public function getSaversMigration(Request $request, $parent_id, $id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = Migrations::findOrNew($id);
        $this->set('report', $report);
    }

    public function postSaversMigration(Request $request, $parent_id, $id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = Migrations::findOrNew($id);
        $report->fill($request->all());
        /** @var FormationSaversReport $parent */
        $parent = FormationSaversReport::findOrFail($parent_id);
        $parent->migrations()->save($report);
        return redirect('/formation/savers/migrations/' . $parent_id)->with('_message', [
            'type' => 'success',
            'text' => "Успешно сохранено",
        ]);
    }

    public function getSaversResourcesList(Request $request, $id)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = FormationSaversReport::with('resources')->findOrFail($id);
        $operations = $report->resources;
        $this->set('parent', $report)
            ->set('reports', $operations);
    }

    public function getSaversResources(Request $request, $parent_id, $id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = Resources::findOrNew($id);
        $this->set('report', $report);
    }

    public function postSaversResources(Request $request, $parent_id, $id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);

        $report = Resources::findOrNew($id);
        $report->fill($request->all());
        /** @var FormationSaversReport $parent */
        $parent = FormationSaversReport::findOrFail($parent_id);
        $parent->resources()->save($report);
        return redirect('/formation/savers/resources/' . $parent_id)->with('_message', [
            'type' => 'success',
            'text' => "Успешно сохранено",
        ]);
    }

    public function postApproveReport101($id)
    {
        $this->needRight(Right::CAN_APPROVE_FORMATION_REPORT_101);

        $report = FormationReport::find($id);

        if($report->is_approved ?? Auth::id() == 1){
            $report->is_approved = false;
            $text = "Утверждение отменено";
        }
        else{
            $report->is_approved = true;
            $text = "Успешно утверждено";
        }
        $report->save();

        return back()->with('_message', [
            'type' => 'success',
            'text' => $text,
        ]);
    }

    public function approveAirRescue(Request $request, $id)
    {
        $formationRecord = AirRescueReport::find($id);

        if(Auth::user()->hasRight(['CAN_APPROVE_FORMATION_RECORD'])){

            $formationRecord->approved = true;
            $formationRecord->save();
        }
        else{
            $this->throwAccessDenied();
        }

        return back();
    }

}
