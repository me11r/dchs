<?php

namespace App\Http\Controllers;


use App\Dictionary\Street;
use App\Models\SpecialPlan;
use App\RoadtripPlan;
use Auth;
use Illuminate\Http\Request;

class AjaxController extends AuthorizedController
{
    public function findStreet(Request $request, $area_id = null)
    {
        $streets = Street::with('area');
        if ($area_id !== null) {
            $streets = $streets->where('city_area_id', $area_id);
        }
        $txt = $request->get('q', '');
        $txt = str_replace('%', '', $txt);

        $streets = $streets
            ->where('name', 'like', $txt . '%')
            ->limit(30);

        $streets = $streets->get();
        return response()->json($streets, 200, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function findSpecialPlan(Request $request)
    {
        $specialPlan = SpecialPlan::where('location', '=', $request->get('location'))->first();
        return response()->json($specialPlan, $specialPlan ? 200 : 404, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function getRightIds(Request $request)
    {
        $user = Auth::user();
        return response()->json($user->rights->keyBy('id'), 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function getRoadtripPlans(Request $request)
    {
        $dept = (Auth::user())->department;
        if ($dept === null) {
            return response()->json([], 200);
        }

        $trips = RoadtripPlan::with(['department', 'ticket']);
        $trips = $trips
            ->where('is_closed', false)
            ->where('department_id', $dept->id)
            ->where('is_accepted', false)
            ->get();
        return response()->json($trips, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }
}
