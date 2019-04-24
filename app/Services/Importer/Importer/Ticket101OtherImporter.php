<?php


namespace App\Services\Importer\Importer;


use App\Services\ChunkedImporter\ChunkedImporter;
use Carbon\Carbon;
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
                $this->get($sheet->toArray());
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

    public function get(array $raw_data)
    {
//        $raw_data = $this->parseItems(database_path('seeds/sources/импорт 101.xlsx'));
//        $raw_data = $this->parseItems(database_path('seeds/sources/импорт 101 - прочие (1) (1).xlsx'));

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
                'type' => 'ok',
                'message' => null,
                'data' => $devideByParam3,
            ];
        }
        catch (\Exception $e) {
            return [
                'type' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }

    }

    private function find_tech(&$ticket)
    {

    }

    private function devide($item, $delimiter)
    {

    }
}