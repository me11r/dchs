<?php

namespace App\Http\Controllers;


use App\Analytics101;
use App\Analytics101Item;
use App\Chronology101;
use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\FireObject;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\DistrictManager;
use App\DrillType;
use App\EmergencyType;
use App\EventInfo;
use App\EventInfoArrived;
use App\FireDepartment;
use App\FormationReport;
use App\FormationTechReport;
use App\Http\Middleware\Rights\FormationRecord;
use App\Http\Resources\HydrantResource;
use App\LivingSectorType;
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
use App\ObjectClassification;
use App\OperationalCard;
use App\RideType;
use App\Services\AnalyticsService;
use App\Services\FileUploadService;
use App\Ticket101;
use App\Ticket101Drill;
use App\Ticket101HqRide;
use App\Ticket101Other;
use App\Ticket101ServicePlan;
use App\TrunkType;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardController extends AuthorizedController
{

    public function getMapscreen(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        $canEditOwnHydrants = Auth::user()->hasRight('CAN_EDIT_MAP_HYDRANTS');
        $canEditAllHydrants = Auth::user()->hasRight('CAN_EDIT_HYDRANT_LOCATIONS');

        $userDept = Auth::user()->fire_department_id;

        $this->set('areas', (new CityArea())->get()->toArray());
        $this->set('fireDepartments', collect(FireDepartment::all(['id', 'title']))->toArray());
        $this->set('model', new HydrantResource(new Hydrant()));

        $this->set('userDeptRight', Auth::user()->role->hydrant_access_id ?? 0);

        $this->set('isAdmin', $isAdmin);
        $this->set('canEditAllHydrants', $canEditAllHydrants);
        $this->set('canEditOwnHydrants', $canEditOwnHydrants);
        $this->set('userDept', $userDept);
    }

    public function hydrants(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        $canEditOwnHydrants = Auth::user()->hasRight('CAN_EDIT_MAP_HYDRANTS');
        $canEditAllHydrants = Auth::user()->hasRight('CAN_EDIT_HYDRANT_LOCATIONS');
        $userDept = Auth::user()->fire_department_id;

        $data['showHydrants'] = true;

        $data['areas'] = (new CityArea())->get()->toArray();
        $data['fireDepartments'] = collect(FireDepartment::all(['id', 'title']))->toArray();
        $data['model'] = new HydrantResource(new Hydrant());

        $data['userDeptRight'] = Auth::user()->role->hydrant_access_id ?? 0;
        $data['isAdmin'] = $isAdmin;
        $data['canEditOwnHydrants'] = $canEditOwnHydrants;
        $data['userDept'] = $userDept;
        $data['canEditAllHydrants'] = $canEditAllHydrants;

        return view('card.mapscreen', $data);
    }

    public function get101(Request $request, $card_type = null)
    {
        $perPage = $request->get('per_page', 10);

        $search = trim($request->search);

        if(Auth::user()->hasRight('DELETE_CARD101')){
            $can_delete = true;
        }

        $sort = $request->get('sort', 'created_at');
        $id = $request->input('filter.id', '');
        $city_area = $request->input('filter.city_area', '');


        $city_areas = CityArea::all();

        if($id){
            $tickets = Ticket101::
                orderBy($sort,'desc')
                ->real()
                ->where('id',$id)
                ->paginate($perPage);
        }
        elseif($city_area){

            $tickets = Ticket101::
                where('city_area_id', $city_area)
                ->real()
                ->orderBy($sort,'desc')
                ->paginate($perPage);
        }
        elseif($search){
            if(is_numeric($search)){
                $tickets = Ticket101::
                    where('id', $search)
                    ->real()
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
                $tickets = Ticket101::
                    real()
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
            $tickets = Ticket101::
                real()
                ->orderBy($sort,'desc')
                ->paginate($perPage);
        }

        $this->set('tickets', $tickets)
            ->set('city_areas', $city_areas)
            ->set('id', $id)
            ->set('card_type', 'real')
            ->set('search', $search)
            ->set('can_delete', $can_delete ?? false)
            ->set('type', $card_type)
            ->set('city_area', $city_area)
            ->set('per_page', $perPage);
    }

    public function getAdd101(Request $request, $card_id = 0, $card_type = null)
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

        $this->set('can_change_card101_emergency_status', Auth::user()->hasRight('CAN_CHANGE_CARD101_EMERGENCY_STATUS'));
        $this->set('emergencyTypes', EmergencyType::all());
        $this->set('wall_materials', $wall_materials);
        $this->set('notification_get_back', session()->pull('notification.get_back', 0));
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
        $this->set('fire_levels', FireLevel::all());
        $this->set('object_classifications', ObjectClassification::all());
        $this->set('living_sector_types', LivingSectorType::all());
        $this->set('burn_object', FireObject::all());
        $this->set('trip_result', TripResult::all());
        $this->set('liquidation_methods', LiquidationMethod::all());
        $this->set('trunk_types', TrunkType::all());
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
                'other_records',
                'chronologies.event_info',
                'chronologies.event_info_arrived',
                'chronologies.fire_department_result.tech',
                'chronologies.fire_department_result.department',
                'chronologiesFromFd',
                'chronologiesFromFd.event_info',
                'chronologiesFromFd.event_info_arrived',
                'chronologiesFromFd.fire_department_result.tech',
                'chronologiesFromFd.fire_department_result.department',
                'results.tech.formation_tech_report',
                'results.department',
                'notifications.service',
                'popup_notifications.user',
                'popup_notifications.status',
                'popup_notifications.group',
                'notification_groups',
                'operational_plan.special_plans',
                'service_plans.service_type',
                'fireDepartmentsInfo.fire_level',
                'fireDepartmentsInfo.burn_object',
                'fireDepartmentsInfo.trip_result',
                'fireDepartmentsInfo.fire_department',
                'fireDepartmentsInfo.living_sector_type',
                'fireDepartmentsInfo.liquidation_method',
                'fireDepartmentsInfo.water_supply_source',
                'hqRides',
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

        if(!$ticket->max_square){
            $max_square = Ticket101OtherRecord::where('ticket101_id', $ticket->id)
                ->max('square');
        }
        else{
            $max_square = $ticket->max_square;
        }

        if($ticket->results()->where('get_back', true)->exists()){
            session(['notification.get_back' => 1]);
        }

        $fire_dep_results_info = '';
        foreach ($ticket->results()->where('dispatched', true)->get() as $item) {
            $fire_dep_results_info .= "{$item->department->name}: {$item->tech->department}; ";
        }

        foreach ($ticket->hqRides as $hqRide) {
            if($hqRide->dispatched){
                $fire_dep_results_info .= "{$hqRide->name}";
            }
        }

        $water_sources = WaterSupplySource::all();

        $this->set('notificationGroups', (new NotificationGroup())->get());
        $this->set('recommendedDispatched', $recommendedDispatched);
        $this->set('fire_dep_results_info', $fire_dep_results_info);
        $this->set('water_sources', $water_sources);
        $this->set('max_square', $max_square);
        $this->set('ticket', $ticket);
        $this->set('other_records_unique', $other_records_unique);
        $this->set('drill_types', DrillType::all());
    }

    private function saveAnalytics($ticket)
    {
        $analytics = new AnalyticsService();
        try{
            $analytics->fill($ticket);
        }
        catch (\Exception $exception){

        }
    }

    private function saveLog($id)
    {
        $ticket = Ticket101::with([
            /*'crossroad_1',
            'crossroad_2',
            'other_records',
            'chronologies',
            'chronologies.event_info',
            'chronologies.event_info_arrived',
            'chronologies.fire_department_result.tech',
            'chronologies.fire_department_result.department',*/
            'results',
            'results.tech',
            'results.tech.formation_tech_report',
            'results.department',
//            'notifications',
//            'notifications.service',
//            'popup_notifications',
//            'popup_notifications.user',
//            'popup_notifications.status',
//            'popup_notifications.group',
//            'notification_groups',
//            'notifications.service',
//            'operational_card',
//            'operational_plan.special_plans',
//            'service_plans',
//            'analytics',
//            'fire_level',
//            'fire_object',
//            'burn_object',
//            'trip_result',
//            'liquidation_method',
//            'road_trip_plans',
//            'operational_plan',
//            'fire_department',
//            'living_sector_type',
//            'other_records',
//            'popup_notifications',
//            'notification_groups',
//            'notifications',
//            'results',
//            'water_supply_source',
//            'wall_material',
//            'operational_card',
//            'service_plans',
//            'file_1',
//            'file_2',
//            'file_3',
//            'file_4',
//            'service_plans.service_type'
        ])->find($id);

        $ticket->logs()->create([
            'user_id' => Auth::id(),
            'body' => $ticket,
        ]);
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

                $exists = FireDepartmentResult::where('ticket101_id', $card->id)
                    ->where('fire_department_id', $tech_item->formation_tech_report->dept_id)
                    ->where('tech_id', $tech_item->id)
                    ->first();

                /*если подразделение еще не вернулось с прошлого происшествия*/
                /*и прошлое происшествие не является учебным*/
                $notAvailable = FireDepartmentResult::where('tech_id', $tech_item->id)
                    ->where(function ($qq) {
                        $qq->whereNotNull('accept_time')
                            ->whereNull('retreat_time');
                    })
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
                                        ->where('id', '<>', $card->id)
                                        ->where('fire_department_id',$card->fire_department_id)
                                        ->real();
                                })
                                    ->where('fire_department_id',$results[$schedule_dept]->fire_department_id)
                                    ->where('tech_id',$results[$schedule_dept]->tech_id)
                                    ->where('created_at', '>', $now->addSeconds(15)->format('Y-m-d H:i:s'))
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

    private function saveHqRides(Request $request, $card)
    {
        $data = $request->hq;

        if($data){
            foreach ($data as $fieldName => $departments) {
                foreach ($departments as $deptName => $inputName) {
                    Ticket101HqRide::updateOrCreate([
                        'name' => $deptName,
                        'ticket101_id' => $card->id,
                    ],[
                        'ticket101_id' => $card->id,
                        'name' => $deptName,
                        'department' => $request->input("hq.dept.{$deptName}.0", null),
                        'accept_time' => $request->input("hq.accept_time.{$deptName}.0", null),
                        'out_time' => $request->input("hq.out_time.{$deptName}.0", null),
                        'arrive_time' => $request->input("hq.arrive_time.{$deptName}.0", null),
                        'ret_time' => $request->input("hq.ret_time.{$deptName}.0", null),
                        'dispatch_time' => $request->input("hq.dispatch_time.{$deptName}.0", null),
                    ]);
                }
            }
        }
    }

    public function postAdd101(Request $request, $card_id = 0)
    {
        if (Auth::user()->hasRight('CAN_EDIT_REQUEST')) {
            $data = $request->except([
                'ph',
                'departments_to_ride',
                'time_arrive',
                'on_way',
                'file_1',
                'file_2',
                'file_3',
                'file_4',
                'hq',
                'notification_services',
                'district_manager_id',
                '00:00', // дефолтное названия инпута из компонента timepicker
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
            $card = Ticket101::findOrNew($card_id);/*при создании карточки единожды привязываемся к строевой записке, во избежание дублей высылки*/
            if (!$card->id) {
                $data['formation_report_id'] = FormationReport::approved()
                    ->has('people_reports')
                    ->max('id');
            }
            $canEditTicket = $card->canEditTicket();
            if (!$canEditTicket && !Auth::user()->hasRight('CARD101_EDIT_CLOSED')) {

                if ($request->ajax()) {
                    return response()->json('ok', 403);
                }
                return redirect('/card/add101/')->with('_message', ['type' => 'error', 'text' => 'Данные не могут быть сохранены. Архивная карточка']);
            }/*если поменяли уровень пожара, новые рекомендации */
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
            $card->fill($data);
            $card->save();
            $this->saveOtherRecords($card, $otherRecords);
            if ($card_id) {
                $this->updateNotificationServices($request->input('notification_services', []));
            } else {
                $this->createNotificationServices($card);
            }
            $this->createServicePlans($card);
            $this->updateServicePlans($request->input('notification_services', []));
            $this->recommend($request, $card);
            $this->saveHqRides($request, $card);//если ранг пожара понизили, отделения которые выехали на пожар, но не входят в новые рекомендации,
            // надо вернуть
            if ($deptsToGetBack->count()) {
                $getBackArray = $deptsToGetBack->pluck('tech_id')->toArray();

                $alreadyRecommended = $card->results()
                    ->recommended()
                    ->whereIn('tech_id', $getBackArray)
                    ->markToGetBack();

                session(['notification.get_back' => $alreadyRecommended]);
            }
            $this->saveArriveTimes($request);
            $this->saveFiles($card, $request);
            if ($card->trip_result_id) {
                $this->saveAnalytics($card);
            }
            $card_type = ($card->drill_type ?? null) == null ? '' : 'drill';
            if ($comeback) {
                $back = "/card/add101/{$card->id}/{$card_type}#return={$comeback}";
            } else {
                $back = "/card/add101/{$card->id}/{$card_type}";
            }
        }

        if(Auth::user()->hasRight('CAN_CHANGE_CARD101_EMERGENCY_STATUS')) {
            $card = Ticket101::find($card_id);
            if ($card) {
                $card->emergency_type_id = $request->emergency_type_id;
                $card->save();
            }
        }

        $this->setEmergencyType($card);
        $this->setDistrictManager($card, $request);

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

    private function setDistrictManager(&$ticket101, Request $request)
    {
        if($request->district_manager_id) {
            $cityAreaId = $ticket101->city_area_id;
            $date = $ticket101->formation_report ? $ticket101->formation_report->report_date : null;

            $ticket101->district_manager_id = DistrictManager::getDailyPerson($cityAreaId, $date)->id ?? null;

            $ticket101->save();
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

    private function updateServicePlans($servicePlans): void
    {
        foreach ($servicePlans as $id => $data) {
            $record = Ticket101ServicePlan::find($id);
            $record->name_accepted = $data['name'] ?? null;
            $record->dispatched_time = $data['message_time'] ? Carbon::parse($data['message_time']) : null;
            $record->arrive_time = $data['arrive_time'] ? Carbon::parse($data['arrive_time']) : null;
            $record->save();
        }
    }

    private function updateNotificationServices(array $notificationServices)
    {
        foreach ($notificationServices as $id => $data) {
            $record = Ticket101Notification::find($id);
            if($record) {
                $record->name = $data['name'] ?? null;
                $record->message_time = $data['message_time'] ?? null;
                $record->arrive_time = $data['arrive_time'] ?? null;
                $record->save();
            }
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

    public function postDelete(Request $request)
    {
        if(Auth::user()->hasRight('DELETE_CARD101')){
            Ticket101::destroy($request->id);
        }

        return response()->json(['ok'], 200);
    }

    public function setEmergencyType(&$card)
    {
        // ARM-414:
        // если в карточке 101 во вкладке "Итоги выезда", поле "результат выезда"
        // проставляется результат "Пожар"/"отравление угарным газом"
        // должен автоматически проставляться статус "ЧС"

        if($card) {
            $emergencyType = EmergencyType::where('name', 'ЧС')->first();
            $tripResult = TripResult::find($card->trip_result_id);
            $isFire = $tripResult ? in_array($tripResult->name,['Отравление угарным газом', 'Пожар']) : false;

            if($emergencyType && $isFire) {
                $card->emergency_type_id = $emergencyType->id;
                $card->save();
            }
        }
    }
}
