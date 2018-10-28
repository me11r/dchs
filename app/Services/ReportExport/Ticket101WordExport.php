<?php


namespace App\Services\ReportExport;

use App\FireDepartment;
use App\FormationReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Settings;

class Ticket101WordExport
{

    use CommonExportTools;

    /**
     * @var PhpWord
     */
    private $phpWord;

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


    public function __construct(
        FormationReport $formationReport,
        Collection $departments,
        Collection $people,
        Collection $tech,
        array $sumPeople
    )
    {
        $this->defineDomPdfWriter();

        $this->phpWord = new PhpWord();
        $this->formationReport = $formationReport;
        $this->departments = $departments;
        $this->people = $people;
        $this->tech = $tech;
        $this->sumPeople = $sumPeople;

        $this->prepareDocument();
    }

    private function defineDomPdfWriter()
    {
        $domPdfPath = realpath(base_path() . '/vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');
    }

    private function prepareDocument()
    {
        $section = $this->getNewSection();

        $this->addFirstPageTopData($section);

        $this->addFirstTable($section);

        $section->addPageBreak();

        $this->addSecondTable($section);
    }

    private function addSecondTable(Section $section)
    {
        $tableStyle = new Table;
        $tableStyle->setBorderColor('cccccc');
        $tableStyle->setBorderSize(1);
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $table = $section->addTable($tableStyle);
        $this->addSecondTableHeaders($table);
        $this->addSecondTableData($table);
    }

    private function addSecondTableData(\PhpOffice\PhpWord\Element\Table $table)
    {
        // все департаменты
        /** @var FireDepartment $department */
        foreach ($this->departments as $department) {
            if ($department->id !== 13) { // суеверия
                $row = $table->addRow();
                foreach ($this->getSecondTableRowForDepartment($department) as $value) {
                    $this->addDataCellToRow($row, $value);
                }
            }
        }

        // сумма
        $row = $table->addRow();
        foreach ($this->getSecondTableSumRow() as $value) {
            $this->addDataCellToRow($row, $value, [], ['bold' => true]);
        }
    }

    private function addSecondTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpanV = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanH = ['vMerge' => 'restart', 'valign' => Jc::CENTER];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER];

        $table->addRow(700);
        $table->addCell(null, $cellRowSpanV)->addText('Наименование пожарных подразделений', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 17, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Имеется на автомобилях в боевом расчете', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Пенообразователя на складе', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 3, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Количество неисправных водоисточников', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('В боевом расчете', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('В резерве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText("1 генератор\n2 дымосос\n3 гирсы,  ИУП", $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanH)->addText('Ф.И.О Начальника караула или лица его подменяющего', $hcFontStyle, $hcAlignStyle);

        $table->addRow(1250);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, ['gridSpan' => 4, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Рукавов', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Лафетн. Ств. стац', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Лафетн. Ств. переносной', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('ГПС-600', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Пурга', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Переносная радиостанция', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Электрофонари', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Прожектора', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('ТОК/Л-1', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Ранцевые аппараты', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Лопаты', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Хлопушки', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Спасательные веревки', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Пенообразователя', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('ПГ', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('ПВ', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('бензин', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('солярка', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('бензин', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('солярка', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addRow(1250);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowSpanV)->addText('125мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('75мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('77мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('51мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowSpanV)->addText('уличный', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('объектовый', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
    }

    private function addFirstTable(Section $section)
    {
        $tableStyle = new Table;
        $tableStyle->setBorderColor('cccccc');
        $tableStyle->setBorderSize(1);
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $table = $section->addTable($tableStyle);
        $this->addFirstTableHeaders($table);
        $this->addFirstTableData($table);
    }

    private function addFirstTableData(\PhpOffice\PhpWord\Element\Table $table)
    {
        // все департаменты
        /** @var FireDepartment $department */
        foreach ($this->departments as $department) {
            if ($department->id !== 13) { // суеверия
                $row = $table->addRow();
                foreach ($this->getFirstTableRowForDepartment($department) as $value) {
                    $this->addDataCellToRow($row, $value);
                }
            }
        }

        // сумма
        $row = $table->addRow();
        foreach ($this->getFirstTableSumRow() as $value) {
            $this->addDataCellToRow($row, $value, [], ['bold' => true]);
        }
    }

    private function addDataCellToRow(Row $row, $value, array $extraCellStyles = [], array $extraTextFStyles = [], array $extraTextPStyles = [])
    {
        $row
            ->addCell(null, array_merge(['valign' => 'center', 'align' => 'center'], $extraCellStyles))
            ->addText(
                $value,
                array_merge(['name' => 'Times New Roman', 'size' => 7], $extraTextFStyles),
                array_merge(['valign' => 'center', 'align' => 'center'], $extraTextPStyles)
            );
    }

    private function addFirstTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER];

        $table->addRow();

        $table->addCell(null, $cellRowSpan)->addText('Наименование пожарных подразделений', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('В карауле по списку л/с', $hcFontStyle, $hcAlignStyle);

        $table->addCell(null, ['gridSpan' => 6, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('На лицо личного состава', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 6, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Отсутствуют', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('ГДЗС', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Аппараты', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Мотопомпы', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 6, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Пожарная техника', $hcFontStyle, $hcAlignStyle);

        $table->addRow();

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, $cellRowSpan)->addText('Всего', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Нач.кар', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Ком.отд', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Шоферы', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Ряд.состав', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Ряд.Диспетчеров', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Отпуск', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Учебный', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Декрет', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Больные', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Командировка', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Др.причины', $hcFontStyle, $hcAlignStyle);

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('В боевом расчёте', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('В резерве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('На ремонте', $hcFontStyle, $hcAlignStyle);

        $table->addRow(1000);

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, $cellRowSpan)->addText('Тип осн. пожарного а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Марка', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Тип осн. а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Марка', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Тип осн. а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Марка', $hcFontStyle, $hcAlignStyle);
    }

    private function addFirstPageTopData(Section $section)
    {
        // заголовок
        $section->addText(
            'Строевая записка на ' . Carbon::parse($this->formationReport->created_at)->format('d-m-Y') . 'г.',
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('ffffff');
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $table = $section->addTable($tableStyle);

        $headCellFontStyle = ['name' => 'Times New Roman', 'size' => 9, 'bold' => true];

        $row = $table->addRow();
        $row->addCell()->addText('ДСПТ:', $headCellFontStyle); // @TODO добавить значение
        $row->addCell()->addText('ЕДДС:', $headCellFontStyle); // @TODO добавить значение
        $row->addCell()->addText('Ст. мастер связи:', $headCellFontStyle); // @TODO добавить значение

        $row = $table->addRow();
        $row->addCell()->addText('ЦППС:', $headCellFontStyle); // @TODO добавить значение
        $row->addCell()->addText('ИПЛ:', $headCellFontStyle); // @TODO добавить значение
        $row->addCell()->addText('Водоканал:', $headCellFontStyle); // @TODO добавить значение

    }

    // @todo PDF не работает корректно
    public function getWriter($name = 'Word2007')
    {
        return IOFactory::createWriter($this->phpWord, $name);
    }

    private function getNewSection()
    {
        return $this->phpWord->addSection([
            'orientation' => 'landscape',
            'marginLeft' => 200,
            'marginRight' => 200,
            'marginTop' => 200,
            'marginBottom' => 200
        ]);
    }

}
