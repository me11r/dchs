<?php

namespace App\Http\Controllers;

use App\Card103;
use App\Card103RoadtripPlan;
use App\Dictionary\CityArea;
use App\Models\IncidentType;
use App\Models\ServiceType;
use App\Ticket101ServicePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Card103Controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 15;
        $items = Card103::select('*')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return View::make('card103.index')
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

        return View::make('card103.create')
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes)->toArray())
            ->with('model', (new Card103()))
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

        $f = $request->all();

        $data = Card103::create($req);

        if ($index) {
            $back = "/card103/{$data->id}/edit/#return={$index}";
        }
        else{
            $back = "/card103/{$data->id}/edit/";
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

        return View::make('card103.edit')
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes->toArray()))
            ->with('currentTabIndex', $request->input('currentTabIndex', 0))
            ->with('model', (new Card103())->where('id', '=', $id)
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
        $card = Card103::find($id);
        $card->fill($data);
        $card->save();
        return redirect(route('card103.edit', $id))->with('currentTabIndex', $index);
    }

    public function destroy($id)
    {
        Card103::destroy($id);
        return redirect(route('card103.index'));
    }

    public function sendDepartment(Request $request)
    {
        $roadTrip = Card103RoadtripPlan::firstOrCreate([
            'card103_id' => $request->cardId,
            'department_id' => $request->department,
        ],[
            'card103_id' => $request->cardId,
            'department_id' => $request->department,
            'dispatch_time' => now(),
            'dispatched' => true,
        ])->toSql();

        return response()->json(['ok']);
    }

    public function checkServicePlans(Request $request)
    {
        $f = $request->all();
        $id = $request->card_id;
        $ticket = Card103::find($id);

        if(!$ticket){
            return response()->json(['servicePlans' => []], 200);
        }

        $data['servicePlans'] = $ticket->service_plans;
        $data['roadtrips'] = $ticket->roadtrips;

        return response()->json($data);
    }
}
