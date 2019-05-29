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

class ReportCivilProtectionServicesWordExport
{

    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * @var array
     */
    private $data;

    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

    public function __construct(array $data)
    {
        $this->phpWord = new PhpWord();
        $this->data = $data;

        $this->prepareDocument();
    }

    private function prepareDocument()
    {
        $section = $this->getNewSection();

        $this->addFirstPageTopData($section);

        $section->addTextBreak(1, ['size' => 1]);

        $this->addFirstTable($section);

        $section->addText($this->data['record']['note']);

        $section->addPageBreak();
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
        $index = 1;
        $fontStyle = ['name' => 'Times New Roman', 'size' => 11, 'bold' => true];
        $fontStyleSimple = ['name' => 'Times New Roman', 'size' => 11];

        foreach ($this->data['blocks'] as $block) {
            $row = $table->addRow();
            $fontStyle['bold'] = true;
            $gripSpan = self::$noPaddingPS + ['gridSpan' => 6];
            $this->addDataCellToRow($row, $block['name'], [], $fontStyle, $gripSpan);

            foreach ($this->data['record']['items'] as $blockItem) {

                if ($blockItem['cp_service_block_id'] === $block['id']) {

                    $table->addRow();

                    $fontStyle['bold'] = false;
                    $table->addCell(500)->addText(htmlspecialchars($blockItem['position']),$fontStyleSimple);
                    $table->addCell(500)->addText(htmlspecialchars($blockItem['name']),$fontStyleSimple);
                    $table->addCell(500)->addText(htmlspecialchars($blockItem['contacts']),$fontStyleSimple);
                    $table->addCell(500)->addText(htmlspecialchars($blockItem['inventory1']),$fontStyleSimple);
                    $table->addCell(500)->addText(htmlspecialchars($blockItem['inventory2']),$fontStyleSimple);
                    $table->addCell(500)->addText(htmlspecialchars($blockItem['inventory3']),$fontStyleSimple);

                    $index++;
                }
            }
        }
    }

    public function export()
    {
        $writer = $this->getWriter('Word2007');
        $fileName = 'Службы ГЗ  - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));
        return response()->download(public_path($fileName));
    }

    private function addDataCellToRow(Row $row, $value, array $extraCellStyles = [], array $extraTextFStyles = [], array $extraTextPStyles = [])
    {
        $row
            ->addCell(null, array_merge(['valign' => 'center', 'align' => 'center'], $extraCellStyles))
            ->addText(
                $value,
                array_merge(['name' => 'Times New Roman', 'size' => 11], $extraTextFStyles),
                array_merge(['valign' => 'center', 'align' => 'center'], $extraTextPStyles)
            );
    }

    private function addFirstTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 11, 'valign' => Jc::CENTER, 'bold' => true];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $table->addRow(500);

        $table->addCell(2000, $cellRowSpan)->addText('Наименование', $hcFontStyle, ['align' => Jc::CENTER, 'gridSpan' => 3, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]]);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(500, $cellRowSpan)->addText('Мешкотара', $hcFontStyle, $hcAlignStyle);
        $table->addCell(500, $cellRowSpan)->addText('Песок и др.', $hcFontStyle, $hcAlignStyle);
        $table->addCell(500, $cellRowSpan)->addText('Шанцевые инструменты', $hcFontStyle, $hcAlignStyle);



    }

    private function addFirstPageTopData(Section $section)
    {
        // заголовок
        $section->addText(
            'Службы гражданской защиты г. Алматы ',
            ['name' => 'Times New Roman', 'size' => 11, 'bold' => false],
            ['align' => Jc::CENTER]
        );

        $section->addText('');
    }

    public function getWriter($name = 'Word2007')
    {
        return IOFactory::createWriter($this->phpWord, $name);
    }

    private function getNewSection()
    {
        return $this->phpWord->addSection([
//            'orientation' => 'landscape',
            'marginLeft' => 500,
            'marginRight' => 500,
            'marginTop' => 500,
            'marginBottom' => 500
        ]);
    }

}
