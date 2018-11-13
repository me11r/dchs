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

class Ticket101ChronologyExcelExport
{

    use CommonExportTools;

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    private $card;

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

    public function __construct($card_id)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->card = Ticket101::find($card_id);

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
        $sheet->setTitle('Хронология');
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
            'ПЧ',
            'Отделение',
            'Время',
            'Количество',
            'Время работы',
            'Ситуация',
            'Информация',
        ];
        $sheet->fromArray($headers);
        $sheet->getStyle("A1:G1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);


//        $sheet->getColumnDimension('F')->setWidth(14);
        foreach (range('A', 'G') as $item) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }

        foreach ($this->card->chronologies as $chronology) {
            $event_info = $chronology->event_info ? $chronology->event_info->name : $chronology->event_info_arrived->name;
            $arr = [
                @$chronology->fire_department_result->department->title,
                @$chronology->fire_department_result->tech->department,
                $chronology->time,
                $chronology->quantity,
                $chronology->working_time,
                $event_info,
                $chronology->information,
            ];
            $sheet->fromArray($arr, null, "A$rowIndex");
            $sheet->getStyle("A$rowIndex:G$rowIndex")->applyFromArray(self::HStyle);
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
