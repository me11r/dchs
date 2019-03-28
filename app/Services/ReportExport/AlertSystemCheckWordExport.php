<?php


namespace App\Services\ReportExport;

use App\FireDepartment;
use App\FormationOdPersonItem;
use App\FormationPersonsReport;
use App\FormationReport;
use App\GuardNumber;
use App\Models\Vehicle;
use App\OperationalGroupSchedule;
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

class AlertSystemCheckWordExport
{
    /**
     * @var PhpWord
     */
    private $phpWord;

    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];


    public function __construct(array $data)
    {
        $this->defineDomPdfWriter();

        $this->phpWord = new PhpWord();
        $this->data = $data;

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

        $section->addTextBreak(1, ['size' => 1]);

        foreach ($this->data['portions'] as $portionNum => $portion) {
            $dates = $portion->pluck('date_human_format')->toArray();

            $tableStyle = new Table;
            $tableStyle->setBorderColor('black');
            $tableStyle->setBorderSize(1);
            $tableStyle->setUnit(TblWidth::PERCENT);
            $tableStyle->setWidth(100 * 50);

            $tableStyle->setCellMargin(10);

            $table = $section->addTable($tableStyle);
            $this->addFirstTableHeaders($table, $dates);
            $this->addFirstTableData($table, $this->data['tables'][$portionNum]);

            $table->addRow(700);

            $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
            $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

            //шиирна столбца ЗПУ
            $reserveSpan = count($dates) * 3 + 2;

            $table->addCell(5000, ['gridSpan' => $reserveSpan, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('ЗПУ', $hcFontStyle, $hcAlignStyle);

            $this->addFirstTableData($table, $this->data['tables_reserved'][$portionNum]);

            $this->addBottomText($section);

        }


    }

    private function addBottomText(Section $section)
    {
        $generalFontStyle = ['name' => 'Times New Roman', 'size' => 8];
        $generalBoldFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true];
        $generalBoldItalicFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true, 'italic' => true];
        $generalBoldItalicUnderlineFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true, 'italic' => true, 'underline' => 'single'];
        $redFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true, 'color' => 'red', 'underline' => 'single'];

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Данные по работе системы оповещения передал:',
            $redFontStyle,
            array_merge(['align' => Jc::BOTH], self::$noPaddingPS)
        );

        $textRun = $section->addTextRun(self::$noPaddingPS);

        $textRun->addText("ОД ДЧС г.Алматы ", $generalFontStyle, self::$noPaddingPS);
    }

    private function addFirstTableHeaders(\PhpOffice\PhpWord\Element\Table $table, $dates)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];
        //'borderSize' => 8, 'borderColor' => '000000'

        //ширина столбца "Дата"
        $dateSpan = count($dates) * 3;

        $table->addRow(700);

        $table->addCell(900, $cellRowSpan)->addText('№п/п', $hcFontStyle, $hcAlignStyle);
        $table->addCell(900, $cellRowSpan)->addText('Направление', $hcFontStyle, $hcAlignStyle);

        $table->addCell(5000, ['gridSpan' => $dateSpan, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Дата', $hcFontStyle, $hcAlignStyle);

        $table->addRow();

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        foreach ($dates as $date) {
            $table->addCell(300, ['gridSpan' => 3, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText($date, $hcFontStyle, $hcAlignStyle);
        }

        $table->addRow();

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        foreach ($dates as $date) {
            $table->addCell(300, ['gridSpan' => 3, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText("09-30;<w:br/>15-00;<w:br/>18-00", $hcFontStyle, $hcAlignStyle);
        }

        $table->addRow();

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        foreach ($dates as $date) {
            $table->addCell(300, ['gridSpan' => 3, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText("Ответ", $hcFontStyle, $hcAlignStyle);
        }
    }

    private function addFirstTableData(\PhpOffice\PhpWord\Element\Table $table, $data)
    {
        foreach ($data as $check) {

            foreach ($check as $rows) {
                $row = $table->addRow();
                foreach ($rows as $item) {
                    $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
                    $this->addDataCellToRow($row, $item, [], $fontStyle, self::$noPaddingPS);
                }
            }
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

    // @todo PDF не работает корректно
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
