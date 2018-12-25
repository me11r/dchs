<?php


namespace App\Services\ReportExport;

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

class DailyWordExport
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
            'ГОСУДАРСТВЕННОЕ УЧРЕЖДЕНИЕ «СЛУЖБА ПОЖАРОТУШЕНИЯ И',
            $headerFontStyle,
            ['align' => Jc::CENTER]
        );
        $section->addText(
            'АВАРИЙНО-СПАСАТЕЛЬНЫХ РАБОТ» ДЧС г. АЛМАТЫ КЧС МВД РК',
            $headerFontStyle,
            ['align' => Jc::CENTER]
        );

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
            'ОПЕРАТИВНАЯ ИНФОРМАЦИЯ',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            'по пожарам, произошедшим на территории',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            'г. Алматы с ' . $this->data['dates']['hour'] . ' час. ' . $this->data['dates']['minutes'] . ' мин. ' . $this->data['dates']['from'] . 'г. до ' . $this->data['dates']['hour'] . ' час. ' . $this->data['dates']['minutes'] . ' мин. ' . $this->data['dates']['to'] . 'г.',
            $generalBoldFontStyle,
            ['align' => Jc::CENTER]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'За дежурные сутки зарегистрировано – ' . $this->data['allCount'] . ' выездов, из них:',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '1.Пожары – ' . $this->data['burntFireCount'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '1.1. Жилой сектор – ' . $this->data['livingSectorCount'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'а) жилой дом (квартира) – ' . $this->data['livingSectorHomeCount'] . "; " . "б) надворные постройки – ".$this->data['livingSectorOutdoorCount'].';',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '1.2. Транспорт – ' . $this->data['burntTransportCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            '1.3. Прочие объекты пожаров – ' . $this->data['burntOtherCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            '2. Случаи горения, не подлежащие учету как пожары – ' . (
                $this->data['burntShortCircuitFireCount'] +
                $this->data['burntRubbishFireCount'] +
                $this->data['burntKitchenFireCount'] +
                $this->data['burntDryThingsFireCount']
            ),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '2.1. КЗ – ' . $this->data['burntShortCircuitFireCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            '2.2. Мусор – ' . $this->data['burntRubbishFireCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            '2.3. Пища на газе – ' . $this->data['burntKitchenFireCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );


        $section->addText(
            '2.4. Сухостой – ' . $this->data['burntDryThingsFireCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $reasons = TripResult::whereIn('name', [
            'Ложный',
            'АСР',
            'Технологический процесс',
            'Бдительность населения',
            'Случаи пожаров трансп.средств в результате ДТП',
            'Загорание бесхозных зданий, бесхозных транспортных средств',
            'Кровельные, битумные, сварочные работы',
            'срабатывание сигнализации',
            'Область',
        ])
            ->get();


        $iterator = 3;
        foreach ($reasons as $reason) {

            $cnt = $this->data['tickets']->filter(function ($event) use ($reason) {
                return $event->trip_result_id == $reason->id;
            })->count();

            $upper = ucfirst($reason->name);

            $section->addText(
                $iterator.". {$upper} – " . $cnt,
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );

            $iterator++;
        }


        /*$section->addText(
            '3. Ложный – ' . count($this->data['falseCall']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '4. АСР – ' . count($this->data['asr']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '5. Кровельные, битумные, сварочные работы – ' . count($this->data['workFire']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '6. Бдительность населения – ' . count($this->data['peopleCall']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '7. Загорание бесхозных зданий, бесхозных транспортных средств – ' . count($this->data['orphanFire']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '8. Прочие случаи загорания – ' . count($this->data['otherFire']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '9. Область – ' . count($this->data['regionFire']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '10. Технологический процесс – ' . count($this->data['technologyFire']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '11. Самовозгорание пирофорных соединений, без последствий и ущерба – ' . count($this->data['pyrophoricFire']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );*/


        /*$section->addText(
            'Cрабатывание сигнализации - ' . count($this->data['alarm']['items']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );*/

        $reasons = TripResult::whereIn('name', [
            'Пожары, в рез-те авиа, ж/д аварии, тер.актов и пр., землетрясения',
            'Покушение на самоубийство',
            'Вспышки и разряды стат.электричества'
        ])
            ->get();

        foreach ($reasons as $reason) {
            $cnt = $this->data['tickets']->filter(function ($event) use ($reason) {
                return $event->trip_result_id == $reason->id;
            })->count();

            $upper = ucfirst($reason->name);

            $section->addText(
                "{$upper} – " . $cnt,
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
        }

//        $section->addText(
//            'Пожары, в рез-те авиа, ж/д аварии, тер.актов и пр., землетрясения - ' . count($this->data['airFire']['items']),
//            $generalBoldFontStyle,
//            ['align' => Jc::BOTH]
//        );
//        $section->addText(
//            'Покушение на самоубийство - ' . count($this->data['suicide']['items']),
//            $generalBoldFontStyle,
//            ['align' => Jc::BOTH]
//        );
//        $section->addText(
//            'Вспышки и разряды стат.электричества - ' . count($this->data['dischargesElectr']['items']),
//            $generalBoldFontStyle,
//            ['align' => Jc::BOTH]
//        );

        $section->addText(
            '12. Случаи отравления - ' . $this->data['poisoningCount'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '12.1. Отравление угарным газом – ' . $this->data['carbonPoisoningCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );
        $section->addText(
            '12.2. Отравление природным газом – ' . $this->data['naturalPoisoningCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            '13. Сведенеия по людям/детям: ' . ($this->data['suicideCount'] + $this->data['rescuedCount'] +
                $this->data['evacCount'] + $this->data['gptBurnsCount'] + $this->data['peopleDeathCount'] +
                $this->data['childrenDeathCount'] + $this->data['hospitalizedCount']),
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '13.1. Попытка суицида - ' . $this->data['suicideCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );
        $section->addText(
            '13.2. Спасено людей – ' . $this->data['rescuedCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );
        $section->addText(
            '13.3. Эвакуировано людей – ' . $this->data['evacCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );
        $section->addText(
            '13.4. Получили ожоги – ' . $this->data['gptBurnsCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );
        $section->addText(
            '13.5. Гибель людей – ' . $this->data['peopleDeathCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );
        $section->addText(
            '13.6. Гибель детей – ' . $this->data['childrenDeathCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );
        $section->addText(
            '13.7. Госпитализировано – ' . $this->data['hospitalizedCount'],
            $generalBoldFontStyle,
            ['indentation' => ['left' => 540]]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        foreach ($this->data['tripResults'] as $title => $tripResult) {
            $section->addText(
                mb_strtoupper($title),
                $generalBoldUnderlineFontStyle,
                ['align' => Jc::CENTER]
            );
            foreach ($tripResult as $key => $item) {
                $number = ($key + 1).".";
                /**
                 * TODO временное решение
                 */
                $item['analytics'] = "<div><span style='float: left;font-weight: bold; margin-right: 10px;'>{$number}</span>".$item['analytics']."</div> <br/>";
                $item['analytics'] = str_replace('<br>', "<br/>", $item['analytics']);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $item['analytics'], false, false);
//                $textRun->addText(' (заявитель: ' . $item['caller_name'] . ', тел: ' . $item['caller_phone'] . '. ', $simpleFontStyle, self::$noPaddingPS);
//                $textRun->addText($item['pre_information'] . ' S=' . $item['square_max'] . ' м2. ', $generalBoldItalicUnderlineFontStyle, self::$noPaddingPS);
//                if ($item['first_dept_arrived']) {
//                    $textRun->addText('(заявитель: ' . $item['first_dept_arrived']['name'] . ' (' . $item['first_dept_arrived']['tech_dept'] . ') на ' . $item['first_dept_arrived']['vehicle'] . ' прибыло в ' . $item['first_dept_arrived']['arrive_time'] . ' (' . $item['first_dept_arrived']['on_way_time'] . '). ', $simpleFontStyle, self::$noPaddingPS);
//                }
//                $textRun->addText('Пожар локализован в ' . $item['loc_time'] . ' и ликвидирован в ' . $item['liqv_time'] . ', ' . $item['chronology_str'] . '. На место пожара выезжал л/с: ' . $item['depts_out'], $simpleFontStyle, self::$noPaddingPS);
//                if ($item['service_plans_str']) {
//                    $textRun->addText('На место ЧС выезжали службы заимодействия: ' . $item['service_plans_str'] . '. ', $simpleFontStyle, self::$noPaddingPS);
//                }
//                $textRun->addText('Материал зарегистрирован в КУИ №' . $item['kui'] . ' от ' . $item['date2'], $simpleFontStyle, self::$noPaddingPS);
            }
//            $section->addText(
//                '',
//                $generalBoldFontStyle,
//                ['align' => Jc::BOTH]
//            );
        }
        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'ДСПТ:',
            $generalBoldUnderlineFontStyle,
            ['align' => Jc::BOTH]
        );

        $fireDeptChecks = $this->data['fireDeptChecks']->where('is_dspt', '=', 1);
        foreach ($fireDeptChecks->all() as $key => $check) {
            $textRun = $section->addTextRun(self::$noPaddingPS);
            $textRun->addText(
                ($key + 1) . '. ' . ($check['fire_department'] ? $check['fire_department']['title'] : '') . ': ',
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
            $textRun->addText(
                $check['time_begin'] . ' - ' . $check['time_end'] . ' ' . $check['responsible_person'] . ' ' . $check['note'],
                $simpleFontStyle,
                ['align' => Jc::BOTH]
            );
        }

        $section->addText(
            'Итого: ' . $fireDeptChecks->count(),
            $generalBoldUnderlineFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );


        $section->addText(
            'Проверка частей:',
            $generalBoldUnderlineFontStyle,
            ['align' => Jc::BOTH]
        );

        $fireDeptChecks = $this->data['fireDeptChecks']->where('is_dspt', '=', 0);
        foreach ($fireDeptChecks->all() as $key => $check) {
            $textRun = $section->addTextRun(self::$noPaddingPS);
            $textRun->addText(
                ($key + 1) . '. ' . ($check['fire_department'] ? $check['fire_department']['title'] : '') . ': ',
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
            $textRun->addText(
                $check['time_begin'] . ' - ' . $check['time_end'] . ' ' . $check['responsible_person'] . ' ' . $check['note'],
                $simpleFontStyle,
                ['align' => Jc::BOTH]
            );
        }

        $section->addText(
            'Итого: ' . $fireDeptChecks->count(),
            $generalBoldUnderlineFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            'Расстановка на ' . $this->data['dates']['from'] . 'г: ' . (count($this->data['arrangementYesterday']) === 0 ? 'нет' : ''),
            $generalBoldUnderlineFontStyle,
            ['align' => Jc::BOTH]
        );
        foreach ($this->data['arrangementYesterday'] as $key => $item) {
            $textRun = $section->addTextRun(self::$noPaddingPS);
            $textRun->addText(
                ($key + 1) . '. ' . ($item['fire_department'] ? $item['fire_department']['title'] : '') .($item['department'] ? "({$item['department']})" : '') . ' ',
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
            $textRun->addText(
//                $item['note'],
                "начало в {$item->time_begin } {$item->object_name} {$item->direction} {$item->note}" . ($item->date_from ? " c {$item->date_from}" : ''),
                $simpleFontStyle,
                ['align' => Jc::BOTH]
            );
        }


        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            'Расстановка на ' . $this->data['dates']['to'] . 'г: ' . (count($this->data['arrangementToday']) === 0 ? 'нет' : ''),
            $generalBoldUnderlineFontStyle,
            ['align' => Jc::BOTH]
        );
        foreach ($this->data['arrangementToday'] as $key => $item) {
            $textRun = $section->addTextRun(self::$noPaddingPS);
            $textRun->addText(
                ($key + 1) . '. ' . ($item['fire_department'] ? $item['fire_department']['title'] : '') . ' ',
//                ($key + 1) . '. ' . ($item->staff ? $item->staff->department->title : '') . ' ',
                $generalBoldFontStyle,
                ['align' => Jc::BOTH]
            );
            $textRun->addText(
//                $item['note'],
                "начало в {$item->time_begin } {$item->object_name} {$item->direction} {$item->note}" . ($item->date_from ? " c {$item->date_from}" : ''),
                $simpleFontStyle,
                ['align' => Jc::BOTH]
            );
        }


        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '',
            $generalBoldFontStyle8,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            'Неисправная техника: ',
            $generalBoldUnderlineFontStyle8,
            ['align' => Jc::BOTH]
        );
        foreach ($this->data['tech'] as $item) {
            foreach ($item['formation_tech_items'] as $tech) {
                if ($tech['status'] === 'repair') {
                    $section->addText(
                        $item['department']['title'] . ' ' . $tech['vehicle']['name'] . ' ' . $tech['vehicle']['base'] . ' ' . $tech['comment'],
                        $generalBoldFontStyle8,
                        ['align' => Jc::BOTH]
                    );
                }
            }
        }

        $section->addText(
            '',
            $generalBoldFontStyle8,
            ['align' => Jc::BOTH]
        );
        $text = '';
        $count_tech = 0;
        foreach ($this->data['inactive_tech_cnt'] as $name => $count) {
            $count_tech++;
            $text .= $name . '-' . $count . (count($this->data['inactive_tech_cnt']) !== $count_tech ? ', ' : '.');
        }
        $section->addText(
            'Всего:___________________________' . $text,
            $generalBoldUnderlineFontStyle8,
            ['align' => Jc::BOTH]
        );


        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '',
            $generalBoldFontStyle8,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            '',
            $generalBoldFontStyle8,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            $this->data['footer_first_person']['position'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            $this->data['footer_first_person']['city'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            $this->data['footer_first_person']['post'],
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );
        $section->addText(
            $this->data['footer_first_person']['name'],
            $generalBoldFontStyle,
            ['align' => Jc::END]
        );
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
