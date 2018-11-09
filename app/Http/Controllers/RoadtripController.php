<?php


namespace App\Http\Controllers;


use App\FireDepartment;
use App\Models\FireDepartmentResult;
use App\RoadtripPlan;
use App\RoadtripSubscription;
use App\Ticket101;
use App\User;
use Auth;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class RoadtripController extends AuthorizedController
{
    public function getIndex(Request $request)
    {
        $perpage = $request->get('per_page', 10);
        /** @var User $user */
        $user = Auth::user();
        $trips = RoadtripPlan::with(['ticket', 'department'])
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

        if(!$trip->is_accepted){
            $trip->is_accepted = true;
            $trip->save();

            $trip->results()->update(['accept_time' => now()]);
        }

        $departments = [];

        $results = $trip->ticket
            ->results()
            ->isDispatched()
            ->with(['tech'])
            ->where('fire_department_id', $trip->department_id)
            ->get();


        if($results->count()){
            foreach ($trip->ticket->results as $item) {
                $departments[] = $item->tech->department;
            }
        }

        $this->set('results', $results);
        $this->set('trip', $trip);
        $this->set('departments', $departments);
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

    public function getSend($dept_id, $ticket_id, $tech_id = null)
    {
        $this->noLayout();
        $ticket = Ticket101::findOrFail($ticket_id);
        $department = FireDepartment::findOrFail($dept_id);

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
                'dispatch_time' => now(),
                'dispatch_id' => $plan->id,
                'tech_id' => $tech_id,
                'ticket101_id' => $ticket_id,
                'fire_department_id' => $dept_id,
            ]
        );

        RoadtripSubscription::notifyDepartment($dept_id, $plan->id);

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
        if(env('IS_LOCAL', false) == true){
            return response()->json('ok', 200);
        }
        $this->noLayout();
        $html = view(
            'pdf.roadtrip-page',
            [
                'trip' => RoadtripPlan::with(['ticket', 'department', 'results'])->find($id),
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
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'roadtrip-'.$id.'.pdf', ['Content-type' => 'application/pdf']);
    }

    public function postDispatch(Request $request)
    {
        $result = FireDepartmentResult::find($request->dept_id);
        $result->dispatched = true;
        $result->out_time = now()->format('H:i:s');
        $result->save();
        return response()->json(['ok'], 200);
    }

    public function postReturn(Request $request)
    {
        $f = $request->all();
        $result = FireDepartmentResult::find($request->dept_id);
        $result->ret_time = now()->format('H:i:s');
        $result->save();
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
}
