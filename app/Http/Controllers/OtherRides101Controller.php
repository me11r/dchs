<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\FireDepartmentResult;
use App\Models\FormationTechItem;
use App\Models\Schedule;
use App\Models\Staff;
use App\RideType;
use App\RoadtripPlan;
use App\Ticket101HqRide;
use App\Ticket101Other;
use App\Ticket101OtherHqRide;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherRides101Controller extends Controller
{
    public function index(Request $request)
    {
        $data['per_page'] = $request->get('per_page', 20);
        $data['can_delete'] = Auth::user()->hasRight('CARD101_OTHERS_RIDES_CAN_DELETE');
        $data['card_type'] = 'other';
        $data['ride_types'] = RideType::all();
        $data['fire_departments'] = FireDepartment::recommend()->get();
        $data['filter_fd'] = $request->filter_fd;
        $data['filter_ride_type'] = $request->filter_ride_type;
        $data['search'] = $request->search;
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;

        $model = Ticket101Other::orderBy('id', 'desc');

        if($data['date_from'] && $data['date_to']) {
            $model = $model->whereBetween('custom_created_at', [$data['date_from'], $data['date_to']]);
        }

        if ($request->filter_ride_type) {
            $model = $model->where('ride_type_id', $request->filter_ride_type)
                ->orWhere('final_ride_type_id', $request->filter_ride_type)
            ;
        }

        if ($request->search) {
            $model = $model->where('final_object_name', "like", "%{$request->search}%")
                ->orWhere('final_direction', "like", "%{$request->search}%")
                ->orWhere('object_name', "like", "%{$request->search}%")
                ->orWhere('final_responsible_person', "like", "%{$request->search}%")
                ->orWhere('responsible_person', "like", "%{$request->search}%")
                ->orWhere('direction', "like", "%{$request->search}%")
            ;
        }

        if ($request->filter_fd) {
            $model = $model->whereHas('results', function ($q) use ($request) {
                $q->where('fire_department_id', $request->filter_fd)
                    ->whereNotNull('dispatch_id')
                ;
            });
        }

        $data['records'] = $model->paginate($data['per_page']);


        return view('card.card101-other-rides.index', $data);
    }

    public function create(Request $request)
    {
        if($request->isMethod('POST')){
            $dataToSave = $request->all();

            $dataToSave['created_by'] = Auth::id();

            $dataToSave['formation_report_id'] = FormationReport::approved()
                ->has('tech_reports')
                ->max('id');

            $dataToSave['custom_created_at'] = now();

            $record = Ticket101Other::create($dataToSave);
            $techItems = $this->recommend($record);

            $record->custom_created_at = Carbon::parse($record->custom_created_at)->format('Y-m-d H:i');

            foreach (['ДСПТ', 'КШМ', 'ИПЛ'] as $deptName) {
                Ticket101OtherHqRide::updateOrCreate([
                    'name' => $deptName,
                    'ticket101_id' => $record->id,
                ],[
                    'ticket101_id' => $record->id,
                    'name' => $deptName,
                    'accept_time' => '00:00',
                    'out_time' => '00:00',
                    'arrive_time' => '00:00',
                    'ret_time' => '00:00',
                    'dispatch_time' => '00:00',
                    'retreat_time' => '00:00',
                ]);
            }

            if($request->ajax()) {
                return response()->json(['record' => $record, 'techItems' => $techItems]);
            }

            return redirect('card101-other-rides')->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
        }
        else{
            $data['fire_departments'] = FireDepartment::recommend()->get();
            $data['ride_types'] = RideType::all();
            $data['staff'] = Staff::all();
            $data['techItems'] = json_encode([]);
            $data['hq'] = json_encode([]);
            $data['can_set_delayed'] = json_encode(Auth::user()->hasRight('CARD101_OTHER_RIDES_CAN_SET_DELAYED'));
            $data['canChangeCreatedAt'] = json_encode(Auth::user()->hasRight('CAN_CHANGE_CARD101_OTHER_RIDES_DATE'));
            return view('card.card101-other-rides.create-edit', $data);
        }
    }

    public function edit(Request $request, $id)
    {
        $data['record'] = Ticket101Other::find($id);

        $all = $request->all();

        $all['changed_by'] = Auth::id();

        if($request->isMethod('POST')){
            $data['record']->update($all);

            //если выбран отложенный выезд, переводим все путевые листы в неактивный режим
            if($data['record']->delayed_at) {
                $roadtrip_plans = $data['record']
                    ->roadtrip_plans()
                    ->update(['is_closed' => true]);
            }

            $techItems = $data['record']->results()->with([
                'tech',
                'tech.formation_tech_report',
            ])->get();

            $this->saveHqRides($request, $data['record']);

            return response()->json(['record' => $data['record'], 'techItems' => $techItems]);

        }
        else{

            $data['fire_departments'] = FireDepartment::recommend()->get();
            $data['ride_types'] = RideType::all();
            $data['staff'] = Staff::all();
            $data['hq'] = $data['record']->hqRides;
            $data['canChangeCreatedAt'] = json_encode(Auth::user()->hasRight('CAN_CHANGE_CARD101_OTHER_RIDES_DATE'));
            $data['techItems'] = FireDepartmentResult::where('ticket101_other_id', $id)
                ->with([
                    'tech',
                    'tech.formation_tech_report',
                ])
                ->get();

            $data['can_set_delayed'] = json_encode(Auth::user()->hasRight('CARD101_OTHER_RIDES_CAN_SET_DELAYED'));

            return view('card.card101-other-rides.create-edit', $data);
        }
    }

    private function saveHqRides(Request $request, $card)
    {
        $data = $request->hq_rides;

        if($data){
            foreach ($data as $fieldName => $ride) {

                Ticket101OtherHqRide::updateOrCreate([
                    'name' => $ride['name'],
                    'ticket101_id' => $card->id,
                ],[
                    'ticket101_id' => $card->id,
                    'name' => $ride['name'],
                    'department' => $ride['department'],

                    'accept_time' => $ride['accept_time'],
                    'out_time' => $ride['out_time'],
                    'arrive_time' => $ride['arrive_time'],
                    'ret_time' => $ride['ret_time'],
                    'dispatch_time' => $ride['dispatch_time'],
                    'retreat_time' => $ride['retreat_time'],
                ]);
            }
        }
    }

    public function delete($id)
    {
        Ticket101Other::destroy($id);
        return back();
    }

    private function recommend($card)
    {
        $results = [];
        $formationTechItems = [];

        /*последняя заполненная строевка, к который моы привязались при создании карточки*/
        $report_id = $card->formation_report_id;
        $formationTech = FormationTechReport::where('form_id', $report_id)
            ->has('items')
            ->get();

        foreach ($formationTech as $report) {

            $activeTech = $report
                ->items()
                ->whereIn('status', ['reserve', 'action'])
                ->get();

            foreach ($activeTech as $tech) {
                $formationTechItems[] = $tech;
            }
        }

        if (count($formationTechItems)) {
            foreach ($formationTechItems as $tech_item) {

                $exists = FireDepartmentResult::where('ticket101_other_id', $card->id)
                    ->where('fire_department_id', $tech_item->formation_tech_report->dept_id)
                    ->where('tech_id', $tech_item->id)
                    ->first();

                /*если подразделение еще не вернулось с прошлого происшествия*/
                /*и прошлое происшествие не является учебным*/
                $notAvailable = FireDepartmentResult::where('tech_id', $tech_item->id)
                    ->whereNotNull('accept_time')
                    ->whereHas('ticket', function ($q){
                        $q->real();
                    })
                    ->whereNull('ret_time')
                    ->first();

                if ($notAvailable) {
                    continue;
                }

                if (!$exists) {
                    $results[$tech_item->department] = FireDepartmentResult::create([
                        'ticket101_other_id' => $card->id,
                        'fire_department_id' => $tech_item->formation_tech_report->dept_id,
                        'dispatched' => false,
                        'tech_id' => $tech_item->id,
                        'recommended' => false,
                    ]);
                }
            }
        }

        return FireDepartmentResult::where('ticket101_other_id', $card->id)
            ->with([
                'tech',
                'tech.formation_tech_report',
            ])
            ->get();
    }

    public function switchDelayed(Request $request)
    {
        $data['record'] = Ticket101Other::find($request->id);
        $delayed = (boolean) $request->delayed;
        $delayed_at = $request->delayed_at;

        $roadtrip_plans = $data['record']
            ->roadtrip_plans()
            ->update(['is_closed' => $delayed]);

        if($delayed) {

            $data['record']->delayed_at = $delayed_at;
        }
        else {
            $data['record']->delayed_at = null;
        }

        $data['record']->save();

        return response()->json('ok');
    }

    public function cancelDelayed(Request $request)
    {
        $data['record'] = Ticket101Other::find($request->id);

        $roadtrip_plans = $data['record']
            ->roadtrip_plans()
            ->delete();

        $data['record']->delayed_at = null;

        $data['record']->save();

        $this->recommend($data['record']);

        return response()->json('ok');
    }

    public function approveDelayed(Request $request)
    {
        $data['record'] = Ticket101Other::find($request->id);

        $roadtrip_plans = $data['record']
            ->roadtrip_plans()
            ->update(['is_closed' => false]);

        $data['record']->delayed_at = null;

        $data['record']->save();

        return response()->json('ok');
    }
}
