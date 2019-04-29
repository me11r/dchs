<?php


namespace App\Services\Importer\Importer;


use App\FireDepartment;
use App\FormationReport;
use App\Models\FormationTechItem;
use App\RideType;
use App\RoadtripPlan;
use App\Services\ChunkedImporter\ChunkedImporter;
use App\Ticket101Other;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Ticket101OtherImporter implements ImporterInterface
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

    public function get(array $raw_data): array
    {
        $raw_data_less = [];

        unset($raw_data[0]);

        foreach ($raw_data as $raw_datum) {

            $temp_item = array_slice($raw_datum, 0, 61);

            $results = $this->parseTemplate(trim($temp_item[4])); // отделения

            if ($results['type'] === 'error') {
                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => $results['message'],
                ];

                continue;
            }

            $changed_keys['direction'] = trim($temp_item[0]); //Адрес
            $changed_keys['ride_type_id'] = trim($temp_item[1]); //Наименование /d
            $changed_keys['responsible_person'] = trim($temp_item[2]); //Ответственное лицо
            $changed_keys['object_name'] = trim($temp_item[3]); //Наименование объекта
            $changed_keys['fire_department_results'] = $results['data']; // отделения
            $changed_keys['custom_created_at'] = $this->parseDate(trim($temp_item[5])); //дата и время создания
            $changed_keys['time_begin'] = trim($temp_item[6]); //время начала
            $changed_keys['time_end'] = trim($temp_item[7]); //время окончания
            $changed_keys['note'] = trim($temp_item[8]); //время окончания

            if(!$changed_keys['custom_created_at']) {

                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => "Некорректная дата создания карточки",
                ];

                continue;
            }

            $changed_keys['ride_type_id'] = RideType::name($changed_keys['ride_type_id'])->first()->id ?? null;

            $this->find_formation_report($changed_keys);
            $this->find_tech($changed_keys);

            $raw_data_less[] = $changed_keys;
        }

        return $raw_data_less;
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
        if($this->formation_report) {
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
                $ticket = Ticket101Other::create([
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
                    'created_by' => Auth::id(),
                    'changed_by' => Auth::id(),
                    'imported_at' => now(),
                ]);

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
                            'card101_other_id' => $ticket->id,
                        ],[
                            'department_id' => $fire_department_result['fire_department_id'],
                            'card101_other_id' => $ticket->id,
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