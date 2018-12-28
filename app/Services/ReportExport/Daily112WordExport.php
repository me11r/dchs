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

class Daily112WordExport
{

    use CommonExportTools;

    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * @var array
     */
    private $data;

    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

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
            $this->data['header_person']['position'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 6400]]
        );
        $section->addText(
            $this->data['header_person']['city'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 6400]]
        );
        $section->addText(
            $this->data['header_person']['post'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 6400]]
        );
        $section->addText(
            $this->data['header_person']['name'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 6400]]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Оперативная информация',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            'Управления единой дежурно-диспетчерской службы ДЧС г. Алматы',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            'об основных чрезвычайных происшествиях, произошедших в период',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            'с ' . $this->data['dates']['hour'] . ' час. ' . $this->data['dates']['minutes'] . ' мин. ' . $this->data['dates']['from'] . 'г. до ' . $this->data['dates']['hour'] . ' час. ' . $this->data['dates']['minutes'] . ' мин. ' . $this->data['dates']['to'] . 'г.',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Городская подсистема государственной системы гражданской защиты функционирует в режиме повседневной деятельности: ',
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $resultString = 'ЧС – ' . $this->data['cards112']->count().', ';

        foreach (TripResult::all() as $reason) {

            $cnt = $this->data['cards101']->filter(function ($event) use ($reason) {
                return $event->trip_result_id == $reason->id;
            });

            if($cnt->count()){

                $resultString .= "{$reason->name} – " . $cnt->count().', ';


            }
        }

        $resultString .= 'погиб – ' . $this->data['dead_count'].', ';
        $resultString .= 'спасено – ' . $this->data['saved_count'].', ';
        $resultString .= 'эвакуировано – ' . $this->data['evacuated_count'].', ';
        $resultString .= 'отравление природным/ угарным газом – ' . $this->data['poisoningCount'].', ';
        $resultString .= 'пострадавший. – ' . $this->data['hurt_count'].'.';

        $section->addText(
            $resultString,
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '1. Системы связи и оповещения: в исправном состоянии и в рабочем режиме.',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '2. Мониторинг окружающей среды и происшествий природного характера:',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );


        $section->addText(
            "- ГУ «СОМЭ КН МОН РК»: ".($this->data['SOME']->epicenter ?? null),
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            "- РГП «Казгидромет»: ".$this->data['weather_forecast']->forecast_city1,
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            "- АГЭУ ГУ «Казселезащита: ",
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            '3. Системы жизнеобеспечения города: не зарегистрировано',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '4. Подтопления: '.$this->data['flooding_count'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '5. ЦМК: '.$this->data['cmk_count'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '6. РОСО: '.($this->data['roso_count'] ?? 'без выездов'),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '7. По основной деятельности «112»: ',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        foreach ($this->data['cards112'] as $card) {
            $section->addText(
                "{$card->created_at->format('H:i')} {$card->location} - {$card->reason}. {$card->measures} {$card->resources}. 
                Материал зарегистрирован в КУИ № {$card->id} от {$card->created_at->format('d-m-Y')} г. ",
                $simpleFontStyle,
                ['align' => Jc::BOTH]
            );
        }

        $section->addText(
            '8. ГУ «СП и АСР» '.$this->data['cards101']->count(),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '9. ЕДДС: Всего поступивших звонков на «112» - '.($this->data['call_info']->count_112 ?? 0). ", «101» - ".($this->data['call_info']->count_101 ?? 0) . ", «109» - ".($this->data['call_info']->count_109 ?? 0),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $strAircraft = '';

        foreach ($this->data['air_rescue_report_tech'] as $air_rescue_report_tech) {
            $strAircraft .= $air_rescue_report_tech->name.', ';
        }

        $section->addText(
            '10. Казавиаспас: в аэропорту Боролдай в режиме дежурства: '.$strAircraft,
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '11. Отработано всего выездов Службой Спасения г. Алматы – ',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '12. Данные по СРУ: ',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '13. Мониторинг интернет пространства – негативная информация не зарегистрирована.',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            $this->data['footer_second_person']['position'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            $this->data['footer_second_person']['city'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            $this->data['footer_second_person']['post'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            $this->data['footer_second_person']['name'],
            $generalBoldFontStyle,
            ['align' => Jc::END]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
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
