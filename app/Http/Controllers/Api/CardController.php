<?php

namespace App\Http\Controllers\Api;

use App\Arrived101;
use App\Chronology101;
use App\Chronology101FromFd;
use App\EventInfo;
use App\EventInfoArrived;
use App\Models\FireDepartmentResult;
use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\NormPsp;
use App\OnWay101;
use App\Services\Ticket101\NotificationService;
use App\Ticket101;
use App\Ticket101HqRide;
use App\Ticket101InfoFromFd;
use App\Ticket101Other;
use App\Ticket101OtherHqRide;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CardController extends Controller
{
    public function delete()
    {

    }

    public function postPromoteToAction(Request $request)
    {
        $reserved_item = FireDepartmentResult::find($request->id);
        $reserved_item->promoted_at = now();
        $reserved_item->promoted_department = $request->promoted_department;
        $reserved_item->save();

        return response()->json(['ok']);
    }

    public function sendNotifications(Request $request, NotificationService $notificationService)
    {
        $notificationService->sendNotificationsForGroups(
            $request->get('notificationMessage'),
            (int)$request->get('ticket101Id'),
            $request->get('notificationGroups', [])
        );

        return response()->json([]);
    }

    public function getTicket101(Request $request)
    {
        return response()->json([
            'ticket101' => Ticket101::with([
                'popup_notifications',
                'popup_notifications.user',
                'popup_notifications.status',
                'popup_notifications.group'])
                ->where('id', '=', $request->get('id'))
                ->first()
        ]);
    }

    public function createOtherRecord101card(Request $request)
    {
        $data = $request->all();
        $resp = [];
        if ($request->records) {
            foreach ($request->records as $record) {
                Ticket101OtherRecord::updateOrCreate(['id' => $record['id']], [
                    'ticket101_id' => $request->ticket_id,
                    'time' => $record['time'],
                    'comment' => $record['comment'],
                    'trunk_id' => $record['trunk_id'],
                    'count' => $record['count'],
                    'square' => $record['square'],
                ]);
            }
        } else {
            $resp = Ticket101OtherRecord::create([
                'ticket101_id' => $request->ticket_id,
                'time' => '00.00',
                'comment' => '',
                'trunk_id' => 1,
                'count' => 0,
                'square' => 0,
            ]);
        }

        return response()->json($resp);
    }

    public function createOnWayRecord101card(Request $request)
    {
        $data = $request->all();
        $resp = [];
        if ($request->record) {
            $resp = OnWay101::updateOrCreate(['id' => $request->record['id']], [
                'ticket101_id' => $request->ticket_id,
                'time' => $request->record['time'],
                'information' => $request->record['information'],
                'event_info_id' => $request->record['event_info_id'],
                'fire_department_result_id' => $request->input('record.fire_department_result.id'),
            ]);

            $resp = OnWay101::with([
                'event_info',
                'fire_department_result.tech',
                'fire_department_result.department',])
                ->where('id', $resp->id)
                ->first();

        }

        return response()->json($resp);
    }

    public function sendHqRide(Request $request)
    {
        $ride = $request->ride;
        $ride['dispatch_time'] = now()->format('H:i');
        $ride['dispatched'] = true;
        $model = $request->type === 'other' ? Ticket101OtherHqRide::class : Ticket101HqRide::class;
        $ride = $model::updateOrCreate([
            'ticket101_id' => $ride['ticket101_id'],
            'name' => $ride['name'],
        ],$ride);

        $ride = $model::find($ride->id);

        return response()->json($ride, 200);
    }

    public function createChronologyRecord101card(Request $request)
    {
        $data = $request->all();
        if(str_contains($request->input('record.time'), 'Z')){
            $time = Carbon::parse($request->input('record.time'), 'Asia/Almaty')
                ->addHours(6)
                ->format('H:i');
        }
        else{
            $time = $request->input('record.time');
        }

        $resp = [];
        $eventInfoArrived = EventInfoArrived::find($request->input('record.event_info_arrived_id', null));
        if($request->record){
            $resp = Chronology101::updateOrCreate(['id' => $request->record['id']],[
                'ticket101_id' => $request->ticket_id,
                'time' => $time,
                'water_delivery_distance' => $request->input('record.water_delivery_distance', null),
                'information' => $request->input('record.information', null),
                'event_info_id' => $request->input('record.event_info_id', null),
                'fire_department_result_id' => $request->input('record.hq', null) ? null : $request->input('record.fire_department_result.id'),
                'hq_ride_id' => $request->input('record.hq', null),
                'working_time' => $request->input('record.working_time', null),
                'quantity' => $request->input('record.quantity', null),
                'event_info_arrived_id' => $eventInfoArrived->id ?? null,
            ]);

            $resp = Chronology101::with([
                'event_info',
                'hq_ride',
                'event_info_arrived',
                'fire_department_result.tech',
                'fire_department_result.department',])
                ->where('id', $resp->id)
                ->first();

        }

        return response()->json($resp);
    }

    public function createChronologyRecord101cardFromFd(Request $request)
    {
        $data = $request->all();
        if(str_contains($request->input('record.time'), 'Z')){
            $time = Carbon::parse($request->input('record.time'), 'Asia/Almaty')
                ->addHours(6)
                ->format('H:i');
        }
        else{
            $time = $request->input('record.time');
        }

        $resp = [];
        if($request->record){
            $eventInfoArrived = EventInfoArrived::find($request->input('record.event_info_arrived_id', null));
            $resp = Chronology101FromFd::updateOrCreate(['id' => $request->record['id']],[
                'ticket101_id' => $request->ticket_id,
                'time' => $time,
                'information' => $request->input('record.information', null),
                'event_info_id' => $request->input('record.event_info_id', null),
                'fire_department_result_id' => $request->input('record.fire_department_result.id'),
                'water_delivery_distance' => $request->input('record.water_delivery_distance', null),

                'working_time' => $request->input('record.working_time', null),
                'quantity' => $request->input('record.quantity', null),
                'event_info_arrived_id' => $eventInfoArrived->id ?? null,
            ]);

            $resp = Chronology101FromFd::with([
                'event_info',
                'event_info_arrived',
                'fire_department_result.tech',
                'fire_department_result.department',])
                ->where('id', $resp->id)
                ->first();

        }

        return response()->json($resp);
    }

    public function copyChronologyRecord101cardFromFd(Request $request)
    {
        $data = $request->all();
        if(str_contains($request->input('record.time'), 'Z')){
            $time = Carbon::parse($request->input('record.time'), 'Asia/Almaty')
                ->addHours(6)
                ->format('H:i');
        }
        else{
            $time = $request->input('record.time');
        }

        $resp = [];
        if($request->record){

            $resp = new Chronology101([
                'ticket101_id' => $request->ticket_id,
                'time' => $time,
                'information' => $request->input('record.information', null),
                'event_info_id' => $request->input('record.event_info_id', null),
                'fire_department_result_id' => $request->input('record.fire_department_result.id'),
                'water_delivery_distance' => $request->input('record.water_delivery_distance', null),

                'working_time' => $request->input('record.working_time', null),
                'quantity' => $request->input('record.quantity', null),
                'event_info_arrived_id' => $request->input('record.event_info_arrived_id', null),
            ]);

            $resp->save();

            $resp = Chronology101::with([
                'event_info',
                'event_info_arrived',
                'fire_department_result.tech',
                'fire_department_result.department',])
                ->where('id', $resp->id)
                ->first();

        }

        return response()->json($resp);
    }

    public function createInfo101cardFromFd(Request $request)
    {
        $info = Ticket101InfoFromFd::updateOrCreate(['id' => $request->card['id']],[
            'detailed_address' => $request->card['detailed_address'],
            'burn_object_id' => $request->card['burn_object_id'],
            'living_sector_type_id' => $request->card['living_sector_type_id'],
            'trip_result_id' => $request->card['trip_result_id'],
            'liquidation_method_id' => $request->card['liquidation_method_id'],
            'result_fire_level_id' => $request->card['result_fire_level_id'],
            'max_square' => $request->card['max_square'],
            'vu_found' => $request->card['vu_found'],
            'animal_death' => $request->card['animal_death'],
            'car_crash' => $request->card['car_crash'],
            'rescued_count' => $request->card['rescued_count'],
            'evac_count' => $request->card['evac_count'],
            'co2_poisoned_count' => $request->card['co2_poisoned_count'],
            'ch4_poisoned_count' => $request->card['ch4_poisoned_count'],
            'gpt_burns_count' => $request->card['gpt_burns_count'],
            'people_death_count' => $request->card['people_death_count'],
            'children_death_count' => $request->card['children_death_count'],
            'hospitalized_count' => $request->card['hospitalized_count'],
            'ticket_result' => $request->card['ticket_result'],
            'special_tech' => $request->card['special_tech'],
            'more_info' => $request->card['more_info'],
            'water_supply_source_id' => $request->card['water_supply_source_id'],
            'distance' => $request->card['distance'],
            'owner' => $request->card['owner'],
            'ticket_id' => $request->card['ticket_id'],
            'fire_department_id' => $request->card['fire_department_id'],
        ]);

        return response()->json($info);
    }

    public function updateChronologyRecord101card(Request $request)
    {

    }

    public function createArrivedRecord101card(Request $request)
    {
        $data = $request->all();
        $resp = [];
        if ($request->record) {
            $resp = Arrived101::updateOrCreate(['id' => $request->record['id']], [
                'ticket101_id' => $request->ticket_id,
                'working_time' => $request->record['working_time'],
                'quantity' => $request->record['quantity'],
                'information' => $request->record['information'],
                'event_info_arrived_id' => $request->record['event_info_id'],
                'fire_department_result_id' => $request->input('record.fire_department_result.id'),
            ]);

            $resp = Arrived101::with([
                'event_info',
                'fire_department_result.tech',
                'fire_department_result.department',
            ])
                ->where('id', $resp->id)
                ->first();

        }

        return response()->json($resp);
    }

    public function deleteOnWayRecord101card(Request $request)
    {
        $data = $request->all();
        $record = OnWay101::destroy($request->id);
        $resp = [];

        return response()->json($resp);
    }

    public function deleteChronologyRecord101card(Request $request)
    {
        $data = $request->all();
        $record = Chronology101::destroy($request->id);
        $resp = [];

        return response()->json($resp);
    }

    public function deleteChronologyFromFdRecord101card(Request $request)
    {
        $data = $request->all();
        $record = Chronology101FromFd::destroy($request->id);
        $resp = [];

        return response()->json($resp);
    }

    public function deleteArrivedRecord101card(Request $request)
    {
        $data = $request->all();
        $record = Arrived101::destroy($request->id);
        $resp = [];

        return response()->json($resp);
    }

    public function checkRoadtrip(Request $request)
    {
        $id = $request->id;
        $otherId = $request->ticket_other_id;

        if($id) {
            $ticket = Ticket101::find($id);
        }
        else {
            return $this->checkTicket101Other($otherId);
        }

        if (!$ticket) {
            return response()->json([], 200);
        }

        //*новый вариант/
        $currentRidesTechIds = $ticket->results()->pluck('tech_id')->toArray();

        $techNotAvailableIds = FireDepartmentResult::whereIn('tech_id', $currentRidesTechIds)
            ->whereHas('ticket', function ($q){
                $q->real();
            })
            ->where(function ($q) {
                $q->whereNotNull('dispatch_time')
                    ->whereNull('retreat_time')
                    ->whereNull('ret_time');

            })
            ->where('ticket101_id', '<>', $ticket->id)
            ->get()
            ->pluck('tech_id')
            ->toArray();

        $data['recommendations'] = $ticket->results()
            ->whereNotIn('tech_id', $techNotAvailableIds)
            ->with([
                'tech',
                'tech.formation_tech_report',
                'department',
            ])->get();
        //*/

        $data['service_plans'] = $ticket->service_plans;
        $data['chronologies_fd'] = $ticket->chronologiesFromFd()->with([
            'event_info',
            'event_info_arrived',
            'fire_department_result.tech',
            'fire_department_result.department',
        ])->get();
        $data['departmentsOnWay'] = FireDepartmentResult::with(['department', 'tech'])
            ->onWay($id)
            ->get();

        return response()->json($data);
    }

    private function checkTicket101Other($id)
    {
        if (!$ticket = Ticket101Other::find($id)) {
            return response()->json([], 200);
        }

        $currentRidesTechIds = $ticket->results()->pluck('tech_id')->toArray();

        $techNotAvailableIds = FireDepartmentResult::whereIn('tech_id', $currentRidesTechIds)
            ->whereHas('ticket', function ($q){
                $q->real();
            })
            ->where(function ($q) {
                $q->whereNotNull('dispatch_time')
                    ->whereNull('ret_time');

            })
            ->whereNotNull('dispatch_time')
            ->get()
            ->pluck('tech_id')
            ->toArray();

        $data['recommendations'] = $ticket->results()
            ->whereNotIn('tech_id', $techNotAvailableIds)
            ->with([
            'tech',
            'tech.formation_tech_report',
            'department',
        ])->get();



        //старый вариант
        /*$data['recommendations'] = $ticket->results()->with([
            'tech',
            'tech.formation_tech_report',
            'department',
        ])->get();*/



        return response()->json($data);
    }

    public function postUpdateFireDepartmentResult(Request $request)
    {
        if ($request->hq) {
            $fireDeptResult = Ticket101HqRide::find($request->id);
        }
        else {
            $fireDeptResult = FireDepartmentResult::find($request->id);
        }

        if($fireDeptResult) {
            $fireDeptResult->staff_count = $request->staff_count;
            $fireDeptResult->save();
        }

        if($ticket = $fireDeptResult->ticket) {
            $sum = FireDepartmentResult::where('ticket101_id', $fireDeptResult->ticket->id)->sum('staff_count');

            $ticket->total_staff_count = $sum;
            $ticket->save();
        }

        return response()->json(['ok']);
    }

    public function postUpdateFireDepartmentResultDistance(Request $request)
    {
        if ($request->hq) {
            $fireDeptResult = Ticket101HqRide::find($request->id);
        }
        else {
            $fireDeptResult = FireDepartmentResult::find($request->id);
        }

        if($fireDeptResult) {
            $fireDeptResult->distance = $request->distance;
            $fireDeptResult->save();
        }

        return response()->json(['ok']);
    }

}
