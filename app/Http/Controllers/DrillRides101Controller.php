<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\DrillType;
use App\FireDepartment;
use App\Models\Staff;
use App\RideType;
use App\Ticket101;
use App\Ticket101Drill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrillRides101Controller extends Controller
{
    public function index(Request $request)
    {
        $data['per_page'] = $request->get('per_page', 10);
        $data['can_delete'] = Auth::user()->hasRight('CARD101_OTHERS_RIDES_CAN_DELETE');
        $data['card_type'] = 'drill';
        $data['search'] = $request->search;
        $data['city_areas'] = CityArea::all();
        $data['drill_types'] = DrillType::all();
        $data['tickets'] = Ticket101::drill()->orderBy('id', 'desc');
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        $data['city_area_id'] = $request->input('city_area_id', null);
        $data['drill_type_id'] = $request->input('drill_type_id', null);

        if($data['date_from'] && $data['date_to']) {
            $data['tickets'] = $data['tickets']->whereBetween('custom_created_at', [$data['date_from'], $data['date_to']]);
        }

        if($data['city_area_id']){

            $data['tickets'] = $data['tickets']->where('city_area_id', $data['city_area_id']);
        }

        if($data['drill_type_id']) {
            $data['tickets'] = $data['tickets']->where('drill_type_id', $data['drill_type_id']);
        }

        if($data['search']){
            if(is_numeric($data['search'])){
                $data['tickets'] = $data['tickets']->
                where('id', $data['search']);
            }
            else{
                try{
                    $date = Carbon::parse(str_replace(['/', '.'],'-',$data['search']));
                }
                catch (\Exception $e){
                    $date = null;
                }
                $data['tickets'] = $data['tickets']
                    ->where('location', "like", "{$data['search']}%")
                    ->orWhereDate('custom_created_at', $date)
                    ->orWhereHas('city_area', function ($q) use ($data){
                        $q->where('name', "like", "{$data['search']}%");
                    });
            }
        }

        $data['tickets'] = $data['tickets']
            ->paginate($data['per_page']);

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
