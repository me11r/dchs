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
        $this->setCell($sheet, $this->stat['totals']['on_way_totals']['less_5'], "B6", "B6", self::HStyle);

        $this->setCell($sheet, 'До 10 минут', "A7", "A7", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['on_way_totals']['less_10'], "B7", "B7", self::HStyle);

        $this->setCell($sheet, 'Более 10 минут', "A8", "A8", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['on_way_totals']['more_10'], "B8", "B8", self::HStyle);


        $sheet->mergeCells('D5:E5');
        $this->setCell($sheet, 'Время ликвидации', "D5", "D5", self::HStyle);

        $this->setCell($sheet, 'До 15 минут', "D6", "D6", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_15'], "E6", "E6", self::HStyle);

        $this->setCell($sheet, 'До 30 минут', "D7", "D7", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_30'], "E7", "E7", self::HStyle);

        $this->setCell($sheet, 'До 1 часа', "D8", "D8", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_60'], "E8", "E8", self::HStyle);

        $this->setCell($sheet, 'До 2 часов', "D9", "D9", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_120'], "E9", "E9", self::HStyle);

        $this->setCell($sheet, 'Более 2 часов', "D10", "D10", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['more_120'], "E10", "E10", self::HStyle);


        $sheet->mergeCells('G5:H5');
        $this->setCell($sheet, 'Звенья ГДЗС', "G5", "G5", self::HStyle);

        $this->setCell($sheet, 'одним звеном', "G6", "G6", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['elements_of_gdzs']['one'], "H6", "H6", self::HStyle);

        $this->setCell($sheet, 'двумя и более', "G7", "G7", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['elements_of_gdzs']['many'], "H7", "H7", self::HStyle);

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

        foreach ($this->stat['items'] as $item) {
            $arr = [
                $item->custom_created_at_date,
                $item->custom_created_at_hours,
                $item->caller_name,
                $item->caller_phone,
                $item->city_area_name,
                $item->location,
                $item->object_name,
                $item->result_fire_level_name,
                $item->liquidation_method_name,
                $item->on_way_time,
                $item->loc_time,
                $item->liqv_time,
                $item->loc_time_total,
                $item->gdzs_count,
                $item->event_info_arrived_names,
                $item->rescued_count,
                $item->evac_count,
                $item->gpt_burns_count,
                $item->total_death_count,
                $item->loc_time_total,
                $item->liqv_time_total,
                $item->trip_result_name,
                $item->max_square,
                $item->storey_count
            ];

            $sheet->fromArray($arr, null, "A$rowIndex");

            $url = env('APP_URL','http://emergency.iteamsolutions.kz')."/card/add101/{$item->id}#return=0";

            $sheet->getCell("F{$rowIndex}")->setValue($arr[5])->getHyperlink()->setUrl($url);

            $sheet->getStyle("A$rowIndex:X$rowIndex")->applyFromArray(self::HStyle);
            $rowIndex++;
        }

        $totalCellAddress = "A" . ($rowIndex + 2);
        $this->setCell($sheet, 'Общее количество выездов: ' . $this->stat['items']->count(), $totalCellAddress, $totalCellAddress, self::HStyle);
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
