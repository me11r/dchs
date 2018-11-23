<?php


namespace App\Services\ReportExport;

use App\FireDepartment;
use App\FormationReport;
use App\Models\MudflowProtection;
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

class MudflowExcelExport
{

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    private $model;

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

    public function __construct($model_id = null)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->model = MudflowProtection::find($model_id);

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
        $sheet->setTitle('ГУ "Казселезащита"');
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
            'Бассейн реки',
            'Информация',
            'Наименование гидропостов и их отметка',
            'Расход воды',
            'Критический расход воды',
            'Мутность воды',
            'Максимальная мутность воды',
            'Температура воздуха',
            'Температура воды',
            'Осадки',
            'Высота снега',
            'Погода',
            'Комментарий',
            'Дата и время',
        ];
        $sheet->fromArray($headers);
        $sheet->getStyle("A1:M1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);

        $rivers = River::all();

        foreach (range('A', 'M') as $item) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }

        foreach ($rivers as $key => $river) {
            foreach ($river->gaugingStations as $gaugingStation) {
                $arr = [
                    ++$key,
                    $river->name,
                    $gaugingStation->mudflowProtection->information,
                    $gaugingStation->name,
                    $gaugingStation->mudflowProtection->water_flow_rate,
                    $gaugingStation->mudflowProtection->critical_water_flow_rate,
                    $gaugingStation->mudflowProtection->turbidity_of_water,
                    $gaugingStation->mudflowProtection->max_turbidity_of_water,
                    $gaugingStation->mudflowProtection->air_temperature,
                    $gaugingStation->mudflowProtection->water_temperature,
                    $gaugingStation->mudflowProtection->precipitation,
                    $gaugingStation->mudflowProtection->height_of_snow,
                    $gaugingStation->mudflowProtection->weather,
                    $gaugingStation->mudflowProtection->comment,
                    $gaugingStation->mudflowProtection->updated_at->format('d.m.Y H:i:s'),
                ];
            }

            $sheet->fromArray($arr, null, "A$rowIndex");
            $sheet->getStyle("A$rowIndex:M$rowIndex")->applyFromArray(self::HStyle);
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
