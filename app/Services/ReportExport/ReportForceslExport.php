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

class ReportForceslExport
{

    use CommonExportTools;

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    /**
     * @var FormationReport
     */
    private $formationReport;

    /**
     * @var Collection
     */
    private $departments;

    /**
     * @var Collection
     */
    private $people;

    /**
     * @var Collection
     */
    private $tech;

    /**
     * @var array
     */
    private $sumPeople;

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
     * @throws Exception
     */
    public function __construct(
        FormationReport $formationReport,
        Collection $departments,
        Collection $people,
        Collection $tech,
        array $sumPeople
    )
    {
        $this->spreadsheet = new Spreadsheet();
        $this->formationReport = $formationReport;
        $this->departments = $departments;
        $this->people = $people;
        $this->tech = $tech;
        $this->sumPeople = $sumPeople;

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
     * @return Dompdf
     */
    public function getPdfWriter(): IWriter
    {
        $writer = new Tcpdf($this->spreadsheet);
        $writer->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)->setPaperSize(PageSetup::PAPERSIZE_A2_PAPER); // @TODO выводятся не все листы + толстые границы ячеек
        return $writer;
    }

    /**
     * @throws Exception
     */
    private function prepareSpreadsheet()
    {
        $sheet = $this->getActiveSheetByIndex(0);
        $sheet->setTitle('Учет сил и средств');
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
        $sheet->getRowDimension(6)->setRowHeight(25);
        $sheet->getRowDimension(7)->setRowHeight(25);
        $sheet->getRowDimension(8)->setRowHeight(25);
        $sheet->getRowDimension(9)->setRowHeight(50);

        $this->setCell($sheet, 'Наименование пожарных подразделений', 'A6', 'A9', self::VStyle);

        $this->setCell($sheet, 'В карауле по списку л/с', 'B6', 'B9', self::VStyle);

        $this->setCell($sheet, 'На лицо личного состава', 'C6', 'H6', self::HStyle);
        $this->setCell($sheet, 'Всего', 'C7', 'C9', self::VStyle);
        $this->setCell($sheet, 'Нач. караулов', 'D7', 'D9', self::VStyle);
        $this->setCell($sheet, 'Ком. отделений', 'E7', 'E9', self::VStyle);
        $this->setCell($sheet, 'Шоферы', 'F7', 'F9', self::VStyle);
        $this->setCell($sheet, 'Ряд. состав', 'G7', 'G9', self::VStyle);
        $this->setCell($sheet, 'Диспетчеров', 'H7', 'H9', self::VStyle);

        $this->setCell($sheet, 'Отсутствуют', 'I6', 'N6', self::HStyle);
        $this->setCell($sheet, 'Отпуск', 'I7', 'I9', self::VStyle);
        $this->setCell($sheet, 'Учебный', 'J7', 'J9', self::VStyle);
        $this->setCell($sheet, 'Декрет', 'K7', 'K9', self::VStyle);
        $this->setCell($sheet, 'Больные', 'L7', 'L9', self::VStyle);
        $this->setCell($sheet, 'Командировка', 'M7', 'M9', self::VStyle);
        $this->setCell($sheet, 'Др. причины', 'N7', 'N9', self::VStyle);

        $this->setCell($sheet, 'ГДЗС', 'O6', 'O9', self::VStyle);

        $this->setCell($sheet, 'Аппараты', 'P6', 'P9', self::VStyle);
        $this->setCell($sheet, "Мотопомпы\nВодяная/Грязевая", 'Q6', 'Q9', self::VStyle);


        $this->setCell($sheet, 'Пожарная техника', 'R6', 'W6', self::HStyle);

        $this->setCell($sheet, 'В боевом расчёте', 'R7', 'S7', self::HStyle);
        $this->setCell($sheet, 'Тип основ пожарного а/м', 'R8', 'R9', self::VStyle);
        $this->setCell($sheet, 'Марка спец. пожарного а/м Мотоциклы', 'S8', 'S9', self::VStyle);

        $this->setCell($sheet, 'В резерве', 'T7', 'U7', self::HStyle);
        $this->setCell($sheet, 'Тип основ пожарного а/м', 'T8', 'T9', self::VStyle);
        $this->setCell($sheet, 'Марка спец. пожарных а/м', 'U8', 'U9', self::VStyle);

        $this->setCell($sheet, 'На ремонте', 'V7', 'W7', self::HStyle);
        $this->setCell($sheet, 'Тип основ пожарного а/м', 'V8', 'V9', self::VStyle);
        $this->setCell($sheet, 'Марка спец. пожарных а/м', 'W8', 'W9', self::VStyle);


        $sheet->getColumnDimension('R')->setWidth(20);
        $sheet->getColumnDimension('S')->setWidth(20);
        $sheet->getColumnDimension('T')->setWidth(20);
        $sheet->getColumnDimension('U')->setWidth(20);
        $sheet->getColumnDimension('V')->setWidth(20);
        $sheet->getColumnDimension('W')->setWidth(20);
    }

    /**
     * @param Worksheet $sheet
     * @param int $startRowIndex
     * @throws Exception
     */
    private function addFirstTableData(Worksheet $sheet, $startRowIndex = 10)
    {
        $rowIndex = $startRowIndex;

        // все департаменты
        /** @var FireDepartment $department */
        foreach ($this->departments as $department) {
            $sheet->fromArray($this->getFirstTableRowForDepartment($department), null, "A$rowIndex");
            $sheet->getStyle("A$rowIndex:W$rowIndex")->applyFromArray(self::HStyle);
            $sheet->getRowDimension($rowIndex)->setRowHeight(-1);
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
