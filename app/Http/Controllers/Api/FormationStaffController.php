<?php

namespace App\Http\Controllers\Api;

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
        $resp = FormationPersonsItem::rank($request->rank)
            ->where('report_id', $request->id)
            ->get();
        return response()->json($resp);
    }
}
