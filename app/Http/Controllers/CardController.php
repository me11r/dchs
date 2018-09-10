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
use App\Models\FireDepartmentResult;
use App\Models\OperationalPlan;
use App\Models\Schedule;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\Trunk;
use App\Models\WallMaterial;
use App\Ticket101;
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
        $ssv_out = FireDepartment::all();
        $wall_materials = WallMaterial::all();
        if($card_id != 0){
            foreach ($ssv_out as $key => $item) {
                $ssv_out[$key]->res = $item->results()->where('ticket101_id', $card_id)->first();
            }
        }

        $dep_results = FireDepartmentResult::all();

        $this->set('wall_materials', $wall_materials);
        $this->set('ssv_out', $ssv_out);
        $this->set('dep_results', $dep_results);
        $this->set('gu_notify', $gu_notify);
        $this->set('service_notify', $service_notify);
        $this->set('city_area', CityArea::all());
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
        $this->set('fire_departments', collect(FireDepartment::all())->map(function ($item){
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        })->toArray());
        $this->set('trunks', Trunk::orderBy('id', 'ASC')->get());
        $ticket = Ticket101::with(['crossroad_1', 'crossroad_2', 'other_records'])->findOrNew($card_id);

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

        $this->set('fire_dep_results_info', $fire_dep_results_info);
        $this->set('water_sources', $water_sources);
        $this->set('max_square', $max_square);
        $this->set('ticket', $ticket);
        $this->set('other_records_unique', $other_records_unique);
    }

    public function postAdd101(Request $request, $card_id = 0)
    {
        $data = $request->except('ph');

        unset($data['comeback']);
        $comeback = $request->get('comeback', false);
        $otherRecords = array_get($data, 'other_records', []);
        unset($data['other_records']);

        $card = Ticket101::findOrNew($card_id);
        $canEditTicket = $card->canEditTicket();
        if(!$canEditTicket){
            return redirect('/card/add101/')->with('_message', ['type' => 'error', 'text' => 'Данные не могут быть сохранены. Архивная карточка']);
        }
        $card->fill($data);
        $card->save();

        $this->saveOtherRecords($card, $otherRecords);
        $back = '/card/101';

        $schedule = Schedule::where('fire_department_main_id', $card->fire_department_id)
            ->where('dict_fire_level_id', $data['fire_level_id'])
            ->get();

        $results_exists = FireDepartmentResult::where('ticket101_id', $card->id)
            ->get()
            ->count();

        if(!$results_exists && $schedule->count()){
            foreach ($schedule as $item) {
                FireDepartmentResult::create([
                    'ticket101_id' => $card->id,
                    'fire_department_id' => $item->fire_department_id,
                    'dispatched' => false,
                    'departments' => $item->department,
                ]);
            }
        }
        else{
            $results_req = $request->ph;
            foreach ($results_req['ot'] as $control_id => $control) {
                if($control){
                    FireDepartmentResult::updateOrCreate(
                        [
                            'ticket101_id' => $card->id,
                            'fire_department_id' => $control_id,
                        ],
                        [
                            'ticket101_id' => $card->id,
                            'fire_department_id' => $control_id,
                            'departments' => $control,

                            'out_time' => $request->input("ph.out_time.$control_id"),
                            'arrive_time' => $request->input("ph.arrive_time.$control_id"),
                            'loc_time' => $request->input("ph.loc_time.$control_id"),
                            'liqv_time' => $request->input("ph.liqv_time.$control_id"),
                            'ret_time' => $request->input("ph.ret_time.$control_id"),
                        ]
                    );
                }
            }
        }

        if ($comeback) {
            $back = '/card/add101/' . $card->id.'#return='.$comeback;
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
