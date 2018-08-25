<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 11.07.2018
 * Time: 19:38
 */

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
use App\Models\FormationTechItem;
use App\Models\Vehicle;
use App\Right;
use App\Services\FormationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $today = date('Y-m-d');
        $has_today = ((new FormationReport)->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationReport)
                ->fill(['report_date' => $today])
                ->save();
        }

        return redirect('/formation/101');
    }

    public function getAdd101Persons(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $fieldlist = [
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
        ];
        $this->set('fieldlist', $fieldlist);

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

        $model = (new FormationPersonsReport())->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationPersonsReport();
        }
        $model->fill($request->all())->save();
        return redirect('/formation/101')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }

    public function getAdd101Tech(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

        $fieldlist = [
            'ГДЗС',
            'Аппараты',
            'Мотопомпы' => [
                'Водяная',
                'Грязевая',
            ],
//            'Пожарная техника' => [
//                'В боевом расчете' => [
//                    'Тип основного пожарного а/м',
//                    'Марка спец. пожарных а/м, мотоциклов'
//                ],
//                'В резерве' => [
//                    'Тип основного пожарного а/м',
//                    'Марка спец. пожарных а/м, мотоциклов'
//                ],
//                'На ремонте' => [
//                    'Тип основного пожарного а/м',
//                    'Марка спец. пожарных а/м, мотоциклов'
//                ],
//            ],
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
                'ТОК/Л-1',
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
            'генератор/дымосос/гирсы,ИУП',
            'Ф.И.О Начальника караула или лица его подменяющего'
        ];
        $this->set('fieldlist', $fieldlist);
        $model = (new FormationTechReport)->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationTechReport();
        }
        $this->set('model', $model);

        $this->set('departments', FireDepartment::all())
            ->set('report', (new FormationReport)->find($form_id))
            ->set('form_id', $form_id)
            ->set('vehicles', Vehicle::with(['vehicleType', 'fireDepartment'])->get())
            ->set('dept_id', $dept_id);

    }

    public function postAdd101Tech(Request $request, $form_id, $dept_id = 0)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);
        $all = $request->all();
        $model = FormationTechReport::updateOrCreate([
                'form_id' => $form_id,
                'dept_id' => $dept_id,
            ], $all);
        if($request->tech){
            FormationTechItem::where('formation_tech_report_id', $model->id)
                ->delete();
            foreach ($request->tech as $type => $inputs) {
                foreach ($inputs['vehicle_id'] as $input_key => $input) {
                    FormationTechItem::create([
                        'vehicle_id' => $input,
                        'formation_tech_report_id' => $model->id,
                        'department' => $type != 'repair' ? $inputs['department'][$input_key] : null,
                        'status' => $type,
                    ]);
                }

            }
        }
        return redirect('/formation/101')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }

    public function getView101(Request $request, $form_id, FormationService $formationService)
    {
        $this->needRight(Right::CAN_ACCESS_FORMATION_REPORT_101);

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
        ];

        $tech_fieldlist = [
            'ГДЗС',
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
                'ТОК/Л-1',
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
            'генератор/дымосос/гирсы,ИУП',
            'Ф.И.О Начальника караула или лица его подменяющего'
        ];
        $people_fields = [
            'field_0',
            'field_2_0',
            'field_2_1',
            'field_2_2',
            'field_2_3',
            'field_2_4',
            'field_2_5',
            'field_3_0',
            'field_3_1',
            'field_3_2',
            'field_3_3',
            'field_3_4',
            'field_3_5'
        ];
        $tech_fields = [
            'field_0',
            'field_1',
            'field_3_0',
            'field_3_1',
            'field_4_1_0',
            'field_4_1_1',
            'field_4_2_0',
            'field_4_2_1',
            'field_4_3_0',
            'field_4_3_1',
            'field_5_1_0',
            'field_5_1_1',
            'field_5_1_2',
            'field_5_1_3',
            'field_5_0',
            'field_5_1',
            'field_5_2',
            'field_5_3',
            'field_5_4',
            'field_5_5',
            'field_5_6',
            'field_5_7',
            'field_5_8',
            'field_5_9',
            'field_5_10',
            'field_5_11',
            'field_5_12',
            'field_2',
            'field_7_1_0',
            'field_7_1_1',
            'field_7_0',
            'field_8_0',
            'field_8_1',
            'field_9_0',
            'field_9_1',
            'field_3',
            'field_4',
        ];

        $excludedIds = $formationService->getExcludedDepartments()->pluck('id');
        $departments = FireDepartment::whereNotIn('id', $excludedIds)->get();
        $this->set('departments', $departments);
        $this->set('excluded_departments', FireDepartment::whereIn('id', $excludedIds)->get());

        $this->set('report', (new FormationReport)->find($form_id));
        $tech = (new FormationTechReport)->where('form_id', $form_id)->get()->keyBy('dept_id');
        $people = (new FormationPersonsReport)->where('form_id', $form_id)->get()->keyBy('dept_id');

        $sumArray = $formationService->getSumArrayByDepartmentsArray($departments, $people_fields, $tech_fields, $people, $tech);

        $this->set('people', $people)
            ->set('tech', $tech)
            ->set('people_fields', $people_fields)
            ->set('tech_fields', $tech_fields)
            ->set('people_fl', $people_fieldlist)
            ->set('tech_fl', $tech_fieldlist)
            ->set('sumArray', $sumArray);
    }

    public function getServicesList(Request $request)
    {

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

}
