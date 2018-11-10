<?php

namespace App\Http\Controllers;

use App\Models\Card112\Card112;
use App\Models\ServiceType;
use App\Ticket101ServicePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicePlanController extends Controller
{
    public function getList(Request $request)
    {
        $services = ServiceType::all();
        $userService = \auth()->user()->service_type_id;

        if($userService){
            $services = ServiceType::where('id', $userService)->get();
        }

        return view('service-plans.list', compact('services'));
    }

    public function getIndex(Request $request, $service)
    {
        $per_page = $request->get('per_page', 10);

        $records = Ticket101ServicePlan::department($service)
            ->orderBy('id', 'desc')
            ->whereNotNull('dispatched_time')
            ->paginate($per_page);

        return view('service-plans.index', compact('records', 'per_page'));
    }

    public function getShow($service, $id)
    {

        $record = Ticket101ServicePlan::findOrFail($id);

        if(!$record->is_accepted){
            $record->is_accepted = true;
            $record->name_accepted = Auth::user()->name;
            $record->save();
        }


        return view('service-plans.view', compact('record'));
    }

    public function postSend(Request $request)
    {
        $all = $request->all();
        if($request->cardType == 101){
            $servicePlan = Ticket101ServicePlan::updateOrCreate([
                'service_type_id' => $request->service_id,
                'card_id' => $request->card_id,
            ],[
                'service_type_id' => $request->service_id,
                'card_id' => $request->card_id,
                'dispatched_time' => now(),
            ]);
        }
        else{
            $servicePlan = Ticket101ServicePlan::updateOrCreate([
                'service_type_id' => $request->service_id,
                'card_id' => $request->card_id,
            ],[
                'service_type_id' => $request->service_id,
                'card112_id' => $request->card_id,
                'dispatched_time' => now(),
            ]);
        }

        return response()->json($servicePlan);
    }

    public function postCheck(Request $request)
    {
        $id = $request->card_id;
        $ticket = Card112::find($id);

        if(!$ticket){
            return response()->json(['servicePlans' => []], 200);
        }

        $data['servicePlans'] = $ticket->service_plans;

        return response()->json($data);
    }


    public function postAccept(Request $request, $id, $service)
    {
        $plan = Ticket101ServicePlan::findOrFail($id);
        if (!$plan->is_accepted) {
            $plan->is_accepted = true;
            $plan->name_accepted = $request->name_accepted;
            $plan->save();
        }
        return redirect("/service-plans/{$service}/{$id}/show")->with('_message', [
            'type' => 'success',
            'text' => 'Путевой лист принят в работу!'
        ]);
    }

    public function postArrive(Request $request, $id, $service)
    {
        $plan = Ticket101ServicePlan::findOrFail($id);
        if (!$plan->arrive_time) {
            $plan->arrive_time = now();
            $plan->save();
        }
        return redirect("/service-plans/{$service}/{$id}/show")->with('_message', [
            'type' => 'success',
            'text' => 'Отмечено время прибытия!'
        ]);
    }

    public function postReturn(Request $request, $id, $service)
    {
        $plan = Ticket101ServicePlan::findOrFail($id);
        if (!$plan->return_time) {
            $plan->return_time = now();
            $plan->is_closed = true;
            $plan->save();
        }
        return redirect("/service-plans/{$service}/{$id}/show")->with('_message', [
            'type' => 'success',
            'text' => 'Отмечено время возвращения!'
        ]);
    }
}
