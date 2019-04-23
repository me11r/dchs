<?php


namespace App\Services\ReportExport;

use App\CallInfo;
use App\FireDepartment;
use App\FormationOdPersonItem;
use App\FormationPersonsReport;
use App\FormationReport;
use App\GuardNumber;
use App\Models\MudflowProtection;
use App\Models\River;
use App\Models\Vehicle;
use App\MudflowProtectionBlock;
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

class ReportMudflowWord
{

    /**
     * @var PhpWord
     */
    private $phpWord;

    private $date;
    private $dateFrom;
    private $dateTo;

    private $models;
    private $block;


    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

    public function __construct($date)
    {
        $this->defineDomPdfWriter();

        $this->phpWord = new PhpWord();
        $this->date = $date;

        $this->models = MudflowProtection::whereDate('created_at', $date)
            ->get()
            ->keyBy('gauging_station_id');

        $this->block = MudflowProtectionBlock::where('date', $date)->first();

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

        $this->addFirstPageBottomData($section);
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
        $this->addTableData($table);
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
        $cellRowHorizontal = ['align' => Jc::CENTER, 'valign' => Jc::CENTER];

        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $table->addRow(700);

        $headers = [
            '№',
//            'Бассейн реки',
            'Информация',
            'Наименование гидропостов и их отметка',
            'Расход воды, м3/сек',
            'Критический расход воды, м3/сек',
            'Мутность воды',
            'Максимальная мутность воды',
            'Температура воздуха',
            'Температура воды',
            'Осадки',
            'Высота снега (Н) см',
            'Погода',
        ];

        foreach ($headers as $header) {
            $style = in_array($header, ['Максимальная мутность воды','Температура воздуха','Температура воды']) ? $cellRowSpan : $cellRowHorizontal;

            $table->addCell(800, $style)->addText($header, $hcFontStyle, $hcAlignStyle);
        }

    }

    private function addFirstPageTopData(Section $section)
    {
        // заголовок
        $section->addText("Информация",
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $section->addText($this->block->text_header ?? null,
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => false],
            ['align' => Jc::BOTH]
        );

        $section->addText("Гидрометеорологические данные",
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true, 'italic' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('ffffff');
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);



        $table = $section->addTable($tableStyle);
    }
    private function addFirstPageBottomData(Section $section)
    {
        $section->addText($this->block->text_footer ?? null,
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => false],
            ['align' => Jc::BOTH]
        );
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

    private function addTableData(\PhpOffice\PhpWord\Element\Table $table, $startRowIndex = 2)
    {
        $rowIndex = $startRowIndex;
        $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
        $cellStyles = ['borderSize' => 10, 'borderColor' => '000000'];

        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = array_merge(['align' => Jc::CENTER, 'valign' => Jc::CENTER], self::$noPaddingPS);

        $rivers = River::all();

        foreach ($rivers as $key => $river) {
            $row = $table->addRow();

            $row->addCell(null, ['vMerge' => 'restart','gridSpan' => 12, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText($river->name, $hcFontStyle, $hcAlignStyle);

            foreach ($river->gaugingStations as $gaugingStation) {
                $row = $table->addRow();

                $arr = [
                    ++$key,
//                    $river->name,
                    $this->models[$gaugingStation->id]->information ?? null,
                    $gaugingStation->name,
                    $this->models[$gaugingStation->id]->water_flow_rate ?? null,
                    $this->models[$gaugingStation->id]->ritical_water_flow_rate ?? null,
                    $this->models[$gaugingStation->id]->turbidity_of_water ?? null,
                    $this->models[$gaugingStation->id]->max_turbidity_of_water ?? null,
                    $this->models[$gaugingStation->id]->air_temperature ?? null,
                    $this->models[$gaugingStation->id]->water_temperature ?? null,
                    $this->models[$gaugingStation->id]->precipitation ?? null,
                    $this->models[$gaugingStation->id]->height_of_snow ?? null,
                    $this->models[$gaugingStation->id]->weather ?? null,
//                    $this->models[$gaugingStation->id]->comment ?? null,
//                    ($this->models[$gaugingStation->id]->updated_at ?? null) ? $this->models[$gaugingStation->id]->updated_at->format('d.m.Y H:i:s') : '',
                ];

                $this->addDataCellToRow($row, $arr, $cellStyles, $fontStyle, self::$noPaddingPS);

                $rowIndex++;
            }
        }
    }

}
