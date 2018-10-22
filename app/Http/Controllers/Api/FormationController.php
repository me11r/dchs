<?php

namespace App\Http\Controllers\Api;

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
            ->date($request->date)
            ->get();
        return response()->json($resp);
    }
}
