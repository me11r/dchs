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

class Ticket101ExcelExport
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
    public function __construct(
        FormationReport $formationReport,
        Collection $departments,
        Collection $people,
        Collection $tech,
        array $sumPeople,
        array $data
    )
    {
        $this->spreadsheet = new Spreadsheet();
        $this->formationReport = $formationReport;
        $this->departments = $departments;
        $this->people = $people;
        $this->tech = $tech;
        $this->sumPeople = $sumPeople;
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
        $sheet->setTitle('Личный состав, техника');
        $this->addFirstTableTopRows($sheet);
        $this->addFirstTableHeaders($sheet);
        $this->addFirstTableData($sheet);

        $sheet = $this->getActiveSheetByIndex(1);
        $sheet->setTitle('Оборудование');
        $this->addSecondTableHeaders($sheet);
        $this->addSecondTableData($sheet);

        $this->spreadsheet->setActiveSheetIndex(0);
    }

    /**
     * @param Worksheet $sheet
     * @param int $startRowIndex
     * @throws Exception
     */
    private function addSecondTableData(Worksheet $sheet, $startRowIndex = 4)
    {
        $rowIndex = $startRowIndex;
        // все департаменты
        /** @var FireDepartment $department */
        foreach ($this->departments as $department) {
            if ($department->id !== 13) { // суеверия
                $sheet->fromArray($this->getSecondTableRowForDepartment($department), null, "A$rowIndex");
                $sheet->getStyle("A$rowIndex:AB$rowIndex")->applyFromArray(self::HStyle);
                $sheet->getRowDimension($rowIndex)->setRowHeight(-1);
                $rowIndex++;
            }
        }

        // строка суммы
        $sheet->fromArray($this->getSecondTableSumRow(), null, "A$rowIndex");
        $sheet->getStyle("A$rowIndex:AB$rowIndex")->applyFromArray(self::HStyle)->getFont()->setBold(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(-1);

    }

    /**
     * @param Worksheet $sheet
     * @throws Exception
     */
    private function addSecondTableHeaders(Worksheet $sheet)
    {
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->getRowDimension(2)->setRowHeight(60);
        $sheet->getRowDimension(3)->setRowHeight(65);

        $this->setCell($sheet, 'Наименование пожарных подразделений', 'A1', 'A3', self::VStyle);

        $this->setCell($sheet, 'Имеется на автомобилях в боевом расчете', 'B1', 'R1', self::HStyle);

        $this->setCell($sheet, 'Рукавов', 'B2', 'E2', self::HStyle);
        $this->setCell($sheet, '125мм', 'B3', 'B3', self::VStyle);
        $this->setCell($sheet, '75мм', 'C3', 'C3', self::VStyle);
        $this->setCell($sheet, '77мм', 'D3', 'D3', self::VStyle);
        $this->setCell($sheet, '51мм', 'E3', 'E3', self::VStyle);

        $this->setCell($sheet, 'Лафетн. Ств. стац', 'F2', 'F3', self::VStyle);
        $this->setCell($sheet, 'Лафетн. Ств. переносной', 'G2', 'G3', self::VStyle);
        $this->setCell($sheet, 'ГПС-600', 'H2', 'H3', self::VStyle);
        $this->setCell($sheet, 'Пурга', 'I2', 'I3', self::VStyle);
        $this->setCell($sheet, 'Переносная радиостанция', 'J2', 'J3', self::VStyle);
        $this->setCell($sheet, 'Электрофонари', 'K2', 'K3', self::VStyle);
        $this->setCell($sheet, 'Прожектора', 'L2', 'L3', self::VStyle);
        $this->setCell($sheet, 'ТОК/Л-1', 'M2', 'M3', self::VStyle);
        $this->setCell($sheet, 'Ранцевые аппараты', 'N2', 'N3', self::VStyle);
        $this->setCell($sheet, 'Лопаты', 'O2', 'O3', self::VStyle);
        $this->setCell($sheet, 'Хлопушки', 'P2', 'P3', self::VStyle);
        $this->setCell($sheet, 'Спасательные веревки', 'Q2', 'Q3', self::VStyle);
        $this->setCell($sheet, 'Пенообразователя', 'R2', 'R3', self::VStyle);

        $this->setCell($sheet, 'Пенообразователя на складе', 'S1', 'S3', self::VStyle);

        $this->setCell($sheet, 'Количество неисправных водоисточников', 'T1', 'V1', self::HStyle);
        $this->setCell($sheet, 'ПГ', 'T2', 'U2', self::HStyle);
        $this->setCell($sheet, 'уличный', 'T3', 'T3', self::VStyle);
        $this->setCell($sheet, 'объектовый', 'U3', 'U3', self::VStyle);
        $this->setCell($sheet, 'ПВ', 'V2', 'V3', self::HStyle);

        $this->setCell($sheet, 'в боевом расчете', 'W1', 'X1', self::HStyle);
        $this->setCell($sheet, 'бензин', 'W2', 'W3', self::VStyle);
        $this->setCell($sheet, 'солярка', 'X2', 'X3', self::VStyle);

        $this->setCell($sheet, 'в резерве', 'Y1', 'Z1', self::HStyle);
        $this->setCell($sheet, 'бензин', 'Y2', 'Y3', self::VStyle);
        $this->setCell($sheet, 'солярка', 'Z2', 'Z3', self::VStyle);

        $this->setCell($sheet, "1 генератор\n2 дымосос\n3 гирсы,  ИУП", 'AA1', 'AA3', self::VStyle);

        $this->setCell($sheet, 'Ф.И.О Начальника караула или лица его подменяющего', 'AB1', 'AB3', self::VStyle);

        $sheet->getColumnDimension('AB')->setWidth(15);
        $sheet->getColumnDimension('AB')->setWidth(20);
    }

    /**
     * @param Worksheet $sheet
     * @throws Exception
     */
    private function addFirstTableTopRows(Worksheet $sheet)
    {
        $operGroup = $this->getOperGroupName();
        // заголовок первого листа
        $sheet
            ->getCell('G1')
            ->setValue('Строевая записка на ' . Carbon::parse($this->formationReport->created_at)->format('d.m.Y') . 'г. '.$operGroup);
        $sheet->mergeCells('G1:J1');

        // аббревиатуры
        $sheet->getCell('A3')->setValue('ДСПТ:');
        $sheet->getCell('B3')->setValue(''); // @TODO добавить значение
        $sheet->getCell('A4')->setValue('ЦППС:');
        $sheet->getCell('B4')->setValue(''); // @TODO добавить значение

        $sheet->getCell('G3')->setValue('ЕДДС:');
        $sheet->getCell('H3')->setValue(''); // @TODO добавить значение
        $sheet->getCell('G4')->setValue('ИПЛ:');
        $sheet->getCell('H4')->setValue(''); // @TODO добавить значение

        $sheet->getCell('L3')->setValue('Ст. мастер связи:');
        $sheet->getCell('M3')->setValue(''); // @TODO добавить значение
        $sheet->getCell('L4')->setValue('Водоканал:');
        $sheet->getCell('M4')->setValue(''); // @TODO добавить значение

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

        $this->setCell($sheet, 'ГДЗС', 'O6', 'P6', self::HStyle);

        $this->setCell($sheet, 'Газодымозащитники', 'O7', 'O9', self::VStyle);
        $this->setCell($sheet, 'АСВ/ДАСК', 'P7', 'P9', self::VStyle);

        $this->setCell($sheet, "Мотопомпы\nВодяная/Грязевая", 'Q6', 'Q9', self::VStyle);


        $this->setCell($sheet, 'Пожарная техника', 'R6', 'W6', self::HStyle);

        $this->setCell($sheet, 'В боевом расчёте', 'R7', 'S7', self::HStyle);
        $this->setCell($sheet, 'Тип основ пожарного а/м', 'R8', 'R9', self::VStyle);
        $this->setCell($sheet, 'Марка спец. пожарного а/м Мотоциклы', 'T8', 'T9', self::VStyle);

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
        $sheet->getColumnDimension('X')->setWidth(20);
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
            if ($department->id !== 13) { // суеверия
                $sheet->fromArray($this->getFirstTableRowForDepartment($department), null, "A$rowIndex");
                $sheet->getStyle("A$rowIndex:W$rowIndex")->applyFromArray(self::HStyle);
                $sheet->getRowDimension($rowIndex)->setRowHeight(-1);
                $rowIndex++;
            }
        }

        // строка суммы
        $sheet->fromArray($this->getFirstTableSumRow(), null, "A$rowIndex");
        $sheet->getStyle("A$rowIndex:W$rowIndex")->applyFromArray(self::HStyle)->getFont()->setBold(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(-1);
        $rowIndex++;

        // 13 департамент
        $department13 = $this->departments->where('id', '=', 13)->first();
        $sheet->fromArray($this->getFirstTableRowForDepartment($department13), null, "A$rowIndex");
        $sheet->getStyle("A$rowIndex:W$rowIndex")->applyFromArray(self::HStyle);
        $sheet->getRowDimension($rowIndex)->setRowHeight(-1);
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
