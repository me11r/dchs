<?php

namespace App\Http\Controllers\Api;

use App\FormationDistrictManagerItem;
use App\OperDutyShiftStaffItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormationController extends Controller
{
    public function staff_page(Request $request)
    {
        $data = $request->all();
        $resp = OperDutyShiftStaffItem::rank($request->rank)
            ->where('shift_id', $request->shift_id)
            ->with(['staff'])
            ->date($request->date)
            ->get();
        return response()->json($resp);
    }

    public function staff_page_district_managers(Request $request)
    {
        $data = $request->all();
        $resp = FormationDistrictManagerItem::where('report_id',$request->report_id)
            ->where('city_area_id', $request->city_area_id)
            ->with(['manager'])
            ->get();
        return response()->json($resp);
    }
}
