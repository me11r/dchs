<?php

namespace App\Http\Controllers;


use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\FireObject;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\FireDepartment;
use App\FormationTechReport;
use App\Http\Middleware\Rights\FormationRecord;
use App\Models\FireDepartmentResult;
use App\Models\OperationalPlan;
use App\Models\Schedule;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\Trunk;
use App\Models\WallMaterial;
use App\OperationalCard;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends AuthorizedController
{

    public function getMapscreen(Request $request)
    {
        $this->set('areas', (new CityArea())->get()->toArray());
    }

    public function get101(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $tickets = Ticket101::with(['crossroad_1', 'crossroad_2', 'city_area'])
            ->orderBy('created_at','desc')
            ->paginate($perPage);
        $this->set('tickets', $tickets)->set('per_page', $perPage);
    }

    public function getAdd101(Request $request, $card_id = 0)
    {
        $gu_notify = [
            '100' => '100',
            '101' => '101',
            '102' => '102',
            '103' => '103',
            '104' => '104',
            'b01' => 'ДЧС "Байкал-01"',
            'b04' => 'ДЧС "Байкал-04"',
        ];
        $service_notify = [
            '112' => '112',
            '102' => 'ДВД 102',
            '103' => 'БСМП 103',
            '104' => 'Служба газа 104',
            'electro' => 'Э\\сеть (277-98-42)',
            'water' => 'Водоканал (274-66-66)',
            'smk' => 'ЦМК (254-63-53)'
        ];
        $ssv_out = [
            1 => 'ӨСБ - ПЧ-1',
            ' - ПЧ-2',
            ' - ПЧ-3',
            ' - ПЧ-4',
            ' - ПЧ-5',
            ' - ПЧ-6',
            'МӨСБ - СПЧ-7',
            ' - СПЧ-8',
            ' - СПЧ-9',
            ' - ПЧ-10',
            ' - СПЧ-11',
            'ӨСБ - ПЧ-12',
            'МЖ - СО',
            'МӨСБ - СПЧ-14',
            'МӨСБ - СПЧ-15',
            ' - П.16',
            ' - П. 17',
        ];
        $ssv_out = FireDepartment::recommend()->get();
        $wall_materials = WallMaterial::all();
        if($card_id != 0){
            foreach ($ssv_out as $key => $item) {
                $ssv_out[$key]->res = $item->results()->where('ticket101_id', $card_id)->first();
                $ssv_out[$key]->res_many = $item->results()->where('ticket101_id', $card_id)->get();
            }
        }

        $dep_results = FireDepartmentResult::all();
        $operational_cards = OperationalCard::all();

        $this->set('wall_materials', $wall_materials);
        $this->set('operational_cards', $operational_cards);
        $this->set('ssv_out', $ssv_out);
        $this->set('dep_results', $dep_results);
        $this->set('gu_notify', $gu_notify);
        $this->set('service_notify', $service_notify);
        $this->set('city_area', CityArea::with(['fire_departments'])->get());
        $this->set('fire_object', BurntObject::all());
        $this->set('fire_levels', FireLevel::all());
        $this->set('burn_object', BurntObject::all());
        $this->set('trip_result', TripResult::all());
        $this->set('liquidation_methods', LiquidationMethod::all());
        $this->set('fire_object_options', FireObject::all());
        $this->set('operational_plans', collect(OperationalPlan::all())->map(function ($item){
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        })->toArray());
        $this->set('fire_departments', collect(FireDepartment::recommend(true)->get())->map(function ($item){
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        })->toArray());
        $this->set('trunks', Trunk::orderBy('id', 'ASC')->get());
        $ticket = Ticket101::with(['crossroad_1', 'crossroad_2', 'other_records', 'results'])->findOrNew($card_id);
        $recommendedDispatched = $ticket->results()
            ->isDispatched()
            ->recommended()
            ->count();

        $other_records_unique = $ticket->other_records()->groupBy('trunk_id')->get(['trunk_id', DB::raw('MAX(count) as count')]);
        if($other_records_unique->count()){
            $trunk_ids = $other_records_unique->pluck('trunk_id')->toArray();
            $unique_count = $other_records_unique->pluck('count')->toArray();
            $other_records_unique = Ticket101OtherRecord::whereIn('trunk_id', $trunk_ids)
                ->where('ticket101_id', $ticket->id)
                ->whereIn('count', $unique_count)
                ->get();
        }
        else{
            $other_records_unique = [];
        }

        $max_square = Ticket101OtherRecord::
            where('ticket101_id', $ticket->id)
            ->max('square');

        $fire_dep_results_info = '';
        foreach ($ticket->results()->where('dispatched', true)->get() as $item) {
            $fire_dep_results_info .= "{$item->department->name}: {$item->departments}; ";
        }

        $water_sources = WaterSupplySource::all();

        $this->set('recommendedDispatched', $recommendedDispatched);
        $this->set('fire_dep_results_info', $fire_dep_results_info);
        $this->set('water_sources', $water_sources);
        $this->set('max_square', $max_square);
        $this->set('ticket', $ticket);
        $this->set('other_records_unique', $other_records_unique);
    }

    /**
     * создаем рекомендации к выезду на основе расписания выездов ПЧ
     */
    private function recommend(Request $request, $card)
    {
        $results = [];
        $formationTechItems = [];

        $schedules = Schedule::where('fire_department_main_id', $card->fire_department_id)
            ->where('dict_fire_level_id', $card->fire_level_id)
            ->get();

        $formationTech = FormationTechReport::whereDate('created_at', Carbon::today())->get();
        foreach ($formationTech as $report) {
            foreach ($report->items()->available()->get() as $tech) {
                $formationTechItems[] = $tech;
            }
        }

        if(count($formationTechItems)){
            foreach ($formationTechItems as $tech_item) {

                $exists = FireDepartmentResult::where('ticket101_id', $card->id)
                    ->where('fire_department_id', $tech_item->formation_tech_report->dept_id)
                    ->where('tech_id', $tech_item->id)
                    ->first();

                $available = FireDepartmentResult::where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->whereNull('ret_time')
                    ->first();

                if(!$exists){
                    $results[$tech_item->department] = FireDepartmentResult::create([
                        'ticket101_id' => $card->id,
                        'fire_department_id' => $tech_item->formation_tech_report->dept_id,
                        'dispatched' => false,
                        'tech_id' => $tech_item->id,
                        'recommended' => false,
                    ]);
                }

                /**/
                foreach ($schedules as $schedule_item) {

                    $schedule_depts = explode(',', str_replace(['.', ' '], ',', $schedule_item->department));

                    foreach ($schedule_depts as $schedule_dept) {
                        if(isset($results[$schedule_dept])){
                            if($results[$schedule_dept]->fire_department_id == $schedule_item->fire_department_id){

                                $results[$schedule_dept]->recommended = true;
                                $results[$schedule_dept]->save();
                            }
                        }
                    }
                }
            }
        }

    }

    public function postAdd101(Request $request, $card_id = 0)
    {
        $data = $request->except(['ph', 'departments_to_ride']);
        $repartments_to_ride = $request->departments_to_ride;
        $r = $request->all();

        unset($data['comeback']);
        $comeback = $request->get('comeback', false);
        $otherRecords = array_get($data, 'other_records', []);
        unset($data['other_records']);

        if($request->operational_plan_id == 'NaN' || is_null($request->operational_plan_id )){
            $data['operational_plan_id'] = 0;
        }

        if($request->fire_level_id == 'NaN' || is_null($request->fire_level_id )){
            $data['fire_level_id'] = 1;
        }

        if($request->fire_department_id == 'NaN' || is_null($request->fire_department_id )){
            $data['fire_department_id'] = 0;
        }

        $card = Ticket101::findOrNew($card_id);
        $canEditTicket = $card->canEditTicket();
        if(!$canEditTicket){
            return redirect('/card/add101/')->with('_message', ['type' => 'error', 'text' => 'Данные не могут быть сохранены. Архивная карточка']);
        }

        /*если поменяли уровень пожара, новые рекомендации */
        if($card->fire_level_id !== null){


            /*todo:*/
            /* повышаем ранг*/
            if($card->fire_level_id < $request->fire_level_id){
                $card->results()->whereNull('out_time')->delete();
                $card->road_trip_plans()->where('is_accepted', false)->delete();
            }
            /* понижаем ранг*/
            elseif($card->fire_level_id > $request->fire_level_id){
                $card->results()->whereNull('out_time')->delete();
                $card->road_trip_plans()->where('is_accepted', false)->delete();
            }
        }

        $card->fill($data);
        $card->save();

        $this->saveOtherRecords($card, $otherRecords);
        $back = '/card/101';

        $this->recommend($request, $card);

        if ($comeback) {
            $back = '/card/add101/' . $card->id.'#return='.$comeback;
        }

        if($request->ajax()){
            return response()->json('ok', 200);
        }

        return redirect($back)->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
    }

    private function saveOtherRecords(Ticket101 $ticket101, array $otherRecords) {
        $ticket101->other_records()->delete();
        $ticket101->other_records()->saveMany(array_map(function ($item){
            return new Ticket101OtherRecord($item);
        }, $otherRecords));
    }
}
