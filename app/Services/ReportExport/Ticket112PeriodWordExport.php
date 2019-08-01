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
          
            foreach ($stat as $citAreaTitle => $cityArea) {
                $row = $table->addRow();
                $arr = [
                    $incident_type,
                    $citAreaTitle,
                    $cityArea['total'],
                    $cityArea['injured'],
                    $cityArea['dead'],
                    $cityArea['evacuated'],
                    $cityArea['hospitalized'],
                    $cityArea['injured_hard'],
                    $cityArea['poisoned'],
                    $cityArea['saved'],
                    $cityArea['saved_animals'],
                ];

             foreach ($arr as $value) {



             	$fontStyle = ['name' => 'Times New Roman', 'size' => 8];
               $this->addDataCellToRow($row, $value, [], $fontStyle, self::$noPaddingPS);
             }
               
               
            }


        }

        /*$row = $table->addRow();
        foreach ($this->getFirstTableSumRow() as $value) {
            $this->addDataCellToRow($row, $value, ['borderSize' => 10, 'borderColor' => '000000'], ['bold' => true, 'name' => 'Times New Roman', 'size' => 8], self::$noPaddingPS);
        }*/
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
            'Отчет по карточке 112 за период',
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