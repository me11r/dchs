<?php

namespace App\Http\Controllers\Api;

use App\FormationOdPersonItem;
use App\FormationPersonsReport;
use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormationStaffController extends Controller
{
    public function delete()
    {

    }

    public function staff_page(Request $request)
    {
        $data = $request->all();
        $od_people = (new FormationPersonsReport())->od_staff;
        if (in_array($data['rank'], $od_people)) {
            $resp = FormationOdPersonItem::with(['staff'])
                ->rank($request->rank)
                ->where('report_id', $request->id)
                ->get();
        } else {
            $resp = FormationPersonsItem::rank($request->rank)
                ->where('report_id', $request->id)
                ->get();


            /*foreach ($resp as $key => $item) {
                $item->staff;
            }*/
        }

        return response()->json($resp);
    }

    public function syncFormationOdPersons(Request $request)
    {
        $formId = $request->get('formId', []);
        $selectedStaff = $request->get('selectedStaff', []);
        $type = $request->get('type');
        $tableName = $request->get('tableName');

        $formationPersonsReport = FormationPersonsReport::where('form_id', '=', $formId)
            ->where('dept_id', '=', 19)
            ->first();

        if (!$formationPersonsReport) {
            $formationPersonsReport = FormationPersonsReport::create(['form_id' => $formId, 'dept_id' => 19]);
        }
        FormationOdPersonItem::where('report_id', $formationPersonsReport->id)
            ->where('rank', '=', $type)
            ->where('table_name', '=', $tableName)
            ->delete();

        foreach ($selectedStaff as $staff) {
            FormationOdPersonItem::create([
                'staff_id' => $staff['id'],
                'report_id' => $formationPersonsReport->id,
                'comment' => null,
                'date_from' => null,
                'date_to' => null,
                'rank' => $type,
                'table_name' => $tableName,
                'status' => 'inactive',
            ]);
        }

        return response()->json(['success' => true]);
    }
}
