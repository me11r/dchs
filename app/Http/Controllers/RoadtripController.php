<?php


namespace App\Http\Controllers;


use App\FireDepartment;
use App\RoadtripPlan;
use App\Ticket101;
use Illuminate\Http\Request;

class RoadtripController extends AuthorizedController
{
    public function getIndex()
    {
        $trips = RoadtripPlan::with(['ticket', 'department'])
            ->where('is_closed', false)
            ->get();


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
            'department_id' => $dept_id,
        ])
            ->save();
        $ticket->{'ph_' . $dept_id . '_dispatched'} = true;
        $ticket->{'ph_' . $dept_id . '_dispatch_id'} = $plan->id;
        $ticket->save();

        return redirect(route('card101add', ['card_id' => $ticket_id]))
            ->with('_message', [
                'type' => 'success',
                'text' => 'В подразделение отправлен путевой лист'
            ]);
    }
}