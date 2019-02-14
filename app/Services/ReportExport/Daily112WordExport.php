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
            $simpleFontStyle,
            ['align' => Jc::BOTH]
        );

        $this->addParagraph($section, '1. Системы связи и оповещения: ', 'в исправном состоянии и в рабочем режиме.');

        $section->addText(
            '2. Мониторинг окружающей среды и происшествий природного характера:',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $this->addParagraph($section, '- ГУ «СОМЭ КН МОН РК»: ', ($this->data['SOME']->epicenter ?? null).($this->data['SOME']->mpv ? (' магнитуда: '.$this->data['SOME']->mpv ?? null) : ''), ['indentation' => ['left' => 540]]);
        $this->addParagraph($section, '- РГП «Казгидромет»: ', $this->data['weather_forecast']->forecast_city1, ['indentation' => ['left' => 540]]);
        $this->addParagraph($section, '- АГЭУ ГУ «Казселезащита: ', $this->data['mudflow_emergency_count'] ? $this->data['mudflow_emergency_count'] : 'не зарегистрировано', ['indentation' => ['left' => 540]]);
        $this->addParagraph($section, '3. Системы жизнеобеспечения города: ', 'не зарегистрировано');
        $this->addParagraph($section, '4. Подтопления: ', $this->data['flooding_count']);

        $index = 5;
        foreach ($this->data['services'] as $serviceName => $datum) {
            $this->addParagraph($section, "$index. {$serviceName}: ", $datum->count() ? $datum->count() : 'не зарегистрировано');
            $this->serviceInfo($section, $datum);
            $index++;
        }

        $this->addParagraph($section, "{$index}. По основной деятельности «112»: ", (!$this->data['cards112']->count() ? 'не зарегистрировано': ''));

        foreach ($this->data['cards112'] as $card) {
            $section->addText(
                "{$card->created_at->format('H:i')} {$card->location} - {$card->reason}. {$card->measures} {$card->resources}. 
                Материал зарегистрирован в КУИ № {$card->id} от {$card->created_at->format('d-m-Y')} г. ",
                $simpleFontStyle,
                ['align' => Jc::BOTH]
            );
        }

        $index++;
        $this->addParagraph($section, "{$index}. ГУ «СП и АСР» ", $this->data['cards101']->count());
        $index++;
        $this->addParagraph($section, "{$index}. ЕДДС: Всего поступивших звонков на «112» - ", ($this->data['call_info']->count_112 ?? 0). ", «101» - ".($this->data['call_info']->count_101 ?? 0) . ", «109» - ".($this->data['call_info']->count_109 ?? 0));

        $strAircraft = '';

        foreach ($this->data['air_rescue_report_tech'] as $air_rescue_report_tech) {
            $strAircraft .= $air_rescue_report_tech->name.', ';
        }

        $index++;

        $this->addParagraph($section, "$index. Казавиаспас: в аэропорту Боролдай в режиме дежурства: ", $strAircraft);
        $index++;

        $this->addParagraph($section, "$index. Отработано всего выездов Службой Спасения г. Алматы – ", $this->data['cards112']->count());
        $index++;

        $this->addParagraph($section, "$index. Данные по СРУ: ", $this->data['siren_speech_tech']->total .":". "С-40 (моторные) – {$this->data['siren_speech_tech']->motor}, СРУ-{$this->data['siren_speech_tech']->sst}:  из них  в нерабочем – {$this->data['siren_speech_tech']->broken}");
        $index++;
        $this->addParagraph($section, "$index. Мониторинг интернет пространства – ", 'негативная информация не зарегистрирована.');

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        foreach ($this->data['footer_persons'] as $person) {

            $section->addText(
                $person->position.' '.$person->city,
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
            $section->addText(
                $person->rank,
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
            $section->addText(
                $person->name,
                $generalBoldFontStyle,
                ['align' => Jc::END]
            );

            $section->addText(
                '',
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
        }


    }

    private function serviceInfo(&$section, $data, $style = ['indentation' => ['left' => 540]])
    {
        foreach ($data as $index => $datum) {
            $index++;
            $this->addParagraph($section, $index.'. Дата: ', $datum->date_human_format, $style);
            $this->addParagraph($section, 'Время: ', $datum->time_human_format, $style);
            $this->addParagraph($section, 'Место ЧС: ', $datum->location, $style);
            $this->addParagraph($section, 'Информация о событии: ', $datum->description, $style);

            $datum->wounded === null ?: $this->addParagraph($section, 'Пострадавших людей/детей: ', $datum->wounded, $style);
            $datum->died === null ?: $this->addParagraph($section, 'Погибло людей/детей: ', $datum->died, $style);
            $datum->evacuated === null ?: $this->addParagraph($section, 'Эвакуированных людей/детей: ', $datum->evacuated, $style);
            $datum->hospitalized === null ?: $this->addParagraph($section, 'Госпитализированных людей/детей: ', $datum->hospitalized, $style);
            $datum->injured === null ?: $this->addParagraph($section, 'Травмированных людей/детей: ', $datum->injured, $style);
            $datum->poisoned === null ?: $this->addParagraph($section, 'Отравление людей/детей: ', $datum->poisoned, $style);
            $datum->saved === null ?: $this->addParagraph($section, 'Спасено людей/детей: ', $datum->saved, $style);
            $datum->saved_animals === null ?: $this->addParagraph($section, 'Спасено животных: ', $datum->saved_animals, $style);
            $this->addParagraph($section, '', '', $style);
        }
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
