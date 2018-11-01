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
        if(in_array($data['rank'], $od_people)){
            $resp = FormationPersonsItem::rank($request->rank)
                ->where('report_id', $request->id)
                ->get();
        }
        else{
            $resp = FormationOdPersonItem::rank($request->rank)
                ->where('report_id', $request->id)
                ->get();
        }

        return response()->json($resp);
    }
}
