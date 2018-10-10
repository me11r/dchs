<?php

namespace App\Http\Controllers\Api;

use App\AirRescueReportPersonsItem;
use App\AirRescueReportTechItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AircraftController extends Controller
{
    public function getTech(Request $request)
    {
        $rank = $request->status;
        $report_id = $request->id;

        $resp = AirRescueReportTechItem::status($rank)
            ->where('report_id', $report_id)
            ->get();

        return response()->json($resp);

    }

    public function getStaff(Request $request)
    {
        $rank = $request->rank;
        $report_id = $request->id;

        $resp = AirRescueReportPersonsItem::status($rank)
            ->where('report_id', $report_id)
            ->get();

        return response()->json($resp);
    }

}
