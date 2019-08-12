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

class Ticket112PeriodExcelExport
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

    const HStyleRight = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_GENERAL,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
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
        $sheet->setTitle('Отчет по карточке 112 за период');
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
            'Происшествие',
            'Район города',
            'Кол-во происшествий(ЧС)',
            'Пострадавших людей/детей',
            'Погибших людей/детей',
            'Эвакуированных людей/детей',
            'Госпитализированных людей/детей',
            'Травмированных людей/детей',
            'Отравление людей/детей',
            'Спасено людей/детей',
            'Спасено животных',
        ];
        $sheet->fromArray($headers);
        $sheet->getStyle("A1:K1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);

        foreach (range('A', 'K') as $item) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }

        foreach ($this->stat as $incident_type => $stat) {
            if($incident_type !== 'Итог') {
                foreach ($stat as $citAreaTitle => $cityArea) {
                    $arr = [
                        $incident_type,
                        $citAreaTitle,
                        $cityArea['total'],
                        $cityArea['injured'],
                        $cityArea['dead'],
                        $cityArea['evacuated'],
                        $cityArea['hospitalized'],
                        $cityArea['injured_hard'],
                        $cityArea['poisoned'],
                        $cityArea['saved'],
                        $cityArea['saved_animals'],
                    ];
                    $sheet->fromArray($arr, null, "A$rowIndex");
                    $sheet->getStyle("A$rowIndex:B$rowIndex")->applyFromArray(self::HStyleRight);
                    $sheet->getStyle("A$rowIndex:K$rowIndex")->applyFromArray(self::HStyleRight);
                    $rowIndex++;
                }
            }
        }

        $arr = [
            'Итог',
            '',
            $this->stat['Итог']['total'],
            $this->stat['Итог']['injured'],
            $this->stat['Итог']['dead'],
            $this->stat['Итог']['evacuated'],
            $this->stat['Итог']['hospitalized'],
            $this->stat['Итог']['injured_hard'],
            $this->stat['Итог']['poisoned'],
            $this->stat['Итог']['saved'],
            $this->stat['Итог']['saved_animals'],
        ];
        $sheet->fromArray($arr, null, "A$rowIndex");
        $sheet->getStyle("A$rowIndex:B$rowIndex")->applyFromArray(self::HStyleRight);
        $sheet->getStyle("A$rowIndex:K$rowIndex")->applyFromArray(self::HStyleRight);
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
