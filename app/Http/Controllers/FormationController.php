<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 11.07.2018
 * Time: 19:38
 */

namespace App\Http\Controllers;


use App\FireDepartment;
use App\FormationPersonsReport;
use App\FormationReport;
use App\FormationTechReport;
use Illuminate\Http\Request;

class FormationController extends AuthorizedController
{
    public function get101(Request $request)
    {
        $today = date('Y-m-d');
        $this
            ->set('today', $today)
            ->set('has_today', (new FormationReport)->where('report_date', $today)->count() > 0)
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
        $this->set('departments', FireDepartment::all());

    }


}