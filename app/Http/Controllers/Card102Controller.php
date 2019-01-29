<?php

namespace App\Http\Controllers;

use App\Card102;
use App\Card102RoadtripPlan;
use App\Dictionary\CityArea;
use App\Models\IncidentType;
use App\Models\ServiceType;
use App\Ticket101ServicePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Card102Controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 15;
        $items = Card102::select('*')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return View::make('card102.index')
            ->with('items', $items)
            ->with('per_page', $perPage)
            ->with('city_areas', CityArea::all())
            ->render();
    }

    public function create()
    {
        $ticket_service_plans = [];
        $serviceTypes = ServiceType::orderBy('name')->get(['id', 'name']);
        foreach ($serviceTypes as $item) {
            $ticket_service_plans[] = new Ticket101ServicePlan();
        }

        return View::make('card102.create')
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes)->toArray())
            ->with('model', (new Card102()))
            ->with('service_plans', $ticket_service_plans)
            ->with('currentTabIndex', 0)
            ->render();
    }

    public function store(Request $request)
    {
        $index = $request->currentTabIndex;

        $req = $request->except([
            'notification_services'
        ]);

        $data = Card102::create($req);

        if ($index) {
            $back = "/card102/{$data->id}/edit/#return={$index}";
        }
        else{
            $back = "/card102/{$data->id}/edit/";
        }

        return redirect($back)
            ->with('currentTabIndex', $index);
    }

    public function show($id)
    {
        // there is no task for this
    }

    public function edit(Request $request, $id)
    {
        $serviceTypes = ServiceType::orderBy('name')->get(['id', 'name']);

        return View::make('card102.edit')
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes->toArray()))
            ->with('currentTabIndex', $request->input('currentTabIndex', 0))
            ->with('model', (new Card102())->where('id', '=', $id)
                ->with([
                    'cityArea',
                ])
                ->first());
    }

    public function update(Request $request, $id)
    {
        $index = $request->currentTabIndex;
        $data = $request->except([
            'notification_services'
        ]);
        $card = Card102::find($id);
        $card->fill($data);
        $card->save();
        return redirect(route('card102.edit', $id))->with('currentTabIndex', $index);
    }

    public function destroy($id)
    {
        Card102::destroy($id);
        return redirect(route('card102.index'));
    }

    public function sendDepartment(Request $request)
    {
        $roadTrip = Card102RoadtripPlan::firstOrCreate([
            'card102_id' => $request->cardId,
            'department_id' => $request->department,
        ],[
            'card102_id' => $request->cardId,
            'department_id' => $request->department,
            'dispatch_time' => now(),
            'dispatched' => true,
        ])->toSql();

        return response()->json(['ok']);
    }

    public function checkServicePlans(Request $request)
    {
        $id = $request->card_id;
        $ticket = Card102::find($id);

        if(!$ticket){
            return response()->json(['servicePlans' => []], 200);
        }

        $data['servicePlans'] = $ticket->service_plans;
        $data['roadtrips'] = $ticket->roadtrips;

        return response()->json($data);
    }
}
