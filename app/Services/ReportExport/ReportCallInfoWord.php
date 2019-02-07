<?php


namespace App\Services\ReportExport;

use App\CallInfo;
use App\FireDepartment;
use App\FormationOdPersonItem;
use App\FormationPersonsReport;
use App\FormationReport;
use App\GuardNumber;
use App\Models\Vehicle;
use App\OperationalGroupSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Settings;

class ReportCallInfoWord
{

    /**
     * @var PhpWord
     */
    private $phpWord;

    private $data;
    private $dateFrom;
    private $dateTo;

    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

    public function __construct()
    {
        $this->defineDomPdfWriter();

        $this->phpWord = new PhpWord();
        $this->data = Cache::get('call_info_dates');
        $this->dateFrom = $this->data['dateFrom'];
        $this->dateTo = $this->data['dateTo'];
        $this->data = CallInfo::whereBetween('date', [$this->data['dateFrom'], $this->data['dateTo']])->get();

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

        $section->addTextBreak(1, ['size' => 1]);

        $this->addFirstTable($section);

//        $section->addPageBreak();

        $this->addSecondPageTopData($section);

        $this->addSecondTable($section);


    }

    private function addSecondTable(Section $section)
    {
        $tableStyle = new Table;
        $tableStyle->setBorderColor('black');
        $tableStyle->setBorderSize(1);
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $tableStyle->setCellMargin(10);

        $table = $section->addTable($tableStyle);
        $this->addSecondTableHeaders($table);
        $this->addSecondTableData($table);
    }

    private function addFirstTable(Section $section)
    {
        $tableStyle = new Table;
        $tableStyle->setBorderColor('black');
        $tableStyle->setBorderSize(1);
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $tableStyle->setCellMargin(10);

        $table = $section->addTable($tableStyle);
        $this->addFirstTableHeaders($table);
        $this->addFirstTableData($table);
    }

    private function addFirstTableData(\PhpOffice\PhpWord\Element\Table $table)
    {
        $row = $table->addRow();
        $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
        $cellStyles = ['borderLeftSize' => 10, 'borderColor' => '000000'];
        $value = [
            $this->sumByField('total112'),
            $this->sumByField('count_112'),
            $this->sumByField('count_101'),
            $this->sumByField('count_102'),
            $this->sumByField('count_103'),
            $this->sumByField('count_info'),
            $this->sumByField('count_other'),
            ''
        ];
        $this->addDataCellToRow($row, $value, $cellStyles, $fontStyle, self::$noPaddingPS);

    }

    private function addSecondTableData(\PhpOffice\PhpWord\Element\Table $table)
    {
        $row = $table->addRow();
        $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
        $cellStyles = ['borderLeftSize' => 10, 'borderColor' => '000000'];
        $value = [
            $this->sumByField('total_101'),
            $this->sumByField('count_101'),
            $this->sumByField('count_emergency'),
            $this->sumByField('count_102'),
            $this->sumByField('count_103'),
            $this->sumByField('count_info'),
            $this->sumByField('count_other'),
            ''
        ];
        $this->addDataCellToRow($row, $value, $cellStyles, $fontStyle, self::$noPaddingPS);

    }

    private function sumByField($field)
    {
        return $this->data->sum($field);
    }

    private function addDataCellToRow(Row $row, $value, array $extraCellStyles = [], array $extraTextFStyles = [], array $extraTextPStyles = [])
    {
        if(is_array($value)) {
            foreach ($value as $item) {
                $row
                    ->addCell(null, array_merge(['valign' => 'center', 'align' => 'center'], $extraCellStyles))
                    ->addText(
                        $item,
                        array_merge(['name' => 'Times New Roman', 'size' => 7], $extraTextFStyles),
                        array_merge(['valign' => 'center', 'align' => 'center'], $extraTextPStyles)
                    );
            }

        }
        else {
            $row
                ->addCell(null, array_merge(['valign' => 'center', 'align' => 'center'], $extraCellStyles))
                ->addText(
                    $value,
                    array_merge(['name' => 'Times New Roman', 'size' => 7], $extraTextFStyles),
                    array_merge(['valign' => 'center', 'align' => 'center'], $extraTextPStyles)
                );
        }

    }

    private function addFirstTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $table->addRow(700);

        $table->addCell(500, $cellRowSpan)->addText('Общее количество принятых сообщения (звонков)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(3600, ['gridSpan' => 7, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Из них:', $hcFontStyle, $hcAlignStyle);

        $table->addRow();

        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, $cellRowSpan)->addText('По основной деятельности «112»', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('По линии «101»', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('По линии «102»', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('По линии «103»', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Информационно – справочного характера', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Прочее (проверка сотовых телефонов, шалость детей и т.д.)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Примечание', $hcFontStyle, $hcAlignStyle);
    }

    private function addSecondTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $table->addRow(700);

        $table->addCell(500, $cellRowSpan)->addText('Общее количество принятых сообщений', $hcFontStyle, $hcAlignStyle);
        $table->addCell(3600, ['gridSpan' => 7, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Из них:', $hcFontStyle, $hcAlignStyle);

        $table->addRow();

        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, $cellRowSpan)->addText('По линии «101»', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('По вопросам реагирования на ЧС природного и техногенного характера', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('По линии «102»', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('По линии «103»', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Информационно – справочного характера', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Прочее (проверка сотовых телефонов, шалость детей и т.д.)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Примечание', $hcFontStyle, $hcAlignStyle);
    }



    private function addFirstPageTopData(Section $section)
    {
        // заголовок
        $section->addText("Сведения о количестве звонков поступивших на номер «112» с 07.00 {$this->dateFrom}
         до 07.00 {$this->dateTo} года УЕДДС ДЧС г. Алматы",
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('ffffff');
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $table = $section->addTable($tableStyle);
    }

    private function addSecondPageTopData(Section $section)
    {
        // заголовок
        $section->addText("Сведения о количестве звонков поступивших на номер «101» с 07.00 {$this->dateFrom}
         до 07.00 {$this->dateTo} года ГУ «СПиАСР» г. Алматы",
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('ffffff');
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $table = $section->addTable($tableStyle);
    }

    public function getWriter($name = 'Word2007')
    {
        return IOFactory::createWriter($this->phpWord, $name);
    }

    private function getNewSection()
    {
        return $this->phpWord->addSection([
            'orientation' => 'landscape',
            'marginLeft' => 500,
            'marginRight' => 500,
            'marginTop' => 500,
            'marginBottom' => 500
        ]);
    }

}
