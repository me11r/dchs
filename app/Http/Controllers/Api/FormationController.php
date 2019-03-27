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
        $f = $request->all();
        $resp = OperDutyShiftStaffItem::rank($request->rank)
            ->wherehas('report', function ($q) use ($request) {
                $q->where('shift_id', $request->shift_id);
            })
            ->with(['staff'])
            ->date($request->date);

        if ($request->inactive) {
            $resp = $resp->whereNotNull('inactive_type');
        }
        else {
            $resp = $resp->whereNull('inactive_type');
        }

        $resp = $resp->get();

        return response()->json($resp);
    }

    public function staff_page_district_managers(Request $request)
    {
        $resp = FormationDistrictManagerItem::where('report_id',$request->report_id)
            ->where('city_area_id', $request->city_area_id)
            ->with(['manager']);

        if ($request->block_type) {
            $resp = $resp->whereNotNull('inactive_type');
        }
        else {
            $resp = $resp->whereNull('inactive_type');
        }

        $resp = $resp->get();

        return response()->json($resp);
    }
}
