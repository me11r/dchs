<?php

namespace App\Http\Controllers;


use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\FireObject;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\EventInfo;
use App\EventInfoArrived;
use App\FireDepartment;
use App\FormationReport;
use App\FormationTechReport;
use App\Http\Middleware\Rights\FormationRecord;
use App\Http\Resources\HydrantResource;
use App\Models\FireDepartmentResult;
use App\Models\Hydrant;
use App\Models\Notification\NotificationGroup;
use App\Models\NotificationService;
use App\Models\OperationalPlan;
use App\Models\Schedule;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\SpecialPlan;
use App\Models\Ticket101\Ticket101Notification;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\Trunk;
use App\Models\WallMaterial;
use App\OperationalCard;
use App\RideType;
use App\Services\FileUploadService;
use App\Ticket101;
use App\Ticket101Other;
use App\Ticket101ServicePlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardController extends AuthorizedController
{

    public function getMapscreen(Request $request)
    {
        $this->set('areas', (new CityArea())->get()->toArray());
        $this->set('fireDepartments', collect(FireDepartment::all(['id', 'title']))->toArray());
        $this->set('model', new HydrantResource(new Hydrant()));
    }

    public function get101(Request $request, $card_type = null)
    {
        $perPage = $request->get('per_page', 10);

        $f = $request->all();

        $search = trim($request->search);

        $sort = $request->get('sort', 'created_at');
        $id = $request->input('filter.id', '');
        $city_area = $request->input('filter.city_area', '');

        $city_areas = Ticket101::groupBy('city_area_id')
            ->checkDrill($card_type)
            ->get(['city_area_id'])
            ->pluck('city_area_id')
            ->toArray();

        $city_areas = CityArea::whereIn('id', $city_areas)->get();

        if($id){
            $tickets = Ticket101::with(['city_area'])
                ->orderBy($sort,'desc')
                ->checkDrill($card_type)
                ->where('id',$id)
                ->paginate($perPage);
        }
        elseif($city_area){

            $tickets = Ticket101::with(['crossroad_1', 'crossroad_2', 'city_area'])
                ->where('city_area_id', $city_area)
                ->checkDrill($card_type)
                ->orderBy($sort,'desc')
                ->paginate($perPage);
        }
        elseif($search){
            if(is_numeric($search)){
                $tickets = Ticket101::with(['crossroad_1', 'crossroad_2', 'city_area'])
                    ->where('id', $search)
                    ->checkDrill($card_type)
                    ->orderBy($sort,'desc')
                    ->paginate($perPage);
            }
            else{
                try{
                    $date = Carbon::parse(str_replace(['/', '.'],'-',$search));
                }
                catch (\Exception $e){
                    $date = null;
                }
                $tickets = Ticket101::with(['crossroad_1', 'crossroad_2', 'city_area'])
                    ->checkDrill($card_type)
                    ->where('location', "like", "$search%")
                    ->orWhereDate('created_at', $date)
                    ->orWhereHas('city_area', function ($q) use ($search){
                        $q->where('name', "like", "$search%");
                    })
                    ->orderBy($sort,'desc')
                    ->paginate($perPage);
            }

        }
        else{
            $tickets = Ticket101::with(['crossroad_1', 'crossroad_2', 'city_area'])
                ->checkDrill($card_type)
                ->orderBy($sort,'desc')
                ->paginate($perPage);
        }

        $this->set('tickets', $tickets)
            ->set('city_areas', $city_areas)
            ->set('id', $id)
            ->set('search', $search)
            ->set('type', $card_type)
            ->set('city_area', $city_area)
            ->set('per_page', $perPage);
    }

    public function getAdd101(Request $request, $card_id = 0, $card_type = 'real')
    {
        $gu_notify = [
            '100' => '100',
            '101' => '101',
            '102' => '102',
            '103' => '103',
            '104' => '104',
            'b01' => 'ДЧС "Байкал-01"',
            'b04' => 'ДЧС "Байкал-04"',
        ];

        $service_notify = ServiceType::all();
        $eventInfos = EventInfo::all();
        $eventInfosArrived = EventInfoArrived::all();
        $departmentsOnWay = FireDepartmentResult::with(['department', 'tech'])
            ->onWay($card_id)
            ->get();

        $departmentsArrived = FireDepartmentResult::with(['department', 'tech'])
            ->arrived($card_id)
            ->get();

        $ssv_out = FireDepartment::recommend()->get();
        $wall_materials = WallMaterial::all();
        if ($card_id != 0) {
            foreach ($ssv_out as $key => $item) {
                $ssv_out[$key]->res = $item->results()->where('ticket101_id', $card_id)->first();
                $ssv_out[$key]->res_many = $item->results()->where('ticket101_id', $card_id)->get();
            }
        }

        $dep_results = FireDepartmentResult::all();
        $operational_cards = OperationalCard::all();
        $special_plans = SpecialPlan::all();

        $this->set('wall_materials', $wall_materials);
        $this->set('notification_get_back', session()->pull('notification.get_back', 0));
        $this->set('wall_materials', $wall_materials);
        $this->set('staff', Staff::all());
        $this->set('ride_types', RideType::all());
        $this->set('fire_departments_vue', FireDepartment::recommend()->get());
        $this->set('card_type', $card_type);
        $this->set('departmentsOnWay', $departmentsOnWay);
        $this->set('departmentsArrived', $departmentsArrived);
        $this->set('operational_cards', $operational_cards);
        $this->set('special_plans', $special_plans);
        $this->set('eventInfos', $eventInfos);
        $this->set('eventInfosArrived', $eventInfosArrived);
        $this->set('ssv_out', $ssv_out);
        $this->set('dep_results', $dep_results);
        $this->set('gu_notify', $gu_notify);
        $this->set('service_notify', $service_notify);
        $this->set('city_area', CityArea::with(['fire_departments'])->get());
        $this->set('fire_object', BurntObject::all());
        $this->set('fire_levels', FireLevel::all());
        $this->set('burn_object', BurntObject::all());
        $this->set('trip_result', TripResult::all());
        $this->set('liquidation_methods', LiquidationMethod::all());
        $this->set('fire_object_options', FireObject::all());
        $this->set('operational_plans', collect(OperationalPlan::all())->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        })->toArray());
        $this->set('fire_departments', collect(FireDepartment::recommend(true)->get())->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        })->toArray());
        $this->set('trunks', Trunk::orderBy('id', 'ASC')->get());
        $ticket = Ticket101::with(
            [
                'crossroad_1',
                'crossroad_2',
                'other_records',
                'chronologies',
                'chronologies.event_info',
                'chronologies.event_info_arrived',
                'chronologies.fire_department_result.tech',
                'chronologies.fire_department_result.department',
                'results',
                'results.tech',
                'results.tech.formation_tech_report',
                'results.department',
                'notifications',
                'notifications.service',
                'popup_notifications',
                'popup_notifications.user',
                'popup_notifications.status',
                'popup_notifications.group',
                'notification_groups',
                'notifications.service',
                'operational_card',
                'operational_plan.special_plans'
            ])
            ->findOrNew($card_id);

        $recommendedDispatched = $ticket->results()
            ->isDispatched()
            ->recommended()
            ->count();

        $other_records_unique = $ticket->other_records()->groupBy('trunk_id')->get(['trunk_id', DB::raw('MAX(count) as count')]);
        if ($other_records_unique->count()) {
            $trunk_ids = $other_records_unique->pluck('trunk_id')->toArray();
            $unique_count = $other_records_unique->pluck('count')->toArray();
            $other_records_unique = Ticket101OtherRecord::whereIn('trunk_id', $trunk_ids)
                ->where('ticket101_id', $ticket->id)
                ->whereIn('count', $unique_count)
                ->get();
        } else {
            $other_records_unique = [];
        }

        $max_square = Ticket101OtherRecord::where('ticket101_id', $ticket->id)
            ->max('square');

        if($ticket->results()->where('get_back', true)->exists()){
            session(['notification.get_back' => 1]);
        }


        $fire_dep_results_info = '';
        foreach ($ticket->results()->where('dispatched', true)->get() as $item) {
            $fire_dep_results_info .= "{$item->department->name}: {$item->departments}; ";
        }

        $water_sources = WaterSupplySource::all();

//        if (!\count($ticket->notifications) && $ticket->id){
//            $this->createNotificationServices($ticket);
//            $ticket->notifications()->get();
//        }

        $this->set('notificationGroups', (new NotificationGroup())->get());
        $this->set('recommendedDispatched', $recommendedDispatched);
        $this->set('fire_dep_results_info', $fire_dep_results_info);
        $this->set('water_sources', $water_sources);
        $this->set('max_square', $max_square);
        $this->set('ticket', $ticket);
        $this->set('other_records_unique', $other_records_unique);
    }

    /**
     * создаем рекомендации к выезду на основе расписания выездов ПЧ
     */
    private function recommend(Request $request, $card)
    {
        $results = [];
        $formationTechItems = [];
        $now = now();

        $schedules = Schedule::where('fire_department_main_id', $card->fire_department_id)
            ->where('dict_fire_level_id', $card->fire_level_id)
            ->get();

        /* последняя заполненная строевка*/
        $report_id = FormationReport::approved()->max('id');
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

                $exists = FireDepartmentResult::where('ticket101_id', $card->id)
                    ->where('fire_department_id', $tech_item->formation_tech_report->dept_id)
                    ->where('tech_id', $tech_item->id)
                    ->first();

                /*если подразделение еще не вернулось с прошлого происшествия*/
                /*и прошлое происшествие не является учебным*/
                $notAvailable = FireDepartmentResult::where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->whereHas('ticket', function ($q){
                        $q->real();
                    })
                    ->whereNull('ret_time')
                    ->first();

                if ($notAvailable) {
                    continue;
                }

                /*полуфабрикат для рекомендаций*/
                if (!$exists) {
                    $results[$tech_item->department] = FireDepartmentResult::create([
                        'ticket101_id' => $card->id,
                        'fire_department_id' => $tech_item->formation_tech_report->dept_id,
                        'dispatched' => false,
                        'tech_id' => $tech_item->id,
                        'recommended' => false,
                    ]);
                }

                /*рекомендации для каждого отделения, каждой пч (если указано в расписании и доступно)*/
                foreach ($schedules as $schedule_item) {

                    $schedule_depts = explode(',', str_replace(['.', ' '], ',', $schedule_item->department));

                    foreach ($schedule_depts as $schedule_dept) {
                        if (isset($results[$schedule_dept])) {
                            if ($results[$schedule_dept]->fire_department_id == $schedule_item->fire_department_id) {

                                $same_address_exists = FireDepartmentResult::whereHas('ticket', function ($q_ticket) use ($card){
                                    $q_ticket
                                        ->closed(false)
                                        ->where('city_area_id',$card->city_area_id)
                                        ->where('fire_department_id',$card->fire_department_id)
                                        ->real();
                                })
                                    ->where('fire_department_id',$results[$schedule_dept]->fire_department_id)
                                    ->where('tech_id',$results[$schedule_dept]->tech_id)
                                    ->where('created_at', '>', $now->subSeconds(15))
                                    ->recommended(true)
                                    ->first();

                                if(!$same_address_exists){
                                    $results[$schedule_dept]->recommended = true;
                                    $results[$schedule_dept]->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function postAdd101(Request $request, $card_id = 0)
    {
        $data = $request->except([
            'ph',
            'departments_to_ride',
            'time_arrive',
            'on_way',
            'file_1',
            'file_2',
            'file_3',
            'file_4'
        ]);

        $deptsToGetBack = collect([]);
        $r = $request->all();

        unset($data['comeback']);
        $back = '/card/101';

        $comeback = $request->get('comeback', false);
        $otherRecords = array_get($data, 'other_records', []);
        unset($data['other_records']);

        if ($request->operational_plan_id == 'NaN' || is_null($request->operational_plan_id)) {
            $data['operational_plan_id'] = 0;
        }

        if ($request->fire_level_id == 'NaN' || is_null($request->fire_level_id)) {
            $data['fire_level_id'] = 1;
        }

        if ($request->fire_department_id == 'NaN' || is_null($request->fire_department_id)) {
            $data['fire_department_id'] = 0;
        }

        /** @var Ticket101 $card */
        $card = Ticket101::findOrNew($card_id);

        $canEditTicket = $card->canEditTicket();
        if (!$canEditTicket && !Auth::user()->hasRight('CARD101_EDIT_CLOSED')) {

            if ($request->ajax()) {
                return response()->json('ok', 403);
            }
            return redirect('/card/add101/')->with('_message', ['type' => 'error', 'text' => 'Данные не могут быть сохранены. Архивная карточка']);
        }

        /*если поменяли уровень пожара, новые рекомендации */
        if ($card->fire_level_id !== null) {

            /* повышаем ранг*/
            if ($card->fire_level_id < $request->fire_level_id) {
                $card->results()->whereNull('out_time')->delete();
                $card->road_trip_plans()->where('is_accepted', false)->delete();
            } /* понижаем ранг*/
            elseif ($card->fire_level_id > $request->fire_level_id) {
                $deptsToDelete = $card->results()->whereNull('out_time'); //подразделение, которые еще не выехали, нужно удалить

                //подразделение, которые уже выехали, но, возможно не входят в дальнейшие рекомендации,
                //нужно вернуть
                $deptsToGetBack = $card->results()
                    ->whereNotNull('out_time')
                    ->recommended()
                    ->get();

                $deptsToDelete->delete();
                $card->road_trip_plans()->where('is_accepted', false)->delete();
            }
        }

        unset($data['notification_services']);

        $card->fill($data);
        $card->save();

        $this->saveOtherRecords($card, $otherRecords);

        if ($card_id) {
            $this->updateNotificationServices($request->input('notification_services', []));
        } else {
            $this->createNotificationServices($card);
        }

        $this->createServicePlans($card);


        $this->recommend($request, $card);

        //если ранг пожара понизили, отделения которые выехали на пожар, но не входят в новые рекомендации,
        // надо вернуть
        if($deptsToGetBack->count()){
            $getBackArray = $deptsToGetBack->pluck('tech_id')->toArray();

            $alreadyRecommended = $card->results()
                ->recommended()
                ->whereIn('tech_id', $getBackArray)
                ->markToGetBack();

            session(['notification.get_back' => $alreadyRecommended]);
        }

        $this->saveArriveTimes($request);

        $this->saveFiles($card, $request);

        /*if($request->input('other_rides', []) && $request->input('drill_type', '')){
            $other_ride = $request->input('other_rides', []);
            $other_ride['ticket_101_id'] = $card->id;

            $ticket_other = Ticket101Other::updateOrCreate(['ticket_101_id' => $card->id], $other_ride);
        }*/

        $card_type = ($ticket_other->drill_type ?? null) ==  null ? ''  : '/drill';
        if ($comeback) {
            $back = "/card/add101/{$card->id}{$card_type}#return={$comeback}";
        }
        else{
            $back = "/card/add101/{$card->id}{$card_type}";
        }

        if ($request->ajax()) {
            return response()->json('ok', 200);
        }

        return redirect($back)->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
    }

    private function saveFiles(Ticket101 $ticket101, Request $request)
    {
        $service = new FileUploadService();
        foreach ([1, 2, 3, 4] as $i) {
            $file = $request->file('file_' . $i);
            if ($file) {
                $upload = $service->saveFile($file);
                $ticket101->{'file_' . $i . '_id'} = $upload->id;
                $ticket101->save();
            }
        }
    }

    private function saveOtherRecords(Ticket101 $ticket101, array $otherRecords)
    {
        $ticket101->other_records()->delete();
        $ticket101->other_records()->saveMany(array_map(function ($item) {
            return new Ticket101OtherRecord($item);
        }, $otherRecords));
    }

    private function createNotificationServices(Ticket101 $ticket101): void
    {
        foreach (ServiceType::all() as $service) {
            Ticket101Notification::create([
                'notification_service_id' => $service->id,
                'ticket101_id' => $ticket101->id,
            ]);
        }
    }

    private function createServicePlans(Ticket101 $ticket101): void
    {
        foreach (ServiceType::all() as $service) {
            Ticket101ServicePlan::firstOrCreate([
                'service_type_id' => $service->id,
                'card_id' => $ticket101->id,
            ]);
        }
    }

    private function updateNotificationServices(array $notificationServices)
    {
        foreach ($notificationServices as $id => $data) {
            $record = Ticket101Notification::find($id);
            $record->name = $data['name'] ?? null;
            $record->save();
        }
    }

    private function saveArriveTimes(Request $request)
    {
        if($request->time_arrive){
            foreach ($request->time_arrive as $id => $item) {
                $dept_to_ride = FireDepartmentResult::find($id);
                if($dept_to_ride){
                    $dept_to_ride->arrive_time = $item;
                    $dept_to_ride->save();
                }
            }
        }
    }

    public function postSwitchStateCard($card_id)
    {
        $card = Ticket101::findOrFail($card_id);
        $card->closed = !$card->closed;
        $card->save();

        return response()->json('ok');
    }

    public function getAdd101OtherRide(Request $request)
    {
        if($request->isMethod('POST')){
            $f = $request->all();
            Ticket101Other::create($request->input('other_rides'));

            return back()->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
        }
        else{
            $data['fire_departments_vue'] = FireDepartment::all();
            $data['ride_types'] = RideType::all();
            $data['staff'] = Staff::all();
            return view('card/101other_rides', $data);
        }
    }
}
