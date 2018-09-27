<?php

namespace App\Http\Controllers;

use App\Ticket101ServicePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicePlanController extends Controller
{
    private $services = [
        '112' => '112',
        '102' => 'ДВД 102',
        '103' => 'БСМП 103',
        '104' => 'Служба газа 104',
        'electro' => 'Э\\сеть (277-98-42)',
        'water' => 'Водоканал (274-66-66)',
        'smk' => 'ЦМК (254-63-53)'
    ];
    public function getList(Request $request)
    {
//        $perpage = $request->get('per_page', 10);
//        /** @var User $user */
//        $user = Auth::user();
//        $trips = Ticket101ServicePlan::with(['ticket'])
//            ->where('is_closed', false);
//
//        if ($user->fire_department_id) {
//            $trips = $trips->where('department_id', $user->fire_department_id);
//        }
//
//        $trips = $trips
//            ->orderBy('created_at', 'desc')
//            ->paginate($perpage);
//
//        $this->set('user', $user->load('department'));
//        $this->set('trips', $trips)->set('per_page', $perpage);
        $services = $this->services;

        return view('service-plans.list', compact('services'));
    }

    public function getIndex(Request $request, $service)
    {
        $per_page = $request->get('per_page', 10);

        $records = Ticket101ServicePlan::department($service)->paginate($per_page);


        return view('service-plans.index', compact('records', 'per_page'));
    }

    public function getShow($service, $id)
    {

        $record = Ticket101ServicePlan::findOrFail($id);


        return view('service-plans.view', compact('record'));
    }

    public function postSend(Request $request)
    {
        $all = $request->all();
        Ticket101ServicePlan::firstOrCreate([
            'department' => $request->service,
            'card_id' => $request->card_id,
        ]);
        return response()->json(['ok']);
    }
}
