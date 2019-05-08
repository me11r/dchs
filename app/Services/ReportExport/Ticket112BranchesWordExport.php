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

class Ticket112BranchesWordExport
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

        $this->addTopData($section);

        $section->addTextBreak(1, ['size' => 1]);

        foreach ($this->data['data'] as $cityAreaTitle => $areaItems) {

            if (count($areaItems)) {
                $headers = array_keys($areaItems[0]);

                $section->addText("{$cityAreaTitle} - " .count($areaItems),
                    ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
                    ['align' => Jc::START]);

                $this->addTable($section, $headers, $areaItems);
            }
            else {
                continue;
            }
        }


        $section->addPageBreak();

        // пострадавшие/погибшие
        $section->addText('Общее количество происшествий  - '.$this->data['total'],
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::START]);

        $section->addText('Пострадавшие/погибшие - '.$this->data['totalInjured'],
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::END]);
    }

    private function addTable(Section $section, $headers, $data)
    {
        $tableStyle = new Table;
        $tableStyle->setBorderColor('black');
        $tableStyle->setBorderSize(1);
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $tableStyle->setCellMargin(10);

        $table = $section->addTable($tableStyle);
        $this->addHeaders($table, $headers);
        $this->addTableData($table, $data);
    }

    private function addTableData(\PhpOffice\PhpWord\Element\Table $table, $data)
    {
        foreach ($data as $rowVal) {
            $row = $table->addRow();
            foreach ($rowVal as $key => $value) {
                $fontStyle = ['name' => 'Times New Roman', 'size' => 8];

                $this->addDataCellToRow($row, $value, [], $fontStyle, self::$noPaddingPS);
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

    private function addHeaders(\PhpOffice\PhpWord\Element\Table $table, $headers)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER, 'bold' => true];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $table->addRow(700);

        foreach ($headers as $header) {
            $table->addCell(900, $cellRowSpan)->addText($header, $hcFontStyle, $hcAlignStyle);
        }
    }

    private function addTopData(Section $section)
    {
        // заголовок
        $section->addText(
            $this->data['title']
            . "{$this->data['dateFrom']} - {$this->data['dateTo']}",
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
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
            'orientation' => 'landscape',
            'marginLeft' => 500,
            'marginRight' => 500,
            'marginTop' => 500,
            'marginBottom' => 500
        ]);
    }

}
