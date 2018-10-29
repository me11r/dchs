<?php


namespace App\Services\ReportExport;

use App\FormationTechReport;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportForcesExcelExport
{

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    /**
     * @var FormationTechReport[]
     */
    private $reports;


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

    const HStyleBold = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
        'font' => [
            'bold' => true,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'd3d3d3d3'
            ]
        ],
    ];

    /**
     * Ticket101Export constructor.
     * @param Collection $reports
     * @throws Exception
     */
    public function __construct(Collection $reports)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->reports = $reports;

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
    private function prepareSpreadsheet(): void
    {
        $sheet = $this->getActiveSheetByIndex(0);
        $sheet->setTitle('Учет сил и средств');

        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        $this->addFirstTableHeaders($sheet);
        $this->addFirstTableData($sheet);

        $this->spreadsheet->setActiveSheetIndex(0);
    }

    /**
     * @param Worksheet $sheet
     * @throws Exception
     */
    private function addFirstTableHeaders(Worksheet $sheet): void
    {
        // заголовки таблицы
        $sheet->getRowDimension(1)->setRowHeight(25);

        $this->setCell($sheet, 'ПЧ', 'A1', 'A1', self::HStyleBold);
        $this->setCell($sheet, 'Статус', 'B1', 'G1', self::HStyleBold);
    }


    /**
     * @param Worksheet $sheet
     * @param int $startRowIndex
     * @throws Exception
     */
    private function addFirstTableData(Worksheet $sheet, $startRowIndex = 2): void
    {
        $rowIndex = $startRowIndex;

        /** @var FormationTechReport $report */
        foreach ($this->reports as $report) {
            $rowIndex = $this->addReportRowForDepartment($sheet, $rowIndex, $report);
            $rowIndex++;
        }
    }

    /**
     * @param Worksheet $sheet
     * @param $rowIndex
     * @param FormationTechReport $report
     * @return float|int
     * @throws Exception
     */
    public function addReportRowForDepartment(Worksheet $sheet, $rowIndex, FormationTechReport $report)
    {
        $lastRowIndex = $rowIndex + ($report->items->count() * 2);

        // ПЧ
        $this->setCell(
            $sheet,
            $report->department->title,
            'A' . $rowIndex,
            'A' . $lastRowIndex,
            self::HStyle);

        // Статус -> заголовки
        $this->setCell($sheet, 'Отделение', 'B' . $rowIndex, 'B' . $rowIndex, self::HStyleBold);
        $this->setCell($sheet, 'Кол-во выездов за сегодня', 'C' . $rowIndex, 'C' . $rowIndex, self::HStyleBold);
        $this->setCell($sheet, 'Статус', 'D' . $rowIndex, 'G' . $rowIndex, self::HStyleBold);

        $rowIndex++;

        foreach ($report->items as $department) {
            $departmentName = $department->department ?? $department->reserve . ' резерв';
            $this->setCell($sheet, $departmentName, 'B' . $rowIndex, 'B' . ($rowIndex + 1), self::HStyle);
            $this->setCell($sheet, $department->departures_count, 'C' . $rowIndex, 'C' . ($rowIndex + 1), self::HStyle);

            if ($department->status) {
                $dataRowIndex = $rowIndex + 1;
                $this->setCell($sheet, 'Адрес', 'D' . $rowIndex, 'D' . $rowIndex, self::HStyleBold);
                $this->setCell($sheet, 'Ранг пожара', 'E' . $rowIndex, 'E' . $rowIndex, self::HStyleBold);
                $this->setCell($sheet, 'Время выезда', 'F' . $rowIndex, 'F' . $rowIndex, self::HStyleBold);
                $this->setCell($sheet, 'Время прибытия', 'G' . $rowIndex, 'G' . $rowIndex, self::HStyleBold);

                $this->setCell($sheet, $department->address, 'D' . $dataRowIndex, 'D' . $dataRowIndex, self::HStyle);
                $this->setCell($sheet, $department->fire_rank, 'E' . $dataRowIndex, 'E' . $dataRowIndex, self::HStyle);
                $this->setCell($sheet, $department->out_time, 'F' . $dataRowIndex, 'F' . $dataRowIndex, self::HStyle);
                $this->setCell($sheet, $department->arrive_time, 'G' . $dataRowIndex, 'G' . $dataRowIndex, self::HStyle);
            } else {
                $this->setCell($sheet, 'в ПЧ', 'D' . $rowIndex, 'G' . ($rowIndex + 1), self::HStyle);
            }

            $rowIndex += 2;
        }

        return $lastRowIndex;
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
