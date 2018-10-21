<?php

namespace App\Http\Controllers;


use App\Dictionary\Street;
use App\FireDepartment;
use App\Models\Building;
use App\Models\SpecialPlan;
use App\OperationalCard;
use App\Right;
use App\RoadtripPlan;
use App\RoadtripSubscription;
use App\Ticket101ServicePlan;
use Auth;
use Illuminate\Http\Request;

class AjaxController extends AuthorizedController
{
    public function findStreet(Request $request, $area_id = null)
    {
        $result = [];

        $streets = Street::with('area');
        if ($area_id !== null) {
            $streets = $streets->where('city_area_id', $area_id);
        }
        $txt = $request->get('q', '');
        $txt = str_replace('%', '', $txt);

        $result['building'] = Building::address($txt)
            ->with(['city_area', 'street', 'city_micro_area', 'object_type', 'wall_material'])
            ->first();

        $streets = $streets
            ->where('name', 'like', "%$txt%")
            ->limit(30);

        $streets = $streets->get();
        $fireDept = FireDepartment::where('city_area_id', $area_id)->first();
        $result = [
            'streets' => $streets,
            'fireDept' => $fireDept,
        ];
        return response()->json($result, 200, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function findSpecialPlan(Request $request)
    {
        $result = [];
        $location = $request->location;
        $specialPlans = (new SpecialPlan)
            ->where('location', 'like', "%$location%")
            ->limit(10)
            ->get();

        $operational_cards = OperationalCard::location($location)
            ->orWhere('object_name', 'like', "%$location%")
            ->limit(10)
            ->get();

        foreach ($operational_cards as $key => $operational_card) {
            $operational_card->is_card = true;
            $operational_cards[$key] = $operational_card;
        }

        $specialPlans = $specialPlans->merge($operational_cards);

        $home = trim(explode(',', $location)[1] ?? null);
        if($home){
            $location = str_replace([',', ' ', $home], '', $location);
        }

        $result['special_plans'] = $specialPlans;
        $result['schedule'] = $specialPlans;
        $result['building'] = Building::address($location, $home)
            ->with(['city_area', 'street', 'city_micro_area', 'object_type', 'wall_material'])
            ->first();

        return response()->json($result, 200, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function getRightIds(Request $request)
    {
        $user = Auth::user();
//        return response()->json($user->rights->keyBy('id'), 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
        return response()->json($user->role->rights->keyBy('id'), 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
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

    public function getServicePlans(Request $request)
    {
        $service = (Auth::user())->service_type;
        $canRecieve = Auth::user()->hasRight(Right::CAN_VIEW_112_CARD);
        if ($service === null || !$canRecieve) {
            return response()->json([], 200);
        }

        $trips = Ticket101ServicePlan::with(['ticket', 'results']);
        $trips = $trips
            ->where('is_closed', false)
//            ->where('department', $service->name)
            ->where('is_accepted', false)
            ->get();
        return response()->json($trips, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function postRoadtripNotificationToken(Request $request)
    {
        $this->noLayout();
        $user = Auth::user();
        $subscription = RoadtripSubscription::updateOrCreate(['user_id' => $user->id, 'token' => $request->get('token')]);
        $subscription->save();
        return response()->json($subscription);
    }
}
