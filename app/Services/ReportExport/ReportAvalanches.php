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

class ReportAvalanches
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

        $section->addText('');
        $section->addText('Общее количество: '.$this->data['records']->count());
    }

    private function addFirstTableData(\PhpOffice\PhpWord\Element\Table $table)
    {
        foreach ($this->data['records'] as $index => $record) {
            $rowVal = [
                ++$index,
                @Carbon::parse($record->created_at)->format('d/m/Y'),
                $record->detailed_address ? $record->detailed_address : $record->location,
                $record->avalanche_type->name,
                $record->emergency_feature,
                $record->avalanche_volume,
                $record->avalanche_note,
            ];
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

    private function addFirstTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $table->addRow(700);
        $headers = [
            '№ п/п',
            'Дата',
            'Адрес',
            'Тип схода лавин',
            'Описание происшествия',
            'Куб/м',
            'Примечание',
        ];
        foreach ($headers as $header) {
            $table->addCell(900, $cellRowSpan)->addText($header, $hcFontStyle, $hcAlignStyle);
        }
    }

    private function addFirstPageTopData(Section $section)
    {
        // заголовок
        $section->addText(
            'Профилактический и самопроизвольный сход лавин',
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );
        $section->addText("{$this->data['date_from']} - {$this->data['date_to']}");
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
