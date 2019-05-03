<?php


namespace App\Services\Importer\Importer;


use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\DistrictManager;
use App\DrillType;
use App\FireDepartment;
use App\FormationReport;
use App\LivingSectorType;
use App\Models\FormationTechItem;
use App\Models\OperationalPlan;
use App\Models\SpecialPlan;
use App\RideType;
use App\RoadtripPlan;
use App\Services\ChunkedImporter\ChunkedImporter;
use App\Ticket101;
use App\Ticket101Other;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Ticket101DrillImporter implements ImporterInterface
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

        $this->range = $this->range('A', 'BO');

        ChunkedImporter::create($filePath, $this->range)
            ->each(function (Worksheet $sheet) {
                $data = $this->get($sheet->toArray());
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

    public function get(array $raw_data): array
    {
        $raw_data_less = [];

        unset($raw_data[0]);

        foreach ($raw_data as $raw_datum) {

            $temp_item = $raw_datum;

            $results = $this->parseTemplate(trim($temp_item[19])); // отделения

            if ($results['type'] === 'error') {
                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => $results['message'],
                ];

                continue;
            }

            $changed_keys['location'] = trim($temp_item[0]); //Адрес
            $changed_keys['fireplace'] = trim($temp_item[1]); //Место пожара
            $changed_keys['pre_information'] = trim($temp_item[2]); //Предварительная информация
            $changed_keys['fire_department_id'] = trim($temp_item[3]); //Микроучасток [справочник] //d
            $changed_keys['city_area_id'] = $this->getIdByName(CityArea::class, trim($temp_item[4])); //Район города [справочник] //d
            $changed_keys['fire_level_id'] = $this->getIdByName(FireLevel::class, trim($temp_item[5])); //Ранг пожара [справочник] //d
            $changed_keys['storey_count'] = trim($temp_item[6]); //Этажность
            $changed_keys['floor'] = trim($temp_item[7]); // На каком этаже пожар
            $changed_keys['caller_name'] = trim($temp_item[8]);
            $changed_keys['responsible_person'] = trim($temp_item[8]);
            $changed_keys['drill_type_id'] = $this->getIdByName(DrillType::class, trim($temp_item[9])); //d
            $changed_keys['caller_phone'] = null;
            $changed_keys['object_name'] = trim($temp_item[10]);
            $changed_keys['operational_plan_id'] = $this->getIdByName(OperationalPlan::class, trim($temp_item[11])); //d
            $changed_keys['operational_card_id'] = $this->getIdByName(OperationalPlan::class, trim($temp_item[12], 'oc_number')); //d
            $changed_keys['building_description'] = trim($temp_item[13]);
            $changed_keys['year_of_development'] = trim($temp_item[14]);
            $changed_keys['building_square'] = trim($temp_item[15]);
            $changed_keys['people_in_danger'] = trim($temp_item[16]);
            $changed_keys['additional_description'] = trim($temp_item[17]);

            $changed_keys['custom_created_at'] = $this->parseDate(trim($temp_item[18]));

            if(!$changed_keys['custom_created_at']) {

                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => "Некорректная дата создания карточки",
                ];

                continue;
            }

            $changed_keys['fire_department_results'] = $this->parseTemplate(trim($temp_item[19]));

            $changed_keys['chronology'] = $this->parseTemplate(trim($temp_item[20]));
            $changed_keys['chronology_hq'] = $this->parseTemplate(trim($temp_item[21]));
            $changed_keys['special_plans'] = $this->parseTemplate(trim($temp_item[22]));

            $changed_keys['loc_time'] = trim($temp_item[23]);
            $changed_keys['liqv_time'] = trim($temp_item[24]);

            $changed_keys['drill_begin'] = trim($temp_item[23]);
            $changed_keys['drill_end'] = trim($temp_item[24]);

            $changed_keys['emergency_type_id'] = null;//trim($temp_item[25]); //d
            $changed_keys['drill_type_total'] = trim($temp_item[26]);
            $changed_keys['kui'] = null;//trim($temp_item[26]);
            $changed_keys['out_number'] = null;//trim($temp_item[27]);
            $changed_keys['detailed_address'] = null;//trim($temp_item[28]);
            $changed_keys['drill_name_total'] = trim($temp_item[27]);
            $changed_keys['drill_address_total'] = trim($temp_item[28]);
            $changed_keys['drill_checked_pg_total'] = trim($temp_item[29]);
            $changed_keys['drill_checked_pv_total'] = trim($temp_item[30]);
            $changed_keys['drill_out_pg_total'] = trim($temp_item[31]);
            $changed_keys['drill_out_pv_total'] = trim($temp_item[32]);
            $changed_keys['drill_corrected_op_total'] = trim($temp_item[33]);
            $changed_keys['drill_corrected_ok_total'] = trim($temp_item[34]);
            $changed_keys['object_classification_id'] = trim($temp_item[35]);
            $changed_keys['living_sector_type_id'] = $this->getIdByName(LivingSectorType::class, trim($temp_item[36])); //d
            $changed_keys['trip_result_id'] = $this->getIdByName(TripResult::class, trim($temp_item[37])); //d
            $changed_keys['burn_object_id'] = $this->getIdByName(BurntObject::class, null);//trim($temp_item[29]); //d
            $changed_keys['liquidation_method_id'] = $this->getIdByName(LiquidationMethod::class, trim($temp_item[38])); //d
            $changed_keys['result_fire_level_id'] = $this->getIdByName(FireLevel::class, null);//trim($temp_item[35]); //d
            $changed_keys['max_square'] = null;

            $changed_keys['vu_found'] = trim($temp_item[43]);
            $changed_keys['animal_death'] = trim($temp_item[44]);
            $changed_keys['car_crash'] = trim($temp_item[45]);
            $changed_keys['rescued_count'] = trim($temp_item[46]);
            $changed_keys['evac_count'] = trim($temp_item[47]);
            $changed_keys['co2_poisoned_count'] = trim($temp_item[48]);
            $changed_keys['ch4_poisoned_count'] = trim($temp_item[49]);
            $changed_keys['gpt_burns_count'] = trim($temp_item[50]);
            $changed_keys['people_death_count'] = trim($temp_item[51]);
            $changed_keys['children_death_count'] = trim($temp_item[52]);
            $changed_keys['hospitalized_count'] = trim($temp_item[53]);
            $changed_keys['saved_vehicles'] = trim($temp_item[54]);
            $changed_keys['saved_children'] = trim($temp_item[55]);
            $changed_keys['bodies_extracted'] = trim($temp_item[56]);
            $changed_keys['children_bodies_extracted'] = trim($temp_item[57]);
            $changed_keys['medical_care_provided'] = trim($temp_item[58]);
            $changed_keys['children_medical_care_provided'] = trim($temp_item[59]);
            $changed_keys['children_evacuated'] = trim($temp_item[60]);

            $changed_keys['ticket_result'] = trim($temp_item[61]);
            $changed_keys['special_tech'] = trim($temp_item[62]);
            $changed_keys['more_info'] = trim($temp_item[63]);
            $changed_keys['water_supply_source_id'] = $this->getIdByName(WaterSupplySource::class, trim($temp_item[64])); //d
            $changed_keys['district_manager_id'] = null; //только для боевых
            $changed_keys['distance'] = trim(str_replace(",", '.',$temp_item[66]));
            $changed_keys['owner'] = trim($temp_item[8]);

            $this->find_formation_report($changed_keys);
            $this->find_tech($changed_keys);
            //$this->findDistrictManager($changed_keys); //только для боевых

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

            //$data = "ПЧ-5::[Отделение=7|Время выезда=11:25|Время возвращения=11:25];";

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

            if (count($devideByDept[0] ?? []) < 2) {
                return [
                    'type' => 'error',
                    'message' => "Ошибка в шаблоне: нет символа ::",
                    'data' => null,
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
                        $temp = explode('=',trim($rawParam));
                        if(count($temp) > 1) {
                            $devideByParam2[trim($map[$temp[0]])] = trim($temp[1]);
                            $devideByParam2['fire_department_id'] = $fd;
                        }
                    }
                }
                $devideByParam3[] = $devideByParam2;
                $devideByParam2 = [];
            }

            return [
                'type' => 'ok',
                'message' => null,
                'data' => $devideByParam3,
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
        if ($this->formation_report) {
            foreach ($changed_keys['fire_department_results'] as $key => $fd_result) {

                $fire_department = FireDepartment::title($fd_result['fire_department_id'])->first();

                $formationTechItem = FormationTechItem::whereHas('formation_tech_report', function ($q) {
                    $q->where('form_id', $this->formation_report->id);
                })->where('status', 'action')
                    ->where('department',$fd_result['tech_dept_number'] ?? null)
                    ->first();

                $changed_keys['fire_department_results'][$key]['tech_id'] = $formationTechItem->id ?? null;
                $changed_keys['fire_department_results'][$key]['fire_department_id'] = $fire_department->id ?? null;
                $changed_keys['fire_department_results'][$key]['ticket101_other_id'] = null;
            }
        }
        else {

            unset($changed_keys['fire_department_results']);

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

                $ticket->fill([
                    'ride_type_id' => $card['ride_type_id'],
                    'time_begin' => $card['time_begin'],
                    'time_end' => $card['time_end'],
                    'object_name' => $card['object_name'],
                    'note' => $card['note'],
                    'formation_report_id' => $this->formation_report->id,
                    'responsible_person' => $card['responsible_person'],
                    'direction' => $card['direction'],
                    'final_ride_type_id' => $card['ride_type_id'],
                    'final_responsible_person' => $card['responsible_person'],
                    'final_direction' => $card['direction'],
                    'final_object_name' => $card['object_name'],
                    'custom_created_at' => $card['custom_created_at'],
                    'imported_at' => now(),
                ]);

                $ticket->save();

                if($card['fire_department_results']) {
                    foreach ($card['fire_department_results'] as $fire_department_result) {

                        $rideType = $ticket->ride_type->name ?? null;

                        $errorString = "{$card['direction']} {$rideType} " . implode(' ', $fire_department_result);

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
                            'card101_id' => $ticket->id,
                        ],[
                            'department_id' => $fire_department_result['fire_department_id'],
                            'card101_id' => $ticket->id,
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

                $this->incorrectItems[] = [
                    'data' => implode(" ", $card),
                    'message' => $e->getMessage() . ' ' . $e->getLine(),
                ];
            }

        }
    }
}