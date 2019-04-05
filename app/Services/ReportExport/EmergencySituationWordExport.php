<?php


namespace App\Services\ReportExport;

use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use App\FireDepartment;
use App\FormationPersonsReport;
use App\FormationReport;
use App\Models\DailyReportPerson;
use App\Models\FireDepartmentResult;
use App\Models\Vehicle;
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

class EmergencySituationWordExport
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
    private $generalBoldFontStyle = ['name' => 'Times New Roman', 'size' => 10, 'bold' => true];
    private $simpleFontStyle = ['name' => 'Times New Roman', 'size' => 10];

    public function __construct(
        array $data
    )
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
        $this->phpWord->setDefaultFontName('Times New Roman');
        $this->defaultParagraph();
        $section = $this->getNewSection();
        $this->addData($section);
    }

    private function addData(Section $section)
    {
        $headerFontStyle = ['name' => 'Times New Roman', 'size' => 9, 'bold' => true];
        $generalBoldFontStyle = ['name' => 'Times New Roman', 'size' => 10, 'bold' => true];
        $generalBoldUnderlineFontStyle = ['name' => 'Times New Roman', 'size' => 10, 'bold' => true, 'underline' => 'single'];
        $generalBoldItalicUnderlineFontStyle = ['name' => 'Times New Roman', 'size' => 10, 'bold' => true, 'italic' => true, 'underline' => 'single'];
        $simpleFontStyle = ['name' => 'Times New Roman', 'size' => 10];

        $generalBoldFontStyle8 = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true];
        $generalBoldUnderlineFontStyle8 = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true, 'underline' => 'single'];

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Экстренная информация по чрезвычайной ситуации',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $this->addParagraph($section, 'Дата происшествия: ', $this->data['date']);
        $this->addParagraph($section, 'Время происшествия: ', $this->data['readable_time']);
        $this->addParagraph($section, 'Место чс: ', $this->data['location']);
        $this->addParagraph($section, 'Количество пострадавших/ из них детей: в том числе: ', '');
        $this->addParagraph($section, 'Информация о событии', '');

        $section->addText(
            $this->data['description'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Погибло '.$this->data['died'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Травмировано '. $this->data['injured'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Пострадало '. $this->data['wounded'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Отравление '. $this->data['poisoned'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Госпитализировано '. $this->data['hospitalized'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Эвакуировано '. $this->data['evacuated'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Спасено '. $this->data['saved'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Спасено животных  '. $this->data['saved_animals'],
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        /*$this->addParagraph($section, 'Погибло ', $this->data['died']);
        $this->addParagraph($section, 'Травмировано ', $this->data['injured']);
        $this->addParagraph($section, 'Пострадало ', $this->data['wounded']);
        $this->addParagraph($section, 'Отравление ', $this->data['poisoned']);
        $this->addParagraph($section, 'Госпитализировано ', $this->data['hospitalized']);
        $this->addParagraph($section, 'Эвакуировано ', $this->data['evacuated']);
        $this->addParagraph($section, 'Спасено ', $this->data['saved']);
        $this->addParagraph($section, 'Спасено животных ', $this->data['saved_animals']);*/

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $finalSign = "{$this->data['user_name']} ___________ (подпись)";
        $this->addParagraph($section, 'Должность, Ф.И.О. оперативного дежурного УЕДДС:', $finalSign);
    }

    private function addParagraph(&$section, $header, $data, $style = ['align' => Jc::BOTH])
    {
        $sectionRun = $section->addTextRun($style);
        $sectionRun->addText(
            $header,
            $this->generalBoldFontStyle,
            $style
        );

        $sectionRun->addText(
            $data,
            $this->simpleFontStyle,
            $style
        );
    }

    private function defaultParagraph()
    {
        $this->phpWord->setDefaultParagraphStyle([
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
                'spacing' => 120,
                'lineHeight' => 1,
            ]
        );
    }

    private function getNewSection()
    {
        return $this->phpWord->addSection([
            'marginLeft' => 800,
            'marginRight' => 700,
            'marginTop' => 400,
            'marginBottom' => 400,
            'headerHeight' => 50,
            'footerHeight' => 50,
        ]);
    }

    // @todo PDF не работает корректно
    public function getWriter($name = 'Word2007')
    {
        return IOFactory::createWriter($this->phpWord, $name);
    }
}
