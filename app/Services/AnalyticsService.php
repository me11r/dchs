<?php

namespace App\Services;


use App\Analytics101;
use App\Analytics101Item;
use App\Chronology101;
use App\FireDepartment;
use App\Models\FireDepartmentResult;
use App\Models\Ticket101\Ticket101OtherRecord;
use Carbon\Carbon;

class AnalyticsService
{
    public function fill($ticket)
    {
        $deptsRetreated = $ticket->departments_retreated();
        $deptsArrived = $ticket->departments_arrived();
        $deptsArrivedHq = $ticket->departments_arrived_hq();
        $firstDeptArrived = $ticket->first_department_arrived();
        if ($firstDeptArrived) {
            $fireDeptResult = FireDepartmentResult::find($firstDeptArrived->id);
            $firstDeptArrived->name = $fireDeptResult->department->title;
            $firstDeptArrived->tech_dept = $fireDeptResult->tech->department;
            $firstDeptArrived->vehicle = $fireDeptResult->tech->vehicle->name;
        }

        $depts_out = $ticket->results()->whereNotNull('arrive_time')->get();
        $deptsArr = array_unique($depts_out->pluck('fire_department_id')->toArray());
        $depts_out_str = '';

        foreach (FireDepartment::whereIn('id', $deptsArr)->get() as $item) {
            $deptsNumbers = $depts_out->filter(function ($q) use ($item){
                return $q->fire_department_id === $item->id;
            })->pluck('tech.department')->toArray();

            $deptsNumbers = implode(',', $deptsNumbers);

            $depts_out_str .= "{$item->title}($deptsNumbers), ";
        }

        $max_square = $ticket->max_square ?? Ticket101OtherRecord::where('ticket101_id', $ticket->id)
                ->max('square');

        $service_plans_str = '';
        foreach ($ticket->service_plans()->whereNotNull('dispatched_time')->get() as $service_plan) {
            $service_plans_str .= $service_plan->service_type->name . ', ';
        }

        if($ticket->district_manager) {
            $service_plans_str .= "ОЧС ({$ticket->district_manager->city_area->name} район, {$ticket->district_manager->position} {$ticket->district_manager->name})";
        }

        $chronology_str = '';

        $chronology = Chronology101::where('ticket101_id', $ticket->id)
            ->whereNotNull('event_info_arrived_id')
            ->get();

        if ($chronology->count()) {
            foreach ($chronology as $chrono) {
                $chronology_str .= "$chrono->quantity " . ($chrono->event_info_arrived->name ?? null) . ', ';
            }
        }

        $result = [
            'result_title' => $ticket->trip_result->name,
            'detailed_address' => $ticket->detailed_address,
            'date' => Carbon::parse($ticket->custom_created_at)->format('d.m.Y H:i'),
            'date2' => Carbon::parse($ticket->custom_created_at)->format('d.m.Y'),
            'city_area' => $ticket->city_area->name ?? null,
            'address' => $ticket->location,
            'deptsRetreated' => $deptsRetreated,
            'deptsArrived' => $deptsArrived,
            'deptsArrivedHq' => $deptsArrivedHq,
            'caller_name' => $ticket->caller_name,
            'caller_phone' => $ticket->caller_phone,
            'pre_information' => $ticket->pre_information,
            'depts_out' => $depts_out_str,
            'first_dept_arrived' => $firstDeptArrived,
            'loc_time' => $ticket->loc_time,
            'liqv_time' => $ticket->liqv_time,
            'id' => $ticket->id,
            'trip_result_id' => $ticket->trip_result->id,
            'chronology_str' => $chronology_str,
            'square_max' => $max_square,
            'kui' => $ticket->kui,
            'ticket' => $ticket,
            'service_plans_str' => $service_plans_str,
        ];

        $text = view('_templates.report101-analytics', $result)->render();
        $text = str_replace('<br>', '<br/>', $text);

        $dataToSave = [
            'text' => $text,
            'trip_result_id' => $ticket->trip_result_id,
            'ticket101_id' => $ticket->id,
        ];

        $exist = Analytics101Item::where('ticket101_id', $ticket->id)->first();

        if($exist){
            $exist->fill($dataToSave);
            $exist->save();
        }
        else{
            $latestAnalytics = Analytics101::where('date', $ticket->created_at->format('Y-m-d'))->first();
            if(!$latestAnalytics){
                $latestAnalytics = Analytics101::select('*')->latest()->first();
            }

            if($latestAnalytics){

                $latestAnalytics->items()->firstOrCreate(['ticket101_id' => $ticket->id], $dataToSave);

            }
        }
    }
}