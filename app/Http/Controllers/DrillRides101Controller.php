<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Models\Staff;
use App\RideType;
use App\Ticket101Drill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrillRides101Controller extends Controller
{
    public function index(Request $request)
    {
        $data['per_page'] = $request->get('per_page', 10);
        $data['can_delete'] = false;
        $data['card_type'] = 'drill';

        if(Auth::user()->hasRight('CARD101_OTHERS_RIDES_CAN_DELETE')){
            $data['can_delete'] = true;
        }

        $data['records'] = Ticket101Drill::orderBy('id', 'desc')->paginate($data['per_page']);

        return view('card.card101-drill-rides.index', $data);
    }

    public function create(Request $request)
    {
        if($request->isMethod('POST')){
            Ticket101Drill::create($request->input('drill'));

            return redirect('card101-drill-rides')->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
        }
        else{
            $data['fire_departments_vue'] = FireDepartment::all();
            $data['ride_types'] = RideType::all();
            $data['staff'] = Staff::all();
            return view('card.card101-drill-rides.create-edit', $data);
        }
    }

    public function edit(Request $request, $id)
    {
        $data['record'] = Ticket101Drill::find($id);
        if($request->isMethod('POST')){
            $data['record']->fill($request->input('drill'));
            $data['record']->save();

            return redirect('card101-drill-rides')->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
        }
        else{
            $data['fire_departments_vue'] = FireDepartment::all();
            $data['ride_types'] = RideType::all();
            $data['staff'] = Staff::all();
            return view('card.card101-drill-rides.create-edit', $data);
        }
    }

    public function delete($id)
    {
        Ticket101Drill::destroy($id);
        return back();
    }
}
