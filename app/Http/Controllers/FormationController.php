<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 11.07.2018
 * Time: 19:38
 */

namespace App\Http\Controllers;


use App\FireDepartment;
use App\Formation\Operations;
use App\FormationMedicalReport;
use App\FormationMudflowReport;
use App\FormationPersonsReport;
use App\FormationReport;
use App\FormationSaversReport;
use App\FormationTechReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormationController extends AuthorizedController
{
    public function get101(Request $request)
    {
        $today = date('Y-m-d');
        $has_today = ((new FormationReport)->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationReport)
                ->fill(['report_date' => $today])
                ->save();
        }
        $this
            ->set('today', $today)
            ->set('reports', (new FormationReport)->orderByDesc('report_date')->get());
    }

    public function getAddToday()
    {
        $today = date('Y-m-d');
        $this
            ->set('today', $today)
            ->set('has_today', (new FormationReport)->where('report_date', $today)->count() > 0)
            ->set('report', (new FormationReport)->where('report_date', $today)->get());

    }

    public function postAddToday(Request $request)
    {
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
        $fieldlist = [
            'В карауле по списку л/с',
            'На лицо личного состава' => [
                'Всего',
                'Нач. караулов',
                'Ком. отделений',
                'Шоферы',
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
        $model = (new FormationPersonsReport())->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationPersonsReport();
        }
        $model->fill($request->all())->save();
        return redirect('/formation/101')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }

    public function getAdd101Tech(Request $request, $form_id, $dept_id = 0)
    {
        $fieldlist = [
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
                'Солярка'
            ],
            'В резерве' => [
                'Бензин',
                'Солярка'
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
            ->set('dept_id', $dept_id);

    }

    public function postAdd101Tech(Request $request, $form_id, $dept_id = 0)
    {
        $model = (new FormationTechReport())->where('form_id', $form_id)->where('dept_id', $dept_id)->first();
        if ($model === null) {
            $model = new FormationTechReport();
        }
        $model->fill($request->all())->save();
        return redirect('/formation/101')->with('_message', ['type' => 'success', 'text' => 'Отчет успешно сохранен']);
    }


    public function getView101(Request $request, $form_id)
    {
        $people_fieldlist = [
            'В карауле по списку л/с',
            'На лицо личного состава' => [
                'Всего',
                'Нач. караулов',
                'Ком. отделений',
                'Шоферы',
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
                'Солярка'
            ],
            'В резерве' => [
                'Бензин',
                'Солярка'
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

        $this->set('departments', FireDepartment::all());
        $this->set('report', (new FormationReport)->find($form_id));
        $tech = (new FormationTechReport)->where('form_id', $form_id)->get()->keyBy('dept_id');
        $people = (new FormationPersonsReport)->where('form_id', $form_id)->get()->keyBy('dept_id');
        $this->set('people', $people)
            ->set('tech', $tech)
            ->set('people_fields', $people_fields)
            ->set('tech_fields', $tech_fields)
            ->set('people_fl', $people_fieldlist)
            ->set('tech_fl', $tech_fieldlist);
    }

    public function getServicesList(Request $request)
    {

    }

    public function getMudflow(Request $request)
    {
        $today = Carbon::today();
        $has_today = ((new FormationMudflowReport())->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationMudflowReport())
                ->fill(['report_date' => $today])
                ->save();
        }
        $this->set('reports', FormationMudflowReport::all())
            ->set('today', $today);
    }

    public function getEditMudflow(Request $request, $id)
    {
        $this->set('report', FormationMudflowReport::findOrFail($id));
    }

    public function postEditMudflow(Request $request, $id)
    {
        $report = FormationMudflowReport::findOrFail($id);
        $report->fill($request->all())->saveOrFail();
        return redirect('/formation/mudflow')->with('_message', [
            'type' => 'success',
            'text' => 'Строевая записка сохранена успешно'
        ]);
    }

    public function getMedical(Request $request)
    {
        $today = Carbon::today();
        $has_today = ((new FormationMedicalReport())->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationMedicalReport())
                ->fill(['report_date' => $today])
                ->save();
        }
        $this->set('reports', FormationMedicalReport::all())
            ->set('today', $today);
    }

    public function getEditMedical(Request $request, $id)
    {
        $this->set('report', FormationMedicalReport::findOrFail($id));
    }

    public function postEditMedical(Request $request, $id)
    {
        $report = FormationMedicalReport::findOrFail($id);
        $report->fill($request->all())->saveOrFail();
        return redirect('/formation/medical')->with('_message', [
            'type' => 'success',
            'text' => 'Строевая записка сохранена успешно'
        ]);
    }

    public function getSavers(Request $request)
    {
        $today = Carbon::today();
        $has_today = ((new FormationSaversReport())->where('report_date', $today)->count() > 0);
        if (!$has_today) {
            (new FormationSaversReport())
                ->fill(['report_date' => $today])
                ->save();
        }
        $this->set('reports', FormationSaversReport::all())
            ->set('today', $today);
    }

    public function getEditSavers(Request $request, $id)
    {
        $this->set('report', FormationSaversReport::findOrFail($id));
    }

    public function postEditSavers(Request $request, $id)
    {
        $report = FormationSaversReport::findOrFail($id);
        $report->fill($request->all())->saveOrFail();
        return redirect('/formation/savers')->with('_message', [
            'type' => 'success',
            'text' => 'Строевая записка сохранена успешно'
        ]);
    }

    public function getSaversOperationsList(Request $request, $id)
    {
        $report = FormationSaversReport::with('operations')->findOrFail($id);
        $operations = $report->operations;
        $this->set('parent', $report)
            ->set('reports', $operations);
    }

    public function getSaversOperation(Request $request, $parent_id, $id = 0)
    {
        $report = Operations::findOrNew($id);
        $this->set('report', $report);
    }

    public function postSaversOperation(Request $request, $parent_id, $id = 0)
    {
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

}