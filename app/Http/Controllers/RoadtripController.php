<?php


namespace App\Http\Controllers;


use App\FireDepartment;
use App\RoadtripPlan;
use App\Ticket101;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoadtripController extends AuthorizedController
{
    public function getIndex()
    {
        /** @var User $user */
        $user = Auth::user();
        $trips = RoadtripPlan::with(['ticket', 'department'])
            ->where('is_closed', false);

        if ($user->fire_department_id) {
            $trips = $trips->where('department_id', $user->fire_department_id);
        }

        $trips = $trips->get();

        $this->set('user', $user->load('department'));
        $this->set('trips', $trips);
    }

    public function getView($plan_id)
    {
        $trip = RoadtripPlan::with(['ticket', 'department'])
            ->findOrFail($plan_id);
        $this->set('trip', $trip);
    }

    public function postPlan(Request $request, $plan_id)
    {
        $plan = RoadtripPlan::findOrFail($plan_id);
        if (!$plan->is_closed) {
            $plan->is_closed = true;
            $plan->return_time = Carbon::now();
            $plan->save();
        }

        return redirect(route('roadtrip.plan.view', ['plan_id' => $plan_id]))
            ->with('_message', [
                'type' => 'sucess',
                'message' => 'Путевой лист закрыт'
            ]);
    }

    public function getSend($dept_id, $ticket_id)
    {
        $this->noLayout();
        $ticket = Ticket101::findOrFail($ticket_id);
        $department = FireDepartment::findOrFail($dept_id);

        $plan = new RoadtripPlan();
        $cnt = $plan
            ->where('card_id', $ticket_id)
            ->where('department_id', $dept_id)
            ->count();

        if ($cnt > 0) {
            return redirect(route('card101add', ['card_id' => $ticket_id]))
                ->with('_message', [
                    'type' => 'warning',
                    'text' => 'В подразделение уже был раннее отправлен путевой лист на этот пожар'
                ]);
        }

        $plan = new RoadtripPlan();
        $plan->fill([
            'card_id' => $ticket_id,
            'department_id' => $dept_id
        ])
            ->save();
        $ticket->{'ph_' . $dept_id . '_dispatched'} = true;
        $ticket->{'ph_' . $dept_id . '_dispatch_id'} = $plan->id;
        $ticket->{'ph_' . $dept_id . '_ot'} = \request('part');
        $ticket->save();

        return redirect(route('card101add', ['card_id' => $ticket_id]))
            ->with('_message', [
                'type' => 'success',
                'text' => 'В подразделение отправлен путевой лист'
            ]);
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
}
