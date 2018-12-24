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

        //экранируем спец. символы

        /*не используем elasticsearch на локалке*/
        if(env('IS_LOCAL', false)){
            $specialPlans = SpecialPlan::where('object_name', 'like', "%$location%")
                ->orWhere('location', 'like', "%$location%")->take(5)->get();
            $operational_cards = OperationalCard::where('object_name', 'like', "%$location%")
                ->orWhere('location', 'like', "%$location%")->take(5)->get();
        }
        else{
            //экранируем спец. символы
            $location = addcslashes($location, '"\\/');

            $specialPlansQuery = SpecialPlan::search($location);
            $operationalCardsQuery = OperationalCard::search($location);

            $specialPlansQuery->limit = 5;
            $specialPlans = $specialPlansQuery->get();

            $operationalCardsQuery->limit = 5;
            $operational_cards = $operationalCardsQuery->get();
        }


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
        $rightsArr = [];
        foreach ($user->role->rights as $right){
            $rightsArr[] = $right->id;
            $rightsArr[] = $right->name;
        }
//        return response()->json($user->role->rights->keyBy('id'), 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
        return response()->json($rightsArr, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
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
            ->has('ticket')
            ->get();
        return response()->json($trips, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function getServicePlans(Request $request)
    {
        $service = (Auth::user())->service_type;
        $canRecieve = Auth::user()->hasRight(Right::CAN_RECEIVE_SERVICE_PLAN);
        if ($service === null || !$canRecieve) {
            return response()->json(['plans' => [], 'service_id' => 0], 200);
        }

        $trips = Ticket101ServicePlan::with(['ticket', 'results']);
        $trips = $trips
            ->where('is_closed', false)
            ->where('service_type_id', $service->id)
            ->whereNotNull('dispatched_time')
            ->where('is_accepted', false)
            ->get();

        return response()->json(['plans' => $trips, 'service_id' => $service->id], 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function postRoadtripNotificationToken(Request $request)
    {
        $this->noLayout();
        $user = Auth::user();
        $subscription = RoadtripSubscription::updateOrCreate(['token' => $request->get('token')], ['user_id' => $user->id]);
        $subscription->save();
        return response()->json($subscription);
    }
}
