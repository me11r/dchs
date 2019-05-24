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
            'Управления в кризисных ситуациях ДЧС г. Алматы',
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

        $resultString = 'ЧС – ' . ($this->data['cards112_emergency']->count() + $this->data['cards101_emergency']->count())
            .', пожары – '.($this->data['fires_count_101']->count() + $this->data['fires_count_112']->count()) .', ';

        $resultString .= 'погиб – ' . $this->data['emergency_dead_count'].', ';
        $resultString .= 'спасено – ' . $this->data['emergency_saved_count'].', ';
        $resultString .= 'эвакуировано – ' . $this->data['evacuated_count'].', ';
        $resultString .= 'отравление природным/ угарным газом – ' . $this->data['emergency_poisoningCount'].', ';
        $resultString .= 'пострадавших – ' . $this->data['emergency_hurt_count'].'.';

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

        $some = "";
        foreach ($this->data['SOME'] as $someItem) {
            $some .= ($someItem->epicenter ?? null).($someItem->mpv ? (' магнитуда: '.$someItem->mpv ?? null) : '').'; ';
        }

        $this->addParagraph($section, '- ГУ «СОМЭ КН МОН РК»: ', $some ? $some : 'не зарегистрировано', ['indentation' => ['left' => 540]]);
        $this->addParagraph($section, '- РГП «Казгидромет»: ', $this->data['weather_forecast']->forecast_city1 ?? 'не зарегистрировано', ['indentation' => ['left' => 540]]);

        if ($this->data['weather_forecast']->storm_warning_text ?? null) {
            $this->addParagraph($section, "Штормовое предупреждение №{$this->data['weather_forecast']->storm_warning_number} от {$this->data['weather_forecast']->storm_date_formatted}: ",
                $this->data['weather_forecast']->storm_warning_text,
                ['indentation' => ['left' => 540]]);
        }

        $this->addParagraph($section, '- АГЭУ ГУ «Казселезащита: ', $this->data['mudflow_emergency_count'] ? $this->data['mudflow_emergency_count'] : 'не зарегистрировано', ['indentation' => ['left' => 540]]);
        $this->addParagraph($section, '3. Системы жизнеобеспечения города: ', 'не зарегистрировано');

        $this->addParagraph($section, '4. Подтопления: ', $this->data['flooding_count']);
        $cardIndex = 1;
        foreach ($this->data['cards112'] as $card) {
            if ($card->additional_incident_type_id == 36) { //нас интересуют только подтопления
                $this->addParagraph($section, $cardIndex++.". ", $card->analytics, ['indentation' => ['left' => 540]]);
                $this->addParagraph($section, "",'');
            }
        }

        $index = 5;
        $subIndex = 1;
        foreach ($this->data['services'] as $serviceName => $datum) {
            $this->addParagraph($section, "$index. {$serviceName}: ", $datum->count() ? $datum->count() : 'не зарегистрировано');
            $this->serviceInfo($section, $datum);
            $index++;
        }

        $this->addParagraph($section, "$index. Отработано всего выездов Службой Спасения г. Алматы – ", $this->data['cards112']->count());
        $index++;

        $this->addParagraph($section, "{$index}. По основной деятельности «112»: ", (!$this->data['cards112']->count() ? 'не зарегистрировано': ''));

        $cardIndex = 1;
        foreach ($this->data['cards112'] as $card) {
            if ($card->additional_incident_type_id !== 36) { //игнорим подтопления, т.к. показываем их выше
                $this->addParagraph($section, $cardIndex++.". ", $card->analytics, ['indentation' => ['left' => 540]]);
                $this->addParagraph($section, "",'');
            }
        }

        //11 пункт
        $index++;
        $guSpiasrString = "всего выездов – " . $this->data['cards101']->count();
        $guSpiasrString .= " из них: ";

        $reasons = TripResult::dailyReportConst()->get();
        $secondIndex = 0;

        foreach ($reasons as $reason) {

            $cnt = $this->data['cards101']->filter(function ($event) use ($reason) {
                return $event->trip_result_id == $reason->id;
            });

            //не выводим "нулевые" выезды
            if($cnt->count() == 0) {
                continue;
            }

            $secondIndex++;

            $upper = ucfirst($reason->name);

            $guSpiasrString .= "{$upper} – {$cnt->count()}, ";

            $reasonsArr = array_unique($cnt->pluck('burn_object_id')->toArray());

            /*рисуем подпункты*/
            if($cnt->count() != 0 && count($reasonsArr)){
                $innerIterator = 1;

                foreach (FireObject::whereIn('id', $reasonsArr)->get() as $fireObject) {

                    $burntFireCount = $this->data['cards101']->filter(function ($event) use($fireObject, $reason) {
                        return $event->burn_object_id == $fireObject->id && $event->trip_result_id == $reason->id;
                    })->count();

                    $guSpiasrString .= "{$fireObject->name} – $burntFireCount, ";

                    $innerIterator++;
                }
            }

        }

        if($this->data['poisoningCount'] != '0/0') {

            $guSpiasrString .= "случаи отравления – {$this->data['poisoningCount']}, ";
            $guSpiasrString .= "отравление угарным газом – {$this->data['carbonPoisoningCount']}, ";
            $guSpiasrString .= "отравление природным газом – {$this->data['naturalPoisoningCount']}, ";
        }

        $subIndex = 1;

        if ($this->data['suicideCount'] && $this->data['suicideCount'] !== '0/0') {

            $guSpiasrString .= "попытка суицида – {$this->data['suicideCount']}, ";

            $subIndex++;
        }

        if ($this->data['saved_count'] && $this->data['saved_count'] !== '0/0') {

            $guSpiasrString .= "спасено людей – {$this->data['saved_count']}, ";

            $subIndex++;
        }

        if ($this->data['evacuated_count'] && $this->data['evacuated_count'] !== '0/0') {

            $guSpiasrString .= "эвакуировано людей – {$this->data['evacuated_count']}, ";

            $subIndex++;
        }

        if ($this->data['gptBurnsCount'] && $this->data['gptBurnsCount'] !== '0/0') {

            $guSpiasrString .= "получили ожоги – {$this->data['gptBurnsCount']}, ";

            $subIndex++;
        }

        if ($this->data['dead_count'] && $this->data['dead_count'] !== '0/0') {

            $guSpiasrString .= "гибель людей – {$this->data['dead_count']}, ";

            $subIndex++;
        }

        if ($this->data['hospitalizedCount'] && $this->data['hospitalizedCount'] !== '0/0') {

            $guSpiasrString .= "госпитализировано – {$this->data['hospitalizedCount']}, ";
        }

        $this->addParagraph($section, "{$index}. ГУ «СП и АСР»: ", $guSpiasrString);

        $index++;

        $this->addParagraph($section, "{$index}. УКС: Всего поступивших звонков на «112» - ", ($this->data['call_info']->count_112 ?? 0). ", «101» - ".($this->data['call_info']->count_101 ?? 0) . ", «109» - ".($this->data['call_info']->count_109 ?? 0));

        $strAircraft = '';

        foreach ($this->data['air_rescue_report_tech'] as $air_rescue_report_tech) {
            $strAircraft .= $air_rescue_report_tech->aircraft->full_name.', ';
        }

        if(!$strAircraft) {
            $strAircraft .= 'Строевая записка Казавиаспаса не заполнена на указанную дату';
        }

        $index++;

        $this->addParagraph($section, "$index. Казавиаспас: в аэропорту Боролдай в режиме дежурства: ", $strAircraft);
        $index++;



        if ($this->data['siren_speech_tech']) {
            $this->data['siren_speech_tech']->total = $this->data['siren_speech_tech']->total ? $this->data['siren_speech_tech']->total : 0;
            $this->data['siren_speech_tech']->motor = $this->data['siren_speech_tech']->motor ? $this->data['siren_speech_tech']->motor : 0;
            $this->data['siren_speech_tech']->sst = $this->data['siren_speech_tech']->sst ? $this->data['siren_speech_tech']->sst : 0;
            $this->data['siren_speech_tech']->broken = $this->data['siren_speech_tech']->broken ? $this->data['siren_speech_tech']->broken : 0;
            $this->data['siren_speech_tech']->demounted = $this->data['siren_speech_tech']->demounted ? $this->data['siren_speech_tech']->demounted : 0;
            $this->data['siren_speech_tech']->inactive = $this->data['siren_speech_tech']->inactive ? $this->data['siren_speech_tech']->inactive : 0;

            $sirenSpeechTechString = '';
            $sirenSpeechTechString .= "всего – {$this->data['siren_speech_tech']->total}, ";
            $sirenSpeechTechString .= "С-40 (моторные) – {$this->data['siren_speech_tech']->motor}, ";
            $sirenSpeechTechString .= "СРУ – {$this->data['siren_speech_tech']->sst}: ";
            $sirenSpeechTechString .= "из них в нерабочем – {$this->data['siren_speech_tech']->broken}, ";
            $sirenSpeechTechString .= "{$this->data['siren_speech_tech']->demounted} из которых демонтированы, ";
            $sirenSpeechTechString .= "на {$this->data['siren_speech_tech']->inactive} ремонтные работы:";

            $this->addParagraph($section, "$index. Данные по СРУ: ", $sirenSpeechTechString);

            foreach ($this->data['siren_speech_tech']->items as $key => $siren_item) {
                $key++;

                $this->addParagraph($section, "{$key}.", $siren_item->text, ['indentation' => ['left' => 540]]);
            }
        }
        else {
            $this->addParagraph($section, "$index. Данные по СРУ: ", "не зарегистрированно");
        }

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
