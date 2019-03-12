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

class FormationRecordWordExport
{

    use CommonExportTools;

    /**
     * @var PhpWord
     */
    private $phpWord;

    private $firstTableFields = [];
    private $secondTableFields = [];

    /**
     * @var array
     */
    private $data;

    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

    public function __construct($data)
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

        $this->addSecondTable($section);

        $section->addPageBreak();

        $this->addDutyPersonsTable($section);

        $this->addDutyPersonsServiceTable($section);

        $section->addPageBreak();

        $this->addFromationDistrictManagersTable($section);

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

    private function addSecondTableData(\PhpOffice\PhpWord\Element\Table $table)
    {
        $sum = [];
        foreach ($this->data['formationRecords'] as $formationRecord) {
            $row = $table->addRow();
            $sum[] = $this->getFirstTableRowForOrganization($formationRecord, $this->secondTableFields);
            foreach ($this->getFirstTableRowForOrganization($formationRecord, $this->secondTableFields) as $key => $value) {
                $fontStyle = ['name' => 'Times New Roman', 'size' => 12];
                if ($key === 0) {
                    $fontStyle['bold'] = true;
                }

                $this->addDataCellToRow($row, $value, [], $fontStyle, self::$noPaddingPS);
            }
        }

        // сумма
        $row = $table->addRow();
        $sumData = $this->getTableSumRow($sum);
        $sumData[0] = 'Итого';
        foreach ($sumData as $value) {
            $this->addDataCellToRow($row, $value, ['borderSize' => 10, 'borderColor' => '000000'], ['bold' => true, 'name' => 'Times New Roman', 'size' => 12], self::$noPaddingPS);
        }
    }

    private function addSecondTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpanV = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanVThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowSpanH = ['vMerge' => 'restart', 'valign' => Jc::CENTER];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 10, 'valign' => Jc::CENTER];
        $hcAlignStyle = array_merge(['align' => Jc::CENTER, 'valign' => Jc::CENTER], self::$noPaddingPS);

        $table->addRow(1500);
        $table->addCell(1500, $cellRowSpanV)->addText('Наименование подразеления', $hcFontStyle, $hcAlignStyle);

        $table->addCell(3000, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('ГСМ', $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText('Радиостанции', $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText('Средства индивидуальной защиты органов дыхания	', $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText('Средства индивидуальной защиты(Костюмы л1 и пр.)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText('Другие спасательные средства', $hcFontStyle, $hcAlignStyle);

        $table->addRow(1250);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(600, $cellRowSpanV)->addText('Банзин', $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText('Дизтопливо', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

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
        $this->secondTableFields = [
            'gsm_gasoline',
            'gsm_diesel',
            'radio_stations',
            'personal_respiratory_protection',
            'personal_protection',
            'other_protection',
        ];

        $secondTableFields = $this->secondTableFields;

        $this->firstTableFields = array_filter($this->data['fields'], function ($item) use ($secondTableFields) {
            return !in_array($item,$secondTableFields);
        });

        $sum = [];
        foreach ($this->data['formationRecords'] as $formationRecord) {
            $row = $table->addRow();
            $sum[] = $this->getFirstTableRowForOrganization($formationRecord, $this->firstTableFields);
            foreach ($this->getFirstTableRowForOrganization($formationRecord, $this->firstTableFields) as $key => $value) {
                $fontStyle = ['name' => 'Times New Roman', 'size' => 12];
                if ($key === 0) {
                    $fontStyle['bold'] = true;
                }

                $this->addDataCellToRow($row, $value, $fontStyle, self::$noPaddingPS);
            }
        }

        // сумма
        $row = $table->addRow();
        $sumData = $this->getTableSumRow($sum);
        $sumData[0] = 'Итого';
        $sumData[2] = '-';
        $sumData[3] = '-';
        foreach ($sumData as $value) {
            $this->addDataCellToRow($row, $value, ['borderSize' => 10, 'borderColor' => '000000'], ['bold' => true, 'name' => 'Times New Roman', 'size' => 12], self::$noPaddingPS);
        }
    }

    private function addFirstTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 10, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0, 'line' => 230], 'indentation' => ['left' => 0, 'right' => 0]];

        $table->addRow(500);

        $table->addCell(300, $cellRowSpan)->addText('Наименование подразеления', $hcFontStyle, $hcAlignStyle);

        $table->addCell(300, ['gridSpan' => 3, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Руководящий состав', $hcFontStyle, $hcAlignStyle);
        $table->addCell(300, ['gridSpan' => 5, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Личный состав', $hcFontStyle, $hcAlignStyle);
        $table->addCell(300, ['gridSpan' => 8, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Техника', $hcFontStyle, $hcAlignStyle);

        $table->addRow(500);

        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, $cellRowSpan)->addText('Количество', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Ф.И.О.', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Телефон', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('По штату', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('В наличии', $hcFontStyle, $hcAlignStyle);
        $table->addCell(300, ['gridSpan' => 3, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('На оперативном дежурстве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(300, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Основная', $hcFontStyle, $hcAlignStyle);
        $table->addCell(300, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Специальная', $hcFontStyle, $hcAlignStyle);
        $table->addCell(300, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Вспомогательная', $hcFontStyle, $hcAlignStyle);
        $table->addCell(300, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Другая', $hcFontStyle, $hcAlignStyle);

        $table->addRow(3000);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowSpan)->addText('ФИО Старшего смены', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Количество', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('8-ми часовом рабочем дне', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('На дежурстве(в боевом рассчете)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('В резерве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('На дежурстве(в боевом рассчете)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('В резерве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('На дежурстве(в боевом рассчете)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('В резерве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('На дежурстве(в боевом рассчете)', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('В резерве', $hcFontStyle, $hcAlignStyle);
    }

    private function addFirstPageTopData(Section $section)
    {
        // заголовок
        $section->addText(
            'Журнал строевых записок ДЧС г.Алматы ' . now()
                ->addDay()
                ->format('d.m.Y'),
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('ffffff');
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);
    }

    //Дежурная смена УЕДДС ДЧС г.Алматы КЧС МВД РК
    private function addDutyPersonsTable(Section $section)
    {
        $cellRowSpan = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 12, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $section->addText(
            'Дежурная смена УЕДДС ДЧС г.Алматы КЧС МВД РК',
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('black');
        $tableStyle->setUnit(TblWidth::PERCENT);

        $table = $section->addTable($tableStyle);

        $headers = [
            '№',
            'ФИО',
            'Должность',
            'Направление',
        ];
        $fontStyle = ['name' => 'Times New Roman', 'size' => 12, 'bold' => true];

        $row = $table->addRow(1600);

        $this->addDataCellToRow($row, $headers, ['borderSize' => 10, 'borderColor' => '000000'], $fontStyle, self::$noPaddingPS);


        foreach ($this->data['dutyShiftItems'] as $dutyShiftItem) {

            $row = $table->addRow(1600);

            $arrData = [
                $dutyShiftItem['shift']['name'],
                $dutyShiftItem['staff']['name'],
                $dutyShiftItem['rankHumanFormat'],
                $dutyShiftItem['direction'],
            ];
            $fontStyle['bold'] = false;
            $this->addDataCellToRow($row, $arrData, ['borderSize' => 10, 'borderColor' => '000000'], $fontStyle, self::$noPaddingPS);

        }
    }

    //Дежурная смена служб взаимодействия
    private function addDutyPersonsServiceTable(Section $section)
    {
        $section->addText(
            'Дежурная смена служб взаимодействия',
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('black');
        $tableStyle->setUnit(TblWidth::PERCENT);

        $table = $section->addTable($tableStyle);

        $headers = [
            'Служба взаимодействия',
            'ФИО дежурного',
        ];
        $fontStyle = ['name' => 'Times New Roman', 'size' => 12, 'bold' => true];

        $row = $table->addRow(1600);

        $this->addDataCellToRow($row, $headers, ['borderSize' => 10, 'borderColor' => '000000'], $fontStyle, self::$noPaddingPS);


        foreach ($this->data['dutyPersonsServiceArr'] as $item) {

            $row = $table->addRow(1600);

            $arrData = [
                $item['name'],
                $item['value'],
            ];

            $fontStyle['bold'] = false;
            $this->addDataCellToRow($row, $arrData, ['borderSize' => 10, 'borderColor' => '000000'], $fontStyle, self::$noPaddingPS);

        }
    }

    //Ответственные по районным отделам ЧС г.Алматы
    private function addFromationDistrictManagersTable(Section $section)
    {
        $section->addText(
            'Ответственные по районным отделам ЧС г.Алматы',
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('black');
        $tableStyle->setUnit(TblWidth::PERCENT);

        $table = $section->addTable($tableStyle);

        $headers = [
            'Районный отдел ЧС',
            'Должность, звание, позывной',
            'Ф.И.О., телефон',
        ];

        $fontStyle = ['name' => 'Times New Roman', 'size' => 12, 'bold' => true];

        $row = $table->addRow(1600);

        $this->addDataCellToRow($row, $headers, ['borderSize' => 10, 'borderColor' => '000000'], $fontStyle, self::$noPaddingPS);


        if($this->data['formationDistrictManager']) {
            foreach ($this->data['cityAreas'] as $area) {

                $row = $table->addRow(1600);

                $this->addDataCellToRow($row, $area['name'], ['borderSize' => 10, 'borderColor' => '000000'], $fontStyle, self::$noPaddingPS);

                foreach ($this->data['formationDistrictManager']['items'] as $item) {
                    if ($item['city_area_id'] === $area['id']) {
                        $phones = '';
                        foreach ($item['manager']['phones'] as $phone) {
                            $phones .= $phone['phone'] . ' ';
                        }
                        $arrData = [
                            $item['manager']['position'] .' '.$item['manager']['rank']. ' '.$item['manager']['nickname'],
                            $item['manager']['name']. ' '.$phones,
                        ];

                        $fontStyle['bold'] = false;
                        $this->addDataCellToRow($row, $arrData, ['borderSize' => 10, 'borderColor' => '000000'], $fontStyle, self::$noPaddingPS);
                    }
                }
            }
        }
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

    private function addDataCellToRow(Row $row, $value, array $extraCellStyles = [], array $extraTextFStyles = [], array $extraTextPStyles = [], $width = null)
    {
        if(is_array($value)) {
            foreach ($value as $item) {
                $this->addDataCellToRow($row,$item,$extraCellStyles,$extraTextFStyles,$extraTextPStyles);
            }
        }
        else {
            $row
                ->addCell($width, array_merge(['valign' => 'center', 'align' => 'center'], $extraCellStyles))
                ->addText(
                    $value,
                    array_merge(['name' => 'Times New Roman', 'size' => 12], $extraTextFStyles),
                    array_merge(['valign' => 'center', 'align' => 'center'], $extraTextPStyles)
                );
        }

    }


}
