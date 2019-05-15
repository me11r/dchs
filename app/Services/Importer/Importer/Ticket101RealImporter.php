<?php


namespace App\Services\Importer\Importer;


use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\DistrictManager;
use App\EmergencyType;
use App\FireDepartment;
use App\FormationReport;
use App\Models\FormationTechItem;
use App\Models\OperationalPlan;
use App\OperationalCard;
use App\RoadtripPlan;
use App\Services\ChunkedImporter\ChunkedImporter;
use App\Ticket101;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Ticket101RealImporter implements ImporterInterface
{
    use \App\Services\Importer\Importer\CommonImporterTrait;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array
     */
    private $incorrectItems = [];

    /**
     * @param $filePath
     * @return ImporterInterface
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadFile($filePath): ImporterInterface
    {
        $this->items = [];
        $this->incorrectItems = [];

        ChunkedImporter::create($filePath, range('A', 'I'))
            ->each(function (Worksheet $sheet) {
                $data = $this->get($sheet->toArray());
                $this->changeEmptyToNull($data);
                $this->save($data);
            });

        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function getIncorrectItems(): array
    {
        return $this->incorrectItems;
    }

    private function getIdByName($class, $search, $searchBy = 'name')
    {
        return $class::where($searchBy, $search)->first()->id ?? null;
    }

    private function changeEmptyToNull(&$data)
    {
        foreach ($data as $data_key => $record) {
            foreach ($record as $record_key => $datum) {
                if(is_array($datum)) {
                    continue;
                }
                else {
                    $data[$data_key][$record_key] = $datum !== '' ? $datum : null;
                }
            }
        }
    }

    public function get(array $raw_data)
    {
//        $raw_data = $this->parseItems(database_path('seeds/sources/импорт 101.xlsx'));
//        $raw_data = $this->parseItems(database_path('seeds/sources/импорт 101 - прочие (1) (1).xlsx'));

        $raw_data_less = [];

        foreach ($raw_data as $raw_datum) {

            $temp_item = array_slice($raw_datum, 0, 61);

            $changed_keys['location'] = trim($temp_item[0]);
            $changed_keys['fireplace'] = trim($temp_item[1]);
            $changed_keys['pre_information'] = trim($temp_item[2]);
            $changed_keys['fire_department_id'] = $this->getIdByName(FireDepartment::class, trim($temp_item[3]),'title'); //d
            $changed_keys['city_area_id'] = $this->getIdByName(CityArea::class, trim($temp_item[4])); //Район города [справочник] //d
            $changed_keys['fire_level_id'] = $this->getIdByName(FireLevel::class, trim($temp_item[5])); //Ранг пожара [справочник] //d
            $changed_keys['storey_count'] = trim($temp_item[6]);
            $changed_keys['floor'] = trim($temp_item[7]);
            $changed_keys['caller_name'] = trim($temp_item[8]);
            $changed_keys['caller_phone'] = trim($temp_item[9]);
            $changed_keys['object_name'] = trim($temp_item[10]);
            $changed_keys['operational_plan_id'] = $this->getIdByName(OperationalPlan::class, trim($temp_item[11])) ?? 0; //d
            $changed_keys['operational_card_id'] = $this->getIdByName(OperationalCard::class, trim($temp_item[12]),'oc_number'); //d
            $changed_keys['building_description'] = trim($temp_item[13]);
            $changed_keys['year_of_development'] = trim($temp_item[14]);
            $changed_keys['building_square'] = trim($temp_item[15]);
            $changed_keys['people_in_danger'] = trim($temp_item[16]);
            $changed_keys['additional_description'] = trim($temp_item[17]);

            $changed_keys['custom_created_at'] = $this->parseDate(trim($temp_item[18]));
            $changed_keys['fire_department_results'] = $this->parseTemplate(trim($temp_item[19]));
            $changed_keys['chronology'] = $this->parseTemplate(trim($temp_item[20]));
            $changed_keys['chronology_hq'] = $this->parseTemplate(trim($temp_item[21]));
            $changed_keys['special_plans'] = $this->parseTemplate(trim($temp_item[22]));
            $changed_keys['loc_time'] = trim($temp_item[23]);
            $changed_keys['liqv_time'] = trim($temp_item[24]);
            $changed_keys['emergency_type_id'] = $this->getIdByName(EmergencyType::class, trim($temp_item[25])); //d
            $changed_keys['kui'] = trim($temp_item[26]);
            $changed_keys['out_number'] = trim($temp_item[27]);
            $changed_keys['detailed_address'] = trim($temp_item[28]);
            $changed_keys['burn_object_id'] = $this->getIdByName(BurntObject::class, trim($temp_item[29])); //d
            $changed_keys['living_sector_type_id'] = $this->getIdByName(LiquidationMethod::class, trim($temp_item[30])); //d
            $changed_keys['trip_result_id'] = $this->getIdByName(TripResult::class, trim($temp_item[31])); //d
            $changed_keys['liquidation_method_id'] = $this->getIdByName(LiquidationMethod::class, trim($temp_item[32])); //d
            $changed_keys['result_fire_level_id'] = $this->getIdByName(FireLevel::class, trim($temp_item[35])); //d
            $changed_keys['max_square'] = trim($temp_item[36]);
            $changed_keys['vu_found'] = trim($temp_item[37]);
            $changed_keys['animal_death'] = trim($temp_item[38]);
            $changed_keys['car_crash'] = trim($temp_item[39]);
            $changed_keys['rescued_count'] = trim($temp_item[40]);
            $changed_keys['evac_count'] = trim($temp_item[41]);
            $changed_keys['co2_poisoned_count'] = trim($temp_item[42]);
            $changed_keys['ch4_poisoned_count'] = trim($temp_item[43]);
            $changed_keys['gpt_burns_count'] = trim($temp_item[44]);
            $changed_keys['people_death_count'] = trim($temp_item[45]);
            $changed_keys['children_death_count'] = trim($temp_item[46]);
            $changed_keys['hospitalized_count'] = trim($temp_item[47]);
            $changed_keys['saved_vehicles'] = trim($temp_item[48]);
            $changed_keys['saved_children'] = trim($temp_item[49]);
            $changed_keys['bodies_extracted'] = trim($temp_item[50]);
            $changed_keys['children_bodies_extracted'] = trim($temp_item[51]);
            $changed_keys['medical_care_provided'] = trim($temp_item[52]);
            $changed_keys['children_medical_care_provided'] = trim($temp_item[53]);
            $changed_keys['children_evacuated'] = trim($temp_item[54]);
            $changed_keys['ticket_result'] = trim($temp_item[55]);
            $changed_keys['special_tech'] = trim($temp_item[56]);
            $changed_keys['more_info'] = trim($temp_item[57]);
            $changed_keys['water_supply_source_id'] = $this->getIdByName(WaterSupplySource::class, trim($temp_item[58])); //d
            $changed_keys['district_manager_id'] = $this->findDistrictManager($changed_keys); //d
            $changed_keys['distance'] = trim(str_replace(",", '.',$temp_item[60]));
            $changed_keys['owner'] = trim($temp_item[61]);

            if(!$changed_keys['custom_created_at']) {
                continue;
            }

            $this->find_tech($changed_keys);

            $raw_data_less[] = $changed_keys;
        }

        foreach ($raw_data_less as $key => $item) {
            $fire_dep_main_id = \App\FireDepartment::title($item['fire_department_main_id'])->first();
            $fire_dep_id = \App\FireDepartment::title($item['fire_department_id'])->first();
            $level = \App\Dictionary\FireLevel::name($item['dict_fire_level_id'])->first();

            if($fire_dep_id && $fire_dep_main_id){
                $raw_data_less[$key]['fire_department_id'] = $fire_dep_id->id;
                $raw_data_less[$key]['fire_department_main_id'] = $fire_dep_main_id->id;
                $raw_data_less[$key]['dict_fire_level_id'] = $level->id;

            }
            else{
                unset($raw_data_less[$key]);
            }
        }

        return $raw_data_less;
    }

    private function findDistrictManager(&$data)
    {
        $cityAreaId = $data['city_area_id'];
        $date = $data['formation_report_id'] ? FormationReport::find($data['formation_report_id'])->report_date : null;

        $data['district_manager_id'] = DistrictManager::getDailyPerson($cityAreaId, $date)->id ?? null;
    }

    private function parseDate($date, $format = 'Y-m-d H:i')
    {
        try {
           return Carbon::parse($date)->format($format);
        }
        catch (\Exception $e) {
            return null;
        }
    }

    public function parseTemplate($data)
    {
//        $data = "ПЧ-2::[Отделение=7|Принято в работу=11:25|Время выезда=11:25|Время прибытия=11:25|Время отбоя=|Время возвращения=11:25|Время оповещения=11:25|Время ввода в боевой расчет=|Количество привлеченного л/с=15|Расстояние до места=5];ПЧ-3::[Отделение=4|Принято в работу=11:25|Время выезда=11:25|Время прибытия=11:25|Время отбоя=|Время возвращения=11:25|Время оповещения=11:25|Время ввода в боевой расчет=|Количество привлеченного л/с=15|Расстояние до места=5];ПЧ-3::[Отделение=5|Принято в работу=11:25|Время выезда=11:25|Время прибытия=11:25|Время отбоя=|Время возвращения=11:25|Время оповещения=11:25|Время ввода в боевой расчет=|Количество привлеченного л/с=15|Расстояние до места=5];";
//        $data = "ПЧ-6::[Время выезда=10:30|Время возвращения=19:00|";

        try {
            if($data === null) {
                return [];
            }

            //отделяем блоки с ПЧ по ;
            $devideByFd = explode(';',$data);

            $devideByDept = [];
            $devideByParam = [];
            $devideByParam2 = [];
            $devideByParam3 = [];

            foreach ($devideByFd as $item) {
                if($item !== '' && $item !== null) {
                    $devideByDept[] = explode('::',$item);
                }
            }

            if (count($devideByDept) < 2) {
                return [
                    'type' => 'error',
                    'message' => "Ошибка в шаблоне: нет символа ::",
                ];
            }

            $map = [
                'Отделение' => 'tech_dept_number',
                'Принято в работу' => 'accept_time',
                'Время выезда' => 'out_time',
                'Время прибытия' => 'arrive_time',
                'Время отбоя' => 'retreat_time',
                'Время возвращения' => 'ret_time',
                'Время оповещения' => 'dispatch_time',
                'Время ввода в боевой расчет' => 'promoted_at',
                'Количество привлеченного л/с' => 'staff_count',
                'Расстояние до места' => 'distance',
            ];

            foreach ($devideByDept as $item) {
                $temp = str_replace(['[',']'], '', $item);
                $devideByParam[] = [$temp[0] => $temp[1]];
            }

            foreach ($devideByParam as $items) {
                foreach ($items as $fd => $item) {
                    $rawParams = explode('|',$item);
                    foreach ($rawParams as $rawParam) {
                        $temp = explode('=',$rawParam);
                        if(count($temp) > 1) {
                            $devideByParam2[$map[$temp[0]]] = $temp[1];
                            $devideByParam2['fire_department_id'] = $fd;
                        }
                    }
                }
                $devideByParam3[] = $devideByParam2;
                $devideByParam2 = [];
            }

            return [
                'type' => 'error',
                'message' => $devideByParam3,
            ];
        }
        catch (\Exception $e) {
            return [
                'type' => 'error',
                'message' => $e->getMessage(),
            ];
        }

    }

    private function find_formation_report(&$changed_keys)
    {
        $date = $this->parseDate($changed_keys['custom_created_at'], 'Y-m-d');
        $report = FormationReport::where('report_date', $date)->first();

        $this->formation_report = $report;

        $changed_keys['formation_report_id'] = $report->id ?? null;
    }

    private function find_tech(&$changed_keys)
    {
        $tempArr = [];
        if ($this->formation_report) {
            foreach ($changed_keys['fire_department_results']['data'] as $key => $fd_result) {

                $fire_department = FireDepartment::title($fd_result['fire_department_id'])->first();

                $formationTechItem = FormationTechItem::whereHas('formation_tech_report', function ($q) {
                    $q->where('form_id', $this->formation_report->id);
                })->where('status', 'action')
                    ->where('department',$fd_result['tech_dept_number'] ?? null)
                    ->first();

                $tempArr[$key]['tech_id'] = $formationTechItem->id ?? null;
                $tempArr[$key]['fire_department_id'] = $fire_department->id ?? null;
                $tempArr[$key]['ticket101_id'] = null;

                $tempArr[$key]['accept_time'] = $fd_result['accept_time'] ?? null;
                $tempArr[$key]['out_time'] = $fd_result['out_time'] ?? null;
                $tempArr[$key]['arrive_time'] = $fd_result['arrive_time'] ?? null;
                $tempArr[$key]['retreat_time'] = $fd_result['retreat_time'] ?? null;
                $tempArr[$key]['ret_time'] = $fd_result['ret_time'] ?? null;
                $tempArr[$key]['dispatch_time'] = $fd_result['dispatch_time'] ?? null;
                $tempArr[$key]['staff_count'] = $fd_result['staff_count'] ?? null;
                $tempArr[$key]['promoted_at'] = $fd_result['promoted_at'] ?? null;
                $tempArr[$key]['distance'] = $fd_result['distance'] ?? null;
            }

            $changed_keys['fire_department_results'] = $tempArr;

        }
        else {

            unset($changed_keys['fire_department_results']);
            unset($changed_keys['chronology']);
            unset($changed_keys['chronology_hq']);
            unset($changed_keys['special_plans']);

            $this->incorrectItems[] = [
                'data' => implode(" ", $changed_keys),
                'message' => "Не найдена строевая записка",
            ];
        }
    }

    private function save($cards)
    {
        foreach ($cards as $card) {

            try {
                $ticket = new Ticket101();

                $cardData = array_filter($card, function ($q, $i) {
                    return $i !== 'fire_department_results';
                }, ARRAY_FILTER_USE_BOTH);

                $cardData['imported_at'] = now();

                $ticket->fill($cardData);

                $ticket->save();

                if(isset($card['fire_department_results']) && $card['fire_department_results']) {
                    foreach ($card['fire_department_results'] as $fire_department_result) {

                        $rideType = $ticket->ride_type->name ?? null;

                        $errorString = "{$card['location']} {$rideType} " . implode(' ', $fire_department_result);

                        if (!$fire_department_result['fire_department_id']) {

                            $this->incorrectItems[] = [
                                'data' => $errorString,
                                'message' => "Не найлена ПЧ",
                            ];

                            continue;
                        }

                        if (!$fire_department_result['tech_id']) {

                            $this->incorrectItems[] = [
                                'data' => $errorString,
                                'message' => "Не найлено отделение ПЧ",
                            ];

                            continue;
                        }

                        $roadtripPlan = RoadtripPlan::firstOrCreate([
                            'department_id' => $fire_department_result['fire_department_id'],
                            'card_id' => $ticket->id,
                        ],[
                            'department_id' => $fire_department_result['fire_department_id'],
                            'card_id' => $ticket->id,
                            'is_closed' => false,
                            'is_accepted' => true,
                            'printed' => true,
                        ]);

                        $ticket->results()->create([
                            'tech_id' => $fire_department_result['tech_id'],
                            'fire_department_id' => $fire_department_result['fire_department_id'],
                            'out_time' => $fire_department_result['out_time'],
                            'ret_time' => $fire_department_result['ret_time'],
                            'dispatch_time' => $fire_department_result['out_time'],
                            'dispatched' => true,
                            'dispatch_id' => $roadtripPlan->id,
                        ]);
                    }
                }

                $this->items[] = $card;
            }
            catch (\Exception $e) {

                unset($card['fire_department_results']);
                unset($card['chronology']);
                unset($card['chronology_hq']);
                unset($card['special_plans']);

                $this->incorrectItems[] = [
                    'data' => implode(" ", $card),
                    'message' => $e->getMessage() . ' ' . $e->getLine(),
                ];
            }
        }
    }
}