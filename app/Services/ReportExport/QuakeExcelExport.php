<?php


namespace App\Services\ReportExport;

use App\FireDepartment;
use App\FormationReport;
use App\Models\MudflowProtection;
use App\Models\Quake;
use App\Models\River;
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

class QuakeExcelExport
{

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    private $models;

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

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->models = Quake::orderBy('date_almaty', 'DESC')->get();

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
        $sheet->setTitle('ТОО СОМЭ');
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
            '№',
            'Описание',
            'Дата и время Алматинского времени',
            'Дата и время по Гринвичу',
            'Эпицентр землетрясения',
            'Энергетический класс землетрясения',
            'Магнитуда MPV',
            'Координаты эпицентра',
            'Глубина',
            'Сведения об ощутимости',
        ];
        $sheet->fromArray($headers);
        $sheet->getStyle("A1:J1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);

        foreach (range('A', 'J') as $item) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }

        $key = 0;
        foreach ($this->models as $item) {
            $arr = [
                ++$key,
                $item->description,
                $item->date_almaty,
                $item->date_greenwich,
                $item->epicenter,
                $item->energy_class,
                $item->mpv,
                $item->coordinates,
                $item->deep,
                $item->information,
            ];

            $sheet->fromArray($arr, null, "A$rowIndex");
            $sheet->getStyle("A$rowIndex:J$rowIndex")->applyFromArray(self::HStyle);
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
