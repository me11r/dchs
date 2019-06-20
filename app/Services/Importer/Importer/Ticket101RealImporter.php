<?php


namespace App\Services\Importer\Importer;


use App\Chronology101;
use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\DistrictManager;
use App\EmergencyType;
use App\EventInfo;
use App\EventInfoArrived;
use App\FireDepartment;
use App\FormationReport;
use App\Models\FormationTechItem;
use App\Models\OperationalPlan;
use App\OperationalCard;
use App\RoadtripPlan;
use App\Services\ChunkedImporter\ChunkedImporter;
use App\Ticket101;
use App\Ticket101OtherHqRide;
use App\TrunkType;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\File;
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

    private $formation_report;

    private $range;

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

        $this->range = $this->range('A', 'BJ');


        ChunkedImporter::create($filePath, $this->range)
            ->each(function (Worksheet $sheet) {
                $data = $this->get($sheet->toArray());
                $this->changeEmptyToNull($data);
                $this->save($data);
            });

        return $this;
    }

    private function range($from, $to)
    {
        $result = [];

        $to = ++ $to;

        for ($i = $from; $i !== $to; $i++){
            $result[] = $i;
        }

        return $result;
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

    private function parseExcelDate($dateTime, $format = 'Y-m-d H:i')
    {
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateTime)->format($format);
        }
        catch (\Exception $e) {
            return null;
        }
    }

    public function get(array $raw_data): array
    {
        $raw_data_less = [];
        unset($raw_data[0]);

        foreach ($raw_data as $raw_datum) {

            if (!$raw_datum[0]) {
                continue;
            }

            $temp_item = $raw_datum;

            $results = $this->parseTemplate(trim($temp_item[19])); // отделения

            if ($results['type'] === 'error') {
                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => $results['message'],
                ];

                continue;
            }

            $changed_keys['location'] = trim($temp_item[0]);
            $changed_keys['fireplace'] = trim($temp_item[1]);
            $changed_keys['pre_information'] = trim($temp_item[2]);

            if (in_array(trim($temp_item[3]), (new Ticket101OtherHqRide())->getDeptNames()) || trim($temp_item[3]) !== null) {
                $changed_keys['fire_department_id'] = FireDepartment::inRandomOrder()->first()->id;
            }
            else {
                $changed_keys['fire_department_id'] = $this->getIdByName(FireDepartment::class, trim($temp_item[3]),'title'); //d
            }

            if (!$changed_keys['fire_department_id']) {

                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => "На найдена ПЧ (микроучасток) {$temp_item[3]}",
                ];

                continue;
            }

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
            $changed_keys['people_in_danger'] = trim($temp_item[16]) ? trim($temp_item[16]) : 0;
            $changed_keys['additional_description'] = trim($temp_item[17]);

            $changed_keys['custom_created_at'] = $this->parseExcelDate(trim($temp_item[18]));

            if(!$changed_keys['custom_created_at']) {

                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => "Некорректная дата создания карточки",
                ];

                continue;
            }

            $changed_keys['fire_department_results'] = $results;
            $changed_keys['chronology'] = $this->parseTemplate(trim($temp_item[20]));
            $changed_keys['chronology_hq'] = $this->parseTemplate(trim($temp_item[21]));
//            $changed_keys['special_plans'] = $this->parseTemplate(trim($temp_item[22])); //todo function
            $changed_keys['loc_time'] = $this->parseExcelDate(trim($temp_item[23]), 'H:i'); //todo parse time
            $changed_keys['liqv_time'] = $this->parseExcelDate(trim($temp_item[24]), 'H:i'); //todo parse time
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
            $changed_keys['vu_found'] = (int) trim($temp_item[37]);
            $changed_keys['animal_death'] = (int) trim($temp_item[38]);
            $changed_keys['car_crash'] = (int) trim($temp_item[39]);
            $changed_keys['rescued_count'] = (int) trim($temp_item[40]);
            $changed_keys['evac_count'] = (int) trim($temp_item[41]);
            $changed_keys['co2_poisoned_count'] = (int) trim($temp_item[42]);
            $changed_keys['ch4_poisoned_count'] = (int) trim($temp_item[43]);
            $changed_keys['gpt_burns_count'] = (int) trim($temp_item[44]);
            $changed_keys['people_death_count'] = (int) trim($temp_item[45]);
            $changed_keys['children_death_count'] = (int) trim($temp_item[46]);
            $changed_keys['hospitalized_count'] = (int) trim($temp_item[47]);
            $changed_keys['saved_vehicles'] = (int) trim($temp_item[48]);
            $changed_keys['saved_children'] = (int) trim($temp_item[49]);
            $changed_keys['bodies_extracted'] = (int) trim($temp_item[50]);
            $changed_keys['children_bodies_extracted'] = (int) trim($temp_item[51]);
            $changed_keys['medical_care_provided'] = (int) trim($temp_item[52]);
            $changed_keys['children_medical_care_provided'] = (int) trim($temp_item[53]);
            $changed_keys['children_evacuated'] = (int) trim($temp_item[54]);
            $changed_keys['ticket_result'] = trim($temp_item[55]);
            $changed_keys['special_tech'] = trim($temp_item[56]);
            $changed_keys['more_info'] = trim($temp_item[57]);
            $changed_keys['water_supply_source_id'] = $this->getIdByName(WaterSupplySource::class, trim($temp_item[58])); //d
//            $changed_keys['district_manager_id'] = $this->findDistrictManager($changed_keys); //d
            $changed_keys['distance'] = trim(str_replace(",", '.',$temp_item[60]));
            $changed_keys['owner'] = trim($temp_item[61]);

            $this->find_formation_report($changed_keys);

            $this->find_tech($changed_keys);
            $this->findDistrictManager($changed_keys); //только для боевых

            $raw_data_less[] = $changed_keys;
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
        try {
            if ($data === null) {
                return [
                    'type' => 'ok',
                    'message' => null,
                    'data' => null,
                ];
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

//            if (count($devideByDept) === 1) {
//                return [
//                    'type' => 'error',
//                    'message' => "Ошибка в шаблоне: нет символа ::",
//                    'data' => null,
//                ];
//            }

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

                /*хронология: в пути*/
                'Время' => 'time',
                'Ситуация' => 'event_info_id',
                'Информация' => 'information',

                /*хронология: на месте*/
                'Тип ствола' => 'trunk_type_id',
                'Стволы' => 'event_info_arrived_id',
                'Количество' => 'quantity',
                'Время работы' => 'working_time',
                'Расстояние подвоза' => 'water_delivery_distance',

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
                        if(count($temp) > 1 && isset($map[$temp[0]])) {
                            $devideByParam2[$map[$temp[0]]] = $temp[1];
                            $devideByParam2['fire_department_id'] = $fd;
                        }
                    }
                }
                $devideByParam3[] = $devideByParam2;
                $devideByParam2 = [];
            }

            $splittedBySemicolon = [];
            foreach ($devideByParam3 as $item) {
                if (isset($item['tech_dept_number'])) {
                    //т.к. в шаблоне номера отделений идут как попало: например, Отделение=1,2; или Отделение=1;2;
                    //заменям запятые и точки с запятыми пробелами
                    $splittedBySpace = str_replace([',', ';'], ' ', $item['tech_dept_number']);

                    //а по пробелу делим отделения
                    $explodedBySpace = explode(' ', $splittedBySpace);
                    $explodedBySpace = array_filter($explodedBySpace, function ($i) {
                        return $i !== '';
                    });


                    //очищаем значения полей от лишних запятых и точек
                    //меняем '' на null
                    foreach ($explodedBySpace as $finalTechDept) {
                        $item['tech_dept_number'] = $finalTechDept;
                        foreach ($item as $key => $cleared) {
                            $item[$key] = str_replace([';', ','], '', $cleared);

                            if (!$item[$key]) {
                                $item[$key] = null;
                            }
                        }
                        $splittedBySemicolon[] = $item;
                    }
                }
            }


            return [
                'type' => 'ok',
                'message' => null,
                'data' => count($splittedBySemicolon) ? $splittedBySemicolon : $devideByParam3,
//                'data' => $splittedBySemicolon,
            ];
        }
        catch (\Exception $e) {
            return [
                'type' => 'error',
                'message' => "Некорректный шаблон: ".$e->getMessage(),
                'data' => null,

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
            $deptHqNames = (new Ticket101OtherHqRide())->getDeptNames();

            foreach ($changed_keys['fire_department_results']['data'] as $key => $fd_result) {

                $deptTitle = trim($fd_result['fire_department_id']);

                if ($deptTitle && in_array($deptTitle, $deptHqNames)) {
                    foreach ($changed_keys['hq_rides'] as $hq_key => $hq_ride) {
                        $tempArr[$key]['out_time'] = @$hq_ride['out_time'] ? $hq_ride['out_time'] : '00:00';
                        $tempArr[$key]['name'] = @$hq_ride['fire_department_id'] ? $hq_ride['fire_department_id'] : '00:00';
                        $tempArr[$key]['arrive_time'] = @$hq_ride['arrive_time'] ? $hq_ride['arrive_time'] : '00:00';
                        $tempArr[$key]['retreat_time'] = @$hq_ride['retreat_time'] ? $hq_ride['retreat_time'] : '00:00';
                        $tempArr[$key]['ret_time'] = @$hq_ride['ret_time'] ? $hq_ride['ret_time'] : '00:00';
                        $tempArr[$key]['dispatch_time'] = @$hq_ride['dispatch_time'] ? $hq_ride['dispatch_time'] : '00:00';
                    }
                }
                else {
                    $fire_department = FireDepartment::title($deptTitle)->first();

                    if(!$fire_department) {
                        $this->incorrectItems[] = [
                            'data' => $changed_keys['location'] ." ".implode(' ', $fd_result),
                            'message' => "Не найлена ПЧ в высылке: {$fd_result['fire_department_id']}",
                        ];
                        unset($changed_keys['fire_department_results']['data'][$key]);
                        continue;
                    }

                    $formationTechItem = FormationTechItem::whereHas('formation_tech_report', function ($q) use ($fire_department) {
                        $q->where('form_id', $this->formation_report->id)
                            ->where('dept_id', $fire_department->id ?? null);
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
            }

            $changed_keys['fire_department_results'] = $tempArr;

            $tempArr = [];

            foreach ($changed_keys['chronology']['data'] ?? [] as $key => $fd_result) {

                $fire_department = FireDepartment::title(trim($fd_result['fire_department_id']))->first();

                if(!$fire_department) {
                    $this->incorrectItems[] = [
                        'data' => $changed_keys['location'] ." ".implode(' ', $fd_result),
                        'message' => "Не найлена ПЧ в хронологии: {$fd_result['fire_department_id']}",
                    ];
                    unset($changed_keys['chronology']['data'][$key]);
                    continue;
                }

                $formationTechItem = FormationTechItem::whereHas('formation_tech_report', function ($q) use ($fire_department) {
                    $q->where('form_id', $this->formation_report->id)
                        ->where('dept_id', $fire_department->id ?? null);
                })->where('status', 'action')
                    ->where('department',$fd_result['tech_dept_number'] ?? null)
                    ->first();

                $tempArr[$key]['tech_id'] = $formationTechItem->id ?? null;
                $tempArr[$key]['fire_department_id'] = $fire_department->id ?? null;
                $tempArr[$key]['ticket101_id'] = null;

                $tempArr[$key]['time'] = $fd_result['time'] ?? null;
                $tempArr[$key]['event_info_id'] = $this->getIdByName(EventInfo::class, $fd_result['accept_time'] ?? null);
                $tempArr[$key]['information'] = $fd_result['information'] ?? null;
                $tempArr[$key]['trunk_type_id'] = $this->getIdByName(TrunkType::class, $fd_result['trunk_type_id'] ?? null);
                $tempArr[$key]['event_info_arrived_id'] = $this->getIdByName(EventInfoArrived::class, $fd_result['event_info_arrived_id'] ?? null);
                $tempArr[$key]['quantity'] = $fd_result['quantity'] ??null;
                $tempArr[$key]['working_time'] = $fd_result['working_time'] ??null;
                $tempArr[$key]['water_delivery_distance'] = $fd_result['water_delivery_distance'] ?? null;
            }

            $changed_keys['chronology'] = $tempArr;

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
                    return !in_array($i, [
                        'fire_department_results',
                        'chronology',
                        'chronology_hq',
                        'special_plans',
                        ]);
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

                        if (isset($fire_department_result['name'])) {
                            $ticket->hqRides()->create([
                                'name' => $fire_department_result['name'],
                                'accept_time' => $fire_department_result['accept_time'] ?? $card['custom_created_at'],
                                'out_time' => @$fire_department_result['out_time'],
                                'retreat_time' => @$fire_department_result['retreat_time'],
                                'arrive_time' => @$fire_department_result['arrive_time'],
                                'ret_time' => @$fire_department_result['ret_time'],
                                'dispatch_time' => @$fire_department_result['out_time'],
                                'dispatched' => true,
                                'distance' => null,
                            ]);
                        }
                        else {
                            $result = $ticket->results()->create([
                                'tech_id' => $fire_department_result['tech_id'],
                                'fire_department_id' => $fire_department_result['fire_department_id'],
                                'accept_time' => $fire_department_result['accept_time'] ?? $card['custom_created_at'],
                                'out_time' => $fire_department_result['out_time'],
                                'ret_time' => $fire_department_result['ret_time'],
                                'dispatch_time' => $fire_department_result['out_time'],
                                'dispatched' => true,
                                'dispatch_id' => $roadtripPlan->id,
                            ]);

                            if ($card['chronology']) {

                                $chronoItems = collect($card['chronology'])->where('tech_id', $fire_department_result['tech_id'])->toArray();

                                foreach ($chronoItems as $chronoKey => $chronoItem) {
                                    $chronoItem['fire_department_result_id'] = $result->id;
                                    try {
                                        Chronology101::create($chronoItem);
                                    }
                                    catch (\Exception $e) {
                                        continue;
                                    }
                                }
                            }
                        }

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