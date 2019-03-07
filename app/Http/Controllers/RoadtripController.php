<?php


namespace App\Http\Controllers;


use App\Chronology101FromFd;
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
use App\LivingSectorType;
use App\Models\FireDepartmentResult;
use App\Models\Notification\NotificationGroup;
use App\Models\OperationalPlan;
use App\Models\ServiceType;
use App\Models\SpecialPlan;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\Trunk;
use App\OperationalCard;
use App\RoadtripPlan;
use App\RoadtripSubscription;
use App\Ticket101;
use App\Ticket101InfoFromFd;
use App\Ticket101Other;
use App\TrunkType;
use App\User;
use Auth;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadtripController extends AuthorizedController
{
    public function getIndex(Request $request)
    {
        $perpage = $request->get('per_page', 10);
        /** @var User $user */
        $user = Auth::user();
        $trips = RoadtripPlan::with(['ticket', 'department'])
            ->where(function ($q){
                $q->has('ticket')
                    ->orHas('ticket101_other');
            })
            ->where('is_closed', false);

        if ($user->fire_department_id) {
            $trips = $trips->where('department_id', $user->fire_department_id);
        }

        $trips = $trips
            ->orderBy('created_at', 'desc')
            ->paginate($perpage);

        $this->set('user', $user->load('department'));
        $this->set('trips', $trips)->set('per_page', $perpage);
    }

    public function getView($plan_id)
    {
        $trip = RoadtripPlan::with([
            'ticket',
            'ticket.operational_card',
            'ticket.operational_plan.special_plans',
            'department',
            'results'
        ])
            ->findOrFail($plan_id);

       if(!$trip->ticket){
           return redirect('roadtrip')->with('_message', [
               'type' => 'danger',
               'text' => 'Невозможно открыть путевой лист. Карточка была удалена'
           ]);
       }

        $results = $trip
            ->ticket
            ->results()
            ->isDispatched()
            ->with(['tech'])
            ->where('fire_department_id', $trip->department_id)
            ->get();

        //отмечаем отделения как "принятые в работу"
        //только если пользователь - оператор той ПЧ, в которую пришел путевой
        foreach ($results as $result) {
            if(!$result->accept_time && Auth::user()->fire_department_id === $trip->department_id){
                $result->accept_time = now();
                $result->save();
            }
        }

        if(!$trip->is_accepted){
            $trip->is_accepted = true;
            $trip->save();
        }

        $departments = [];

        if($results->count()){
            foreach ($trip->ticket->results as $item) {
                $departments[] = $item->tech->department;
            }
        }

        $this->set('results', $results);
        $this->set('trip', $trip);
        $this->set('departments', $departments);
    }

    public function getViewOther($plan_id)
    {
        $trip = RoadtripPlan::with([
            'ticket101_other',
        ])
            ->findOrFail($plan_id);

       if(!$trip->ticket101_other){
           return redirect('roadtrip')->with('_message', [
               'type' => 'danger',
               'text' => 'Невозможно открыть путевой лист. Карточка была удалена'
           ]);
       }

        $results = $trip
            ->ticket101_other
            ->results()
            ->isDispatched()
            ->with(['tech'])
            ->where('fire_department_id', $trip->department_id)
            ->get();

        foreach ($results as $result) {
            if(!$result->accept_time){
                $result->accept_time = now();
                $result->save();
            }
        }

        if(!$trip->is_accepted){
            $trip->is_accepted = true;
            $trip->save();
        }

        $departments = [];

        if($results->count()){
            foreach ($trip->ticket101_other->results as $item) {
                $departments[] = $item->tech->department;
            }
        }

        $data = [
            'results' => $results,
            'trip' => $trip,
            'departments' => $departments,
        ];

        return view('roadtrip.view-other', $data);
    }

    public function postPlan(Request $request, $plan_id)
    {
        $plan = RoadtripPlan::findOrFail($plan_id);
        if (!$plan->is_closed) {
            $plan->is_closed = true;
            $plan->return_time = Carbon::now();
            $plan->save();
        }

        $plan->result->ret_time = $plan->return_time;
        $plan->result->save();


        return back()
            ->with('_message', [
                'type' => 'success',
                'text' => 'Путевой лист закрыт'
            ]);
    }

    /*send dept from 101 card view*/
    public function getSend(Request $request, $dept_id, $ticket_id, $tech_id = null)
    {
        $this->noLayout();

        $plan = RoadtripPlan::firstOrCreate([
            'card_id' => $ticket_id,
            'department_id' => $dept_id
        ]);

        FireDepartmentResult::updateOrCreate(
            [
                'tech_id' => $tech_id,
                'ticket101_id' => $ticket_id,
            ],
            [
                'dispatched' => true,
                'retreat_time' => null,
                'dispatch_time' => now(),
                'dispatch_id' => $plan->id,
                'tech_id' => $tech_id,
                'ticket101_id' => $ticket_id,
                'fire_department_id' => $dept_id,
            ]
        );

        RoadtripSubscription::notifyDepartment($dept_id, $plan->id);

        if($request->ajax()){
            return response()->json('ok', 200);
        }

        return redirect(route('card101add', ['card_id' => $ticket_id]))
            ->with('_message', [
                'type' => 'success',
                'text' => 'В подразделение отправлен путевой лист'
            ]);
    }

    /*retreat dept from 101 card view*/
    public function postRetreat(Request $request, $dept_id, $ticket_id, $tech_id)
    {
        //признак того, что мы отзываем отделение из вкладки "Высылка" или "Хронология"
        $force = $request->force;

        $result = FireDepartmentResult::where('tech_id', $tech_id)
            ->where('ticket101_id', $ticket_id)
            ->firstOrFail();

        $result->retreat_time = now();

        $data = [];

        //время прибытия обнуляется, если высылали из "Хронологии"
        if ($force) {
            $result->dispatched = null;
            $result->dispatch_time = null;
            $result->arrive_time = null;
            $result->need_check_retreat = true;
        }

        $data['fd_chronology_item'] = Chronology101FromFd::create([
            'ticket101_id' => $ticket_id,
            'event_info_id',
            'time' => $result->retreat_time,
            'information' => 'отбой',
            'fire_department_result_id' => $result->id,
        ]);

        $data['fd_chronology_item'] = Chronology101FromFd::with([
            'fire_department_result.department',
            'fire_department_result.tech',
        ])->find($data['fd_chronology_item']->id);

        $result->save();

        if($request->ajax()){
            return response()->json($data);
        }
    }

    //отправить отделение (прочие выезды)
    public function postSendOther(Request $request, $dept_id, $ticket_id, $tech_id = null)
    {
        $this->noLayout();

        $plan = RoadtripPlan::firstOrCreate([
            'card101_other_id' => $ticket_id,
            'department_id' => $dept_id
        ]);

        FireDepartmentResult::updateOrCreate(
            [
                'tech_id' => $tech_id,
                'ticket101_other_id' => $ticket_id,
            ],
            [
                'dispatched' => true,
                'dispatch_time' => now(),
                'dispatch_id' => $plan->id,
                'tech_id' => $tech_id,
                'ticket101_other_id' => $ticket_id,
                'fire_department_id' => $dept_id,
            ]
        );

        RoadtripSubscription::notifyDepartment($dept_id, $plan->id);

        if($request->ajax()){
            return response()->json('ok', 200);
        }

        return redirect(route('card101add', ['card_id' => $ticket_id]))
            ->with('_message', [
                'type' => 'success',
                'text' => 'В подразделение отправлен путевой лист'
            ]);
    }

    public function postSendAll($ticket_id)
    {
        $this->noLayout();
        $ticket = Ticket101::findOrFail($ticket_id);

        foreach ($ticket->results()->recommended()->get() as $result) {
            $plan = RoadtripPlan::firstOrCreate([
                'card_id' => $ticket_id,
                'department_id' => $result->fire_department_id
            ]);

            if(!$result->dispatch_time) {
                $result->dispatch_time = now();

                $result->dispatched = true;
                $result->dispatch_id = $plan->id;
                $result->save();
            }
        }

        return response()->json('ok', 200);
    }

    public function postSendAllOther($ticket_id)
    {
        $this->noLayout();
        $ticket = Ticket101Other::findOrFail($ticket_id);

        foreach ($ticket->results()->get() as $result) {
            $plan = RoadtripPlan::firstOrCreate([
                'card_id' => $ticket_id,
                'department_id' => $result->fire_department_id
            ]);

            $result->dispatched = true;
            $result->dispatch_time = now();
            $result->dispatch_id = $plan->id;
            $result->save();
        }

        return response()->json('ok', 200);
    }

    public function postAccept(Request $request, $id)
    {
        $plan = RoadtripPlan::findOrFail($id);
        if (!$plan->is_accepted) {
            $plan->is_accepted = true;
            $plan->save();
        }
        return redirect('/roadtrip/view/' . $id)->with('_message', [
            'type' => 'success',
            'text' => 'Путевой лист принят в работу!'
        ]);
    }

    public function postForceOut(Request $request, $id)
    {
        $plan = RoadtripPlan::findOrFail($id);
        FireDepartmentResult::where('fire_department_id', $plan->department_id)
            ->where('ticket101_id', $plan->card_id)
            ->update(
                [
                    'out_time' => now()->toTimeString(),
                ]
            );

        return redirect('/roadtrip/view/' . $id)->with('_message', [
            'type' => 'success',
            'text' => 'Выезд сил одобрен'
        ]);
    }

    public function getPrint(Request $request, $id)
    {
        $record = RoadtripPlan::with(['ticket','ticket101_other', 'department', 'results'])->find($id);

        if($record->printed){
            return response()->json('', 200);
        }

        $view = $record->ticket ? 'pdf.roadtrip-page' : 'pdf.roadtrip-other-page';

        $this->noLayout();
        $html = view(
            $view,
            [
                'trip' => $record,
                'image_path' => $request->get('image_path')
            ])
            ->render();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf();
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        $pdf = $dompdf->output(['isRemoteEnabled' => true]);

        $record->printed = true;
        $record->save();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'roadtrip-'.$id.'.pdf', ['Content-type' => 'application/pdf']);
    }

    public function postDispatch(Request $request)
    {
        $result = FireDepartmentResult::find($request->dept_id);
        $result->dispatched = true;
        if($result->out_time === null) {
            $result->out_time = now()->format('H:i:s');
            $result->save();
        }

        return response()->json(['ok'], 200);
    }

    public function postArrived(Request $request)
    {
        $result = FireDepartmentResult::find($request->dept_id);
        if($result->out_time !== null) {
            $result->arrive_time = now()->format('H:i:s');
            $result->save();
        }
        else {
            return response()->json('error', 500);
        }
        return response()->json(['ok'], 200);
    }

    public function postReturn(Request $request)
    {
        $result = FireDepartmentResult::find($request->dept_id);
        if($result->out_time !== null && $result->arrive_time != null) {
            $result->ret_time = now()->format('H:i:s');
            $result->save();
        }
        else {
            return response()->json('error', 500);
        }

        return response()->json(['ok'], 200);
    }

    public function postRecommend(Request $request)
    {
        $f = $request->all();
        $result = FireDepartmentResult::find($request->id);
        $result->recommended = $request->recommended;
        $result->save();

        return response()->json(['ok'], 200);
    }

    public function getAdditional($id)
    {
        $trip = RoadtripPlan::with([
            'ticket',
            'ticket.chronologiesFromFd',
            'ticket.chronologiesFromFd.event_info',
            'ticket.chronologiesFromFd.event_info_arrived',
            'ticket.chronologiesFromFd.fire_department_result.department',
            'ticket.chronologiesFromFd.fire_department_result.tech',
//            'ticket.operational_card',
//            'ticket.operational_plan.special_plans',
            'department',
            'results'
        ])
            ->findOrFail($id);

        if(!$trip->ticket){
            return redirect('roadtrip')->with('_message', [
                'type' => 'danger',
                'text' => 'Невозможно открыть путевой лист. Карточка была удалена'
            ]);
        }
        $eventInfos = EventInfo::all();
        $eventInfosArrived = EventInfoArrived::all();
        $departmentsOnWay = FireDepartmentResult::with(['department', 'tech'])
            ->onWay($trip->ticket->id)
            ->where('fire_department_id', Auth::user()->fire_department_id ?? 1)
            ->get();

        $departmentsArrived = FireDepartmentResult::with(['department', 'tech'])
            ->arrived($trip->ticket->id)
            ->where('fire_department_id', Auth::user()->fire_department_id ?? 1)
            ->whereNull('retreat_time')
            ->orderBy('fire_department_id')
            ->get();

        $ticket = $trip->ticket;
        $ticket->getDetailedStaffCount = $ticket->getDetailedStaffCount();

        $this->set('eventInfos', $eventInfos);
        $this->set('eventInfosArrived', $eventInfosArrived);
        $this->set('departmentsOnWay', $departmentsOnWay);
        $this->set('departmentsArrived', $departmentsArrived);
        $this->set('trip', $trip);
        $this->set('ticket', $ticket);
        $this->set('fire_object', BurntObject::all());
        $this->set('fire_levels', FireLevel::all());
        $this->set('living_sector_types', LivingSectorType::all());
        $this->set('burn_object', FireObject::all());
        $this->set('trip_result', TripResult::all());
        $this->set('fire_object_options', FireObject::all());
        $this->set('liquidation_methods', LiquidationMethod::all());

        $this->set('fire_departments', collect(FireDepartment::recommend(true)->get())->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        })->toArray());
        $this->set('trunks', Trunk::orderBy('id', 'ASC')->get());

        if(!$trip->ticket->max_square){
            $max_square = Ticket101OtherRecord::where('ticket101_id', $trip->ticket->id)
                ->max('square');
        }
        else{
            $max_square = $trip->ticket->max_square;
        }

        $ticketInfo = Ticket101InfoFromFd::where('fire_department_id', Auth::user()->fire_department_id)
            ->where('ticket_id', $trip->ticket->id)
            ->first();

        if (!$ticketInfo) {
            $ticketInfo = new Ticket101InfoFromFd([
                'ticket_id' => $trip->ticket->id,
                'fire_department_id' => Auth::user()->fire_department_id ?? 1
            ]);
            $ticketInfo->save();
        }

        $this->set('notificationGroups', (new NotificationGroup())->get());
        $this->set('water_sources', WaterSupplySource::all());
        $this->set('max_square', $max_square);
        $this->set('ticketInfo', $ticketInfo);
        $this->set('trunk_types', TrunkType::all());
        $this->set('departmentId', Auth::user()->fire_department_id ?? 1);

    }
}
