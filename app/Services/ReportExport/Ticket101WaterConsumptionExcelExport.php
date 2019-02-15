<?php


namespace App\Services\ReportExport;

use App\FireDepartment;
use App\FormationReport;
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

class Ticket101WaterConsumptionExcelExport
{

    private $spreadsheet;

    /**
     * @var array
     */
    private $data;

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

    /**
     * Ticket101Export constructor.
     * @param FormationReport $formationReport
     * @param Collection $departments
     * @param Collection $people
     * @param Collection $tech
     * @param array $sumPeople
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->data = $data;

        $this->prepareSpreadsheet();
    }

    /**
     * @return Xls
     */
    public function getXlsWriter(): IWriter
    {
        return new Xls($this->spreadsheet);
    }

    /**
     * @throws Exception
     */
    private function prepareSpreadsheet()
    {
        $sheet = $this->getActiveSheetByIndex(0);
        $sheet->setTitle('Расход воды');
        $this->addFirstTableHeaders($sheet);
        $this->addFirstTableData($sheet);

        $this->spreadsheet->setActiveSheetIndex(0);
    }

    /**
     * @param Worksheet $sheet
     * @throws Exception
     */
    private function addFirstTableHeaders(Worksheet $sheet)
    {
        // заголовки таблицы
        $sheet->getCell('B1')
            ->setValue("Расход воды за {$this->data['dateFrom']} по {$this->data['dateTo']}");
        $sheet->mergeCells('B1:I1');
        $sheet->getRowDimension(1)->setRowHeight(50);
        $sheet->getRowDimension(2)->setRowHeight(100);

        $letters = range('A', 'Z');

        foreach ($this->data['headers'] as $index => $header) {
            $sheet->getColumnDimension($letters[$index])->setWidth(20);
            $this->setCell($sheet, $header, "$letters[$index]2", "$letters[$index]2", self::HStyle);
        }

    }

    /**
     * @param Worksheet $sheet
     * @param int $startRowIndex
     * @throws Exception
     */
    private function addFirstTableData(Worksheet $sheet, $startRowIndex = 3)
    {
        $rowIndex = $startRowIndex;

        foreach ($this->data['values'] as $rowVal) {

            $sheet->fromArray($rowVal, null, "A$rowIndex");
            $sheet->getStyle("A$rowIndex:I$rowIndex")->applyFromArray(self::HStyle);
            $sheet->getRowDimension($rowIndex)->setRowHeight(50);
            $rowIndex++;

        }
    }

    /**
     * @param Worksheet $sheet
     * @param string $value
     * @param string $cell1
     * @param string $cell2
     * @param array $styles
     * @return null|\PhpOffice\PhpSpreadsheet\Cell\Cell
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
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
