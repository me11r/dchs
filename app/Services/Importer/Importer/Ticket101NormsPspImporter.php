<?php


namespace App\Services\Importer\Importer;


use App\FireDepartment;
use App\FormationReport;
use App\Models\FormationTechItem;
use App\NormNumber;
use App\NormPsp;
use App\NormType;
use App\RideType;
use App\RoadtripPlan;
use App\Services\ChunkedImporter\ChunkedImporter;
use App\Ticket101Other;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Ticket101NormsPspImporter implements ImporterInterface
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

        ChunkedImporter::create($filePath, range('A', 'J'))
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

            $results = $this->parseTemplate(trim($temp_item[7])); // отделения

            if ($results['type'] === 'error') {
                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => $results['message'],
                ];

                continue;
            }

            $changed_keys['date'] = $this->parseDate(trim($temp_item[0])); //Дата  [дд-мм-гггг]
            $changed_keys['time_begin'] = trim($temp_item[1]); //Время начала [чч:мм]
            $changed_keys['time_end'] = trim($temp_item[2]); //Время окончания [чч:мм]
            $changed_keys['fire_department_id'] = $this->getIdByName(FireDepartment::class, trim($temp_item[3]), 'title'); //Подразделение [справочник]
            $changed_keys['norm_number_id'] = $this->getIdByName(NormNumber::class, trim($temp_item[4]), 'name'); //№ норматива [справочник]
            $changed_keys['gdzs_included_30'] = (bool) trim($temp_item[5]); //С включением ГДЗС (30 минут) [логика: 1/0]
            $changed_keys['norm_type_id'] = $this->getIdByName(NormType::class, trim($temp_item[6]), 'name'); //Тип норматива [справочник]
            $changed_keys['departments'] = $results; //Тип норматива [справочник]
            $changed_keys['responsible_person'] = trim($temp_item[8]); //Ответственное лицо
            $changed_keys['note'] = trim($temp_item[9]); //Примечание

            if(!$changed_keys['date']) {

                $this->incorrectItems[] = [
                    'data' => implode(" ", $temp_item),
                    'message' => "Некорректная дата",
                ];

                continue;
            }

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

            $departments = explode(",", $data);

            $departments = array_map(function ($q) {
               return trim($q);
            }, $departments);

            return [
                'type' => 'ok',
                'message' => null,
                'data' => $departments,
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

    private function getIdByName($class, $search, $searchBy = 'name')
    {
        return $class::where($searchBy, $search)->first()->id ?? null;
    }

    private function save($cards)
    {
        foreach ($cards as $card) {

            try {
                $ticket = NormPsp::create([
                    'date' => $card['date'],
                    'time_begin' => $card['time_begin'],
                    'time_end' => $card['time_end'],
                    'fire_department_id' => $card['fire_department_id'],
                    'norm_number_id' => $card['norm_number_id'],
                    'norm_type_id' => $card['norm_type_id'],
                    'responsible_person' => $card['responsible_person'],
                    'note' => $card['note'],
                    'gdzs_included_30' => $card['gdzs_included_30'],
                ]);

                $this->items[] = $card;
            }
            catch (\Exception $e) {

                $this->incorrectItems[] = [
                    'data' => implode(" ", $card),
                    'message' => $e->getMessage() . ' ' . $e->getLine(),
                ];
            }

        }
    }
}