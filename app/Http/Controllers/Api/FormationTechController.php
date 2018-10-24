<?php

namespace App\Http\Controllers\Api;

use App\Models\FormationTechItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormationTechController extends Controller
{
    public function delete()
    {

    }

    public function index(Request $request)
    {
        $data = $request->all();
        $resp = FormationTechItem::status($request->status)
            ->where('formation_tech_report_id', $request->id)
            ->get();
        return response()->json($resp);
    }

    public function rememberTech(Request $request)
    {
        $card_id = $request->card_id;
        $tech_id = $request->tech_id;


    }
}
