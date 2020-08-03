<?php

namespace App\Services\ReportExport;

use App\FireDepartment;
use App\FormationReport;
use App\Ticket101;
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

class Ticket112PeriodWordExport
{


    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * @var array
     */
    private $data;
    private $dateBegin;
    private $dateEnd;

    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

    public function __construct(array $data, $dateBegin, $dateEnd)
    {
        $this->phpWord = new PhpWord();
        $this->data = $data;
        $this->dateBegin = Carbon::parse($dateBegin)->format('d.m.Y');
        $this->dateEnd = Carbon::parse($this->dateEnd)->format('d.m.Y');

        $this->prepareDocument();
    }

    private function prepareDocument()
    {
        $section = $this->getNewSection();

        $this->addFirstPageTopData($section);

        $section->addTextBreak(1, ['size' => 1]);

        $this->addFirstTable($section);

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
        foreach ($this->data as $incident_type => $stat) {
            if($incident_type !== 'Итог') {
                foreach ($stat as $citAreaTitle => $cityArea) {
                    $row = $table->addRow();
                    $arr = [
                        $citAreaTitle !== 'Итого' ? $incident_type : 'Итого',
                        $citAreaTitle !== 'Итого' ? $citAreaTitle : '',
                        $cityArea['total'],
                        $cityArea['injured'] . ' / ' . $cityArea['injured_children'],
                        $cityArea['dead'] . ' / ' . $cityArea['dead_children'],
                        $cityArea['evacuated'] . ' / ' . $cityArea['evacuated_children'],
                        $cityArea['hospitalized'] . ' / ' . $cityArea['hospitalized_children'],
                        $cityArea['injured_hard'] . ' / ' . $cityArea['injured_hard_children'],
                        $cityArea['poisoned'] . ' / ' . $cityArea['poisoned_children'],
                        $cityArea['saved'] . ' / ' . $cityArea['saved_children'],
                        $cityArea['saved_animals'],
                    ];

                    foreach ($arr as $value) {
                        $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
                        $this->addDataCellToRow($row, $value, [], $fontStyle, self::$noPaddingPS);
                     }
                }
            }
        }

        $row = $table->addRow();

        $arr = [
            'Итог',
            '',
            $this->data['Итог']['total'],
            $this->data['Итог']['injured'] . ' / ' . $this->data['Итог']['injured_children'],
            $this->data['Итог']['dead'] . ' / ' . $this->data['Итог']['dead_children'],
            $this->data['Итог']['evacuated'] . ' / ' . $this->data['Итог']['evacuated_children'],
            $this->data['Итог']['hospitalized'] . ' / ' . $this->data['Итог']['hospitalized_children'],
            $this->data['Итог']['injured_hard'] . ' / ' . $this->data['Итог']['injured_hard_children'],
            $this->data['Итог']['poisoned'] . ' / ' . $this->data['Итог']['poisoned_children'],
            $this->data['Итог']['saved'] . ' / ' . $this->data['Итог']['saved_children'],
            $this->data['Итог']['saved_animals'],
        ];

        foreach ($arr as $value) {
            $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
            $this->addDataCellToRow($row, $value, [], $fontStyle, self::$noPaddingPS);
        }
    }

    private function addDataCellToRow(Row $row, $value, array $extraCellStyles = [], array $extraTextFStyles = [], array $extraTextPStyles = [])
    {
        $row
            ->addCell(null, array_merge(['valign' => 'center', 'align' => 'center'], $extraCellStyles))
            ->addText(
                $value,
                array_merge(['name' => 'Times New Roman', 'size' => 14], $extraTextFStyles),
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

        $data['headers'] = [
            'Происшествие',
            'Район города',
            'Кол-во происшествий(ЧС)',
            'Пострадавших людей/детей',
            'Погибших людей/детей',
            'Эвакуированных людей/детей',
            'Госпитализированных людей/детей',
            'Травмированных людей/детей',
            'Отравление людей/детей',
            'Спасено людей/детей',
            'Спасено животных',
        ];

        foreach ($data['headers'] as $header) {
            $table->addCell(900, $cellRowSpan)->addText($header, $hcFontStyle, $hcAlignStyle);
        }
    }

    private function addFirstPageTopData(Section $section)
    {
        // заголовок
        $section->addText(
            "Отчет по карточке 112 за период c {$this->dateBegin} по {$this->dateEnd}",
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );
        $section->addText('');

        $section->addText(
            "Количество происшествий: {$this->data['Итог']['total']}",
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );
        $section->addText('');

        /*$tableStyle = new Table;
        $tableStyle->setBorderColor('ffffff');
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $section->addTable($tableStyle);*/
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


?>