<?php


namespace App\Services\ReportExport;

use App\FireDepartment;
use App\FormationReport;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Ticket101PeriodExcelExport
{

    use CommonExportTools;

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    private $stat;

    const HStyle = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ]
    ];

    const VStyle = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
            'textRotation' => 90
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ]
    ];

    public function __construct($stat)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->stat = $stat;

        $this->prepareSpreadsheet();
    }

    /**
     * @return Xls
     */
    public function getXlsWriter(): IWriter
    {
        return new Xls($this->spreadsheet);
    }

    private function prepareSpreadsheet()
    {
        $sheet = $this->getActiveSheetByIndex(0);
        $sheet->setTitle('Отчет по карточке 101 за период');
        $this->addTableData($sheet);
    }

    /**
     * @param Worksheet $sheet
     * @param int $startRowIndex
     * @throws Exception
     */
    private function addTableData(Worksheet $sheet, $startRowIndex = 16)
    {
        $sheet->mergeCells('A5:B5');
        $this->setCell($sheet, 'Время следования', "A5", "A5", self::HStyle);

        $this->setCell($sheet, 'До 5 минут', "A6", "A6", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('on_way_category','less_5'), "B6", "B6", self::HStyle);

        $this->setCell($sheet, 'До 10 минут', "A7", "A7", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('on_way_category','less_10'), "B7", "B7", self::HStyle);

        $this->setCell($sheet, 'Более 10 минут', "A8", "A8", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('on_way_category','more_10'), "B8", "B8", self::HStyle);



        $sheet->mergeCells('D5:E5');
        $this->setCell($sheet, 'Время ликвидации', "D5", "D5", self::HStyle);

        $this->setCell($sheet, 'До 15 минут', "D6", "D6", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('liqv_category','less_15'), "E6", "E6", self::HStyle);

        $this->setCell($sheet, 'До 30 минут', "D7", "D7", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('liqv_category','less_30'), "E7", "E7", self::HStyle);

        $this->setCell($sheet, 'До 1 часа', "D8", "D8", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('liqv_category','less_60'), "E8", "E8", self::HStyle);

        $this->setCell($sheet, 'До 2 часов', "D9", "D9", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('liqv_category','less_120'), "E9", "E9", self::HStyle);

        $this->setCell($sheet, 'Более 2 часов', "D10", "D10", self::HStyle);
        $this->setCell($sheet, $this->filterByCount('liqv_category','more_120'), "E10", "E10", self::HStyle);



        $sheet->mergeCells('G5:H5');
        $this->setCell($sheet, 'Звенья ГДЗС', "G5", "G5", self::HStyle);

        $this->setCell($sheet, 'одним звеном', "G6", "G6", self::HStyle);
        $this->setCell($sheet, $this->stat->filter(function ($q) {
            return $q->gdzs_count_type == 'one';
        })->sum('gdzs_count_time'), "H6", "H6", self::HStyle);

        $this->setCell($sheet, 'двумя и более', "G7", "G7", self::HStyle);
        $this->setCell($sheet, $this->stat->filter(function ($q) {
            return $q->gdzs_count_type == 'many';
        })->sum('gdzs_count_time'), "H7", "H7", self::HStyle);

        $rowIndex = $startRowIndex;
        $headers = [
            'Дата выезда',
            'Время',
            'ФИО',
            'Телефон',
            'Район города',
            'Адрес',
            'Объект пожара',
            'Участники тушения',
            'Ликвидировано стволами',
            'Время в пути',
            'Локализация',
            'Ликвидация',
            'Время тушения',
            'Звенья ГДЗС',
            'Время работы ГДЗС',
            'Спасено людей',
            'Эвакуировано людей',
            'Травмы',
            'Гибель',
            'Затраченное время на локализацию',
            'Затраченное время на ликвидацию',
            'Результат выезда',
            'Площадь горения',
            'Этажность',
        ];
        $sheet->fromArray($headers, null, 'A15');
        $sheet->getStyle("A15:X15")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);

        foreach (range('A', 'Z') as $item) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }

        foreach ($this->stat as $stat) {
            $arr = [
                Carbon::parse($stat->custom_created_at)->format('d.m.Y'),//$stat->created_at->format('d.m.Y'),
                Carbon::parse($stat->custom_created_at)->format('H:i'),//$stat->created_at->format('H:i'),
                $stat->caller_name,
                $stat->caller_phone,
                @$stat->city_area->name,
                $stat->location,
                $stat->object_name,
                @$stat->result_fire_level->name,
                @$stat->liquidation_method->name,
                $stat->first_arrived_time,
                $stat->loc_time,
                $stat->liqv_time,
                $stat->loc_time_total,
//                $stat->chronologies_trucks()->get()->count() ? implode($stat->chronologies_trucks()->get()->map(function ($q) {
//                    if($q->event_info_arrived) {
//                        return "Тип {$q->event_info_arrived->name}; Количество: {$q->quantity}";
//                    }
//                })->toArray()) : null,
                $stat->gdzs_count,
//                $stat->chronologies_trucks()->get()->count() ? implode($stat->chronologies_trucks()->get()->map(function ($q) {
//                    if($q->event_info_arrived) {
//                        return "Тип {$q->event_info_arrived->name}; Количество: {$q->working_time}";
//                    }
//                })->toArray()) : null,
                $stat->chronologies()->get()->count() ? implode($stat->chronologies()->get()->map(function ($q) {
                    if($q->event_info_arrived) {
                        return "Тип {$q->event_info_arrived->name}; Количество: {$q->working_time}";
                    }
                })->toArray()) : null,
                $stat->rescued_count,
                $stat->evac_count,
                $stat->gpt_burns_count,
                $stat->people_death_count + $stat->children_death_count,
                $stat->loc_time_total,
                $stat->liqv_time_total,
                @$stat->trip_result->name,
                $stat->max_square,
                $stat->storey_count,
            ];

            $sheet->fromArray($arr, null, "A$rowIndex");

            $url = env('APP_URL','http://emergency.iteamsolutions.kz')."/card/add101/{$stat->id}#return=0";

            $sheet->getCell("F{$rowIndex}")->setValue($arr[5])->getHyperlink()->setUrl($url);

            $sheet->getStyle("A$rowIndex:X$rowIndex")->applyFromArray(self::HStyle);
            $rowIndex++;
        }
    }

    private function filterByCount($field, $value)
    {
        return $this->stat->filter(function ($q) use ($field, $value) {
            return $q->$field === $value;
        })->count();
    }

    /**
     * @param int $index
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function getActiveSheetByIndex(int $index): Worksheet
    {
        $this->spreadsheet->createSheet($index);
        $this->spreadsheet->setActiveSheetIndex($index);
        return $this->spreadsheet->getActiveSheet();
    }

    private function setCell(
        Worksheet $sheet,
        string $value,
        string $cell1,
        string $cell2,
        array $styles
    )
    {
        if ($cell2 && $cell2 !== $cell1) {
            $styleCoordinates = "$cell1:$cell2";
            $sheet->mergeCells("$cell1:$cell2");
        } else {
            $styleCoordinates = $cell1;
        }

        $cell = $sheet->getCell($cell1);

        if (!$cell) {
            throw new Exception("Cell '$cell1'' not found");
        }
        $cell->setValue($value);

        $sheet->getStyle($styleCoordinates)->applyFromArray($styles);

        return $cell;
    }
}
