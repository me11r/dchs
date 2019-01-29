<?php

namespace App\Http\Controllers;

use App\Card102;
use App\Card102RoadtripPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Roadtrip102Controller extends Controller
{
    public function index(Request $request)
    {
        $perpage = $request->get('per_page', 10);

        $trips = Card102::has('roadtrips')
            ->orderBy('created_at', 'desc')
            ->paginate($perpage);

        $data['trips'] = $trips;
        $data['per_page'] = $perpage;

        return view('roadtrip-102.index', $data);

    }

    public function show($id)
    {
        $record = Card102::with(['roadtrips'])->findOrFail($id);

        foreach ($record->roadtrips as $trip) {
            if(!$trip->accept_time){
                $trip->accept_time = now();
                //$record->name_accepted = Auth::user()->name;
                $trip->save();
            }
        }

        return view('roadtrip-102.view', compact('record'));
    }

    public function postDispatch(Request $request)
    {
        $result = Card102RoadtripPlan::find($request->dept_id);
//        $result->dispatched = true;
        $result->out_time = now();
        $result->save();
        return response()->json(['ok'], 200);
    }

    public function postArrived(Request $request)
    {
        $result = Card102RoadtripPlan::find($request->dept_id);
        $result->arrive_time = now();
        $result->save();
        return response()->json(['ok'], 200);
    }

    public function postReturn(Request $request)
    {
        $result = Card102RoadtripPlan::find($request->dept_id);
        $result->ret_time = now();
        $result->save();
        return response()->json(['ok'], 200);
    }


}
