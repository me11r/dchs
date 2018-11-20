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
    private function addTableData(Worksheet $sheet, $startRowIndex = 2)
    {
        $rowIndex = $startRowIndex;
        $headers = [
            'Результат выезда',
            'Объект горения',
            'Район города',
            'Адрес',
            'Спасено людей',
            'Эвакуировано людей',
            'Получили отравление угарным газом',
            'Получили отравление природным газом',
            'Получили ожоги',
            'Гибель людей',
            'Гибель детей',
            'Госпитализировано',
            'Время первым прибывшего отделения',
            'Время в пути',
            'Ликвидация',
            'Локализация',
            'Использованные стволы',
            'Время работы стволов',
            'Затраченное время на локализацию',
            'Затраченное время на ликвидацию',
        ];
        $sheet->fromArray($headers);
        $sheet->getStyle("A1:T1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);

        foreach (range('A', 'T') as $item) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }

        foreach ($this->stat as $stat) {
            $arr = [
                @$stat->trip_result->name,
                @$stat->burn_object->name,
                @$stat->city_area->name,
                $stat->location,
                $stat->rescued_count,
                $stat->evac_count,
                $stat->co2_poisoned_count,
                $stat->ch4_poisoned_count,
                $stat->gpt_burns_count,
                $stat->people_death_count,
                $stat->children_death_count,
                $stat->hospitalized_count,

                $stat->first_arrived_time,
                $stat->on_way_time,
                $stat->liqv_time,
                $stat->loc_time,
                null,
                null,
                $stat->loc_time_total,
                $stat->liqv_time_total,
            ];
            $sheet->fromArray($arr, null, "A$rowIndex");
            $sheet->getStyle("A$rowIndex:T$rowIndex")->applyFromArray(self::HStyle);
            $rowIndex++;
        }
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
}
