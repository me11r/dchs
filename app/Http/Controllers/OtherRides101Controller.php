<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\FireDepartmentResult;
use App\Models\FormationTechItem;
use App\Models\Schedule;
use App\Models\Staff;
use App\RideType;
use App\Ticket101Other;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherRides101Controller extends Controller
{
    public function index(Request $request)
    {
        $data['per_page'] = $request->get('per_page', 10);
        $data['can_delete'] = false;
        $data['card_type'] = 'other';

        if(Auth::user()->hasRight('CARD101_OTHERS_RIDES_CAN_DELETE')){
            $data['can_delete'] = true;
        }

        $data['records'] = Ticket101Other::orderBy('id', 'desc')->paginate($data['per_page']);

        return view('card.card101-other-rides.index', $data);
    }

    public function create(Request $request)
    {
        if($request->isMethod('POST')){
            $dataToSave = $request->all();
            $dataToSave['formation_report_id'] = FormationReport::approved()
                ->has('tech_reports')
                ->max('id');

            $record = Ticket101Other::create($dataToSave);
            $techItems = $this->recommend($record);

            if($request->ajax()) {
                return response()->json(['record' => $record, 'techItems' => $techItems]);
                #return response()->json(['record' => ["id" => 1, 'created_at' => now()->toDateString()]]);
            }

            return redirect('card101-other-rides')->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
        }
        else{
            $data['fire_departments'] = FireDepartment::all();
            $data['ride_types'] = RideType::all();
            $data['staff'] = Staff::all();
            $data['techItems'] = json_encode([]);
            return view('card.card101-other-rides.create-edit', $data);
        }
    }

    public function edit(Request $request, $id)
    {
        $data['record'] = Ticket101Other::find($id);
        $all = $request->all();
        if($request->isMethod('POST')){
            $data['record']->update($all);

            $techItems = $data['record']->results()->with([
                'tech',
                'tech.formation_tech_report',
            ])->get();

            return response()->json(['record' => $data['record'], 'techItems' => $techItems]);

//            return redirect('card101-other-rides')->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
        }
        else{

            $data['fire_departments'] = FireDepartment::all();
            $data['ride_types'] = RideType::all();
            $data['staff'] = Staff::all();
            $data['techItems'] = FireDepartmentResult::where('ticket101_other_id', $id)
                ->with([
                    'tech',
                    'tech.formation_tech_report',
                ])
                ->get();;
            return view('card.card101-other-rides.create-edit', $data);
        }
    }

    public function delete($id)
    {
        Ticket101Other::destroy($id);
        return back();
    }

    private function recommend($card)
    {
        $results = [];
        $formationTechItems = [];

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

                $exists = FireDepartmentResult::where('ticket101_other_id', $card->id)
                    ->where('fire_department_id', $tech_item->formation_tech_report->dept_id)
                    ->where('tech_id', $tech_item->id)
                    ->first();

                /*если подразделение еще не вернулось с прошлого происшествия*/
                /*и прошлое происшествие не является учебным*/
                $notAvailable = FireDepartmentResult::where('tech_id', $tech_item->id)
                    ->whereNotNull('accept_time')
                    ->whereHas('ticket', function ($q){
                        $q->real();
                    })
                    ->whereNull('ret_time')
                    ->first();

                if ($notAvailable) {
                    continue;
                }

                if (!$exists) {
                    $results[$tech_item->department] = FireDepartmentResult::create([
                        'ticket101_other_id' => $card->id,
                        'fire_department_id' => $tech_item->formation_tech_report->dept_id,
                        'dispatched' => false,
                        'tech_id' => $tech_item->id,
                        'recommended' => false,
                    ]);
                }
            }
        }

        return FireDepartmentResult::where('ticket101_other_id', $card->id)
            ->with([
                'tech',
                'tech.formation_tech_report',
            ])
            ->get();
    }

    private function recommend1($card)
    {
        $results = [];
        $formationTechItems = [];
        $now = now();

        $report_id = FormationReport::approved()
            ->has('tech_reports')
            ->max('id');

        $formationTech = FormationTechReport::where('form_id', $report_id)
            ->has('items')
            ->get();


        $formationTechItems = FormationTechItem::whereHas('formation_tech_report', function ($q) use ($report_id) {
            $q->where('form_id', $report_id);
        })->whereIn('status', ['reserve', 'action'])->get(); //->groupBy('vehicle.fire_department_id');

        $formationTechItemsIds = $formationTechItems->pluck('id')->toArray();

        $notAvailableIds = FireDepartmentResult::whereIn('tech_id', $formationTechItemsIds)
            ->whereNotNull('accept_time')
            ->whereHas('ticket', function ($q){
                $q->real();
            })
            ->whereNull('ret_time')
            ->get()
            ->pluck('tech_id')
            ->toArray();

        $formationTechItems = FormationTechItem::whereHas('formation_tech_report', function ($q) use ($report_id) {
            $q->where('form_id', $report_id);
        })->whereIn('status', ['reserve', 'action'])
            ->whereNotIn('tech_id',$notAvailableIds)
            ->with([
                'vehicle',
                'vehicle.fireDepartment',
                'vehicle_status',
                'formation_tech_report',
            ])
            ->get();

        return $formationTechItems;

//        foreach ($formationTech as $report) {
//
//            $activeTech = $report
//                ->items()
//                ->whereIn('status', ['reserve', 'action'])
//                ->get();
//
//            foreach ($activeTech as $tech) {
//                $formationTechItems[] = $tech;
//            }
//        }

        if (count($formationTechItems)) {
            foreach ($formationTechItems as $tech_item) {

                $exists = FireDepartmentResult::where('ticket101_id', $card->id)
                    ->where('fire_department_id', $tech_item->formation_tech_report->dept_id)
                    ->where('tech_id', $tech_item->id)
                    ->first();

                /*если подразделение еще не вернулось с прошлого происшествия*/
                /*и прошлое происшествие не является учебным*/
                $notAvailable = FireDepartmentResult::where('tech_id', $tech_item->id)
                    ->whereNotNull('accept_time')
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
}
