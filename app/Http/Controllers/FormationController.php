<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 11.07.2018
 * Time: 19:38
 */

namespace App\Http\Controllers;


use App\FormationReport;
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

    public function getAdd101Persons(Request $request, $form_id = 0)
    {

    }

    public function postAdd101Persons(Request $request, $form_id = 0)
    {

    }

    public function getViewFull(Request $request, $date = null)
    {

    }


}