<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Formation\Migrations;
use App\Formation\Operations;
use App\Formation\Resources;
use App\FormationMedicalReport;
use App\FormationMudflowReport;
use App\FormationPersonsReport;
use App\FormationReport;
use App\FormationSaversReport;
use App\FormationTechReport;
use App\Models\FormationPersonsItem;
use App\Models\FormationRecord;
use App\Models\FormationTechItem;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Reports\Report;
use App\Right;
use App\Services\FormationService;
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
        if (!$has_today) {
            (new FormationReport)
                ->fill(['report_date' => $today])
                ->save();
        }
        $this
            ->set('today', $today)
            ->set('per_page', $perpage)
            ->set('reports', (new FormationReport)->orderBy('report_date','desc')->paginate($perpage));
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

        $fieldlist = [
            'ГДЗС',
        ];
        $this->set('fieldlist', $fieldlist);

        $staff = Staff::where('department_id', $dept_id)->get();

        $this->set('staff', $staff);

        $model = (new FormationPersonsReport())->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationPersonsReport();
        }
        $this->set('model', $model);

        $this->set('departments', FireDepartment::all())
            ->set('report', (new FormationReport)->find($form_id))
            ->set('form_id', $form_id)
            ->set('dept_id', $dept_id);
    }

    public function postAdd101Persons(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $formationReport = FormationReport::find($form_id);
        $canEditReport = $formationReport->canEditReport();
        if(!$canEditReport && Auth::id() != 1){
            return redirect('/formation/101')->with('_message', ['type' => 'error', 'text' => 'Отчет может быть сохранен только в период 18:00-19:00, 08:00-09:00']);
        }

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
            'head_guards' => count($request->input('staff.head_guards', [])),
            'commander_squads' => count($request->input('staff.commander_squads', [])),
            'drivers' => count($request->input('staff.drivers', [])),
            'privates' => count($request->input('staff.privates', [])),
            'dispatchers' => count($request->input('staff.dispatchers', [])),
            'vacation' => count($request->input('staff.vacation', [])),
            'study' => count($request->input('staff.study', [])),
            'maternity' => count($request->input('staff.maternity', [])),
            'sick' => count($request->input('staff.sick', [])),
            'business_trip' => count($request->input('staff.business_trip', [])),
            'other' => count($request->input('staff.other', [])),
            'gas_smoke_protection_service' => $request->gas_smoke_protection_service,
        ];
        $model->fill($all)->save();

        foreach ($request->staff as $rank => $items) {
            if(!in_array($rank, ['vacation', 'study', 'maternity', 'sick', 'business_trip', 'other'])){
                $data['status'] = 'active';
            }
            else{
                $data['status'] = 'inactive';
            }
            $data['rank'] = $rank;


            foreach ($items as $item) {
                $data['staff_id'] = $item;
                $data['report_id'] = $model->id;

                $staffItem = FormationPersonsItem::updateOrCreate([
                    'staff_id' => $item
                ], $data);
            }
        }


        return redirect('/formation/101')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }

    public function getAdd101Tech(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $model = (new FormationTechReport)->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationTechReport();
        }
        $this->set('model', $model);

        $staff = Staff::where('department_id', $dept_id)->get();

        $this->set('departments', FireDepartment::all())
            ->set('report', (new FormationReport)->find($form_id))
            ->set('form_id', $form_id)
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

        if(!$canEditReport && Auth::id() != 1){
            return redirect('/formation/101')->with('_message', ['type' => 'error', 'text' => 'Отчет может быть сохранен только в период 18:00-19:00, 08:00-09:00']);
        }

        $model = FormationTechReport::updateOrCreate([
                'form_id' => $form_id,
                'dept_id' => $dept_id,
            ], $except);

        if($request->tech){
            FormationTechItem::where('formation_tech_report_id', $model->id)
                ->delete();
            foreach ($request->tech as $type => $inputs) {
                foreach ($inputs['vehicle_id'] as $input_key => $input) {
                    FormationTechItem::create([
                        'vehicle_id' => $input,
                        'formation_tech_report_id' => $model->id,
                        'department' => ($type != 'repair' && $type != 'reserve') ? $inputs['department'][$input_key] : null,
                        'status' => $type,
                        'reserve' => $inputs['reserve'][$input_key] ?? null,
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
            'tech_action' => 0,
            'tech_reserve' => 0,
            'tech_repair' => 0,
        ];

        $dept13_people = [];

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
            'gas_smoke_protection_service'
        ];
        $tech_fields = [
//            null,
            null,
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
//        $departments = FireDepartment::whereNotIn('id', $excludedIds)->get();
        $departments = FireDepartment::whereNotIn('title', ['ОД'])->get();

        $this->set('departments', $departments);
        $this->set('excluded_departments', FireDepartment::whereIn('id', $excludedIds)->get());

        $this->set('report', (new FormationReport)->find($form_id));
        $tech = (new FormationTechReport)->with('formation_tech_items')->where('form_id', $form_id)->get()->keyBy('dept_id');
        $people = (new FormationPersonsReport)->with('formation_person_items')->where('form_id', $form_id)->get()->keyBy('dept_id');

        // лс ПЧ-13
        if(isset($people[13])){
            foreach ($people[13]->formation_person_items as $item) {
                if($item->status == 'active'){
                    $dept13_people[$item->rank][] = $item->staff;
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
        $tech_fields_temp = $tech_fields2;
        $tech_fields_temp[] = 'head_guard_id';

        foreach ($departments as $dep) {
            $tech_items_count['tech_action'] += count($dep->tech_action);
            $tech_items_count['tech_reserve'] += count($dep->tech_reserve);
            $tech_items_count['tech_repair'] += count($dep->tech_repair);


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

        $sumArray = $formationService->getSumArrayByDepartmentsArray($departments, $people_fields, $tech_fields, $people, $tech);
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
            'report' => FormationReport::find($form_id),
        ];

        Cache::put('report101_data', $dataToReport, 3600);

//        $html = view('pdf/formation-report', $dataToReport);

//        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
//        $date = date('d-m-Y');
//        $file_name = "Суточный отчет - $date.pdf";

//        $dompdf = new Dompdf();
//        $dompdf->loadHTML($html, 'UTF-8');
//        $dompdf->setPaper('A4', 'landscape');
//        $dompdf->render();
//        $dompdf->stream($file_name);

//        $html2pdf = new Html2Pdf('L');
//        $html2pdf->writeHTML($html);
//        $html2pdf->output('fff.pdf');


        $this->set('people', $people)
            ->set('tech', $tech)
            ->set('dept13_people', $dept13_people)
            ->set('user', $user)
            ->set('people_fields', $people_fields)
            ->set('tech_fields', $tech_fields)
            ->set('tech_fields2', $tech_fields2)
            ->set('people_fl', $people_fieldlist)
            ->set('tech_fl', $tech_fieldlist)
            ->set('tech_items_count', $tech_items_count)
            ->set('ttl_count', $ttl_count)
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

}
