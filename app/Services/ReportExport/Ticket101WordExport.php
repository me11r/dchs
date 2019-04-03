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

class Ticket101WordExport
{

    use CommonExportTools;

    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * @var FormationReport
     */
    private $formationReport;

    /**
     * @var Collection
     */
    private $departments;

    /**
     * @var Collection
     */
    private $people;

    /**
     * @var Collection
     */
    private $tech;

    /**
     * @var array
     */
    private $sumPeople;

    /**
     * @var array
     */
    private $data;

    public static $noPaddingPS = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

    public static $sortedDepartmentNames = [
        'ПЧ-1',
        'ПЧ-2',
        'ПЧ-3',
        'ПЧ-4',
        'ПЧ-5',
        'ПЧ-6',
        'СПЧ-7',
        'СПЧ-8',
        'СПЧ-9',
        'ПЧ-10',
        'СПЧ-11',
        'ПЧ-12',
        'СПЧ-14',
        'СПЧ-15',
        'ПП-16',
        'ПП-17',
        'СО',
        'ПЧ-13',
    ];

    public static $sortedDepartmentNamesBottom = [
        'ПЧ-1',
        'ПЧ-2',
        'ПЧ-3',
        'ПЧ-4',
        'ПЧ-5',
        'ПЧ-6',
        'СПЧ-7',
        'СПЧ-8',
        'СПЧ-9',
        'ПЧ-10',
        'СПЧ-11',
        'ПЧ-12',
        'ПЧ-13',
        'СПЧ-14',
        'СПЧ-15',
        'ПП-16',
        'ПП-17',
        'СО',
    ];

    public static $exceptions = [
        'crb',
        'gdzs_base',
        'doctor',
        'tulpar1',
        'tulpar2',
        'tulpar3',
        'tulpar4',
        'tulpar5',
        'tulpar7',
        'tulpar8',
        'tulpar10',
        'kshm',
        'ipl_zhalyn'
    ];

    public function __construct(
        FormationReport $formationReport,
        Collection $departments,
        Collection $people,
        Collection $tech,
        array $sumPeople,
        array $data
    )
    {
        $this->defineDomPdfWriter();

        $this->phpWord = new PhpWord();
        $this->formationReport = $formationReport;
        $this->departments = $departments;
        $this->people = $people;
        $this->tech = $tech;
        $this->sumPeople = $sumPeople;
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
        $section = $this->getNewSection();

        $this->addFirstPageTopData($section);

        $section->addTextBreak(1, ['size' => 1]);

        $this->addFirstTable($section);

        $section->addPageBreak();

        $this->addSecondTable($section);

        $this->addBottomText($section);

    }

    private function getSickLeavePeople()
    {
        $people = $this->people;
        $result = [];
        foreach ($people as $dept_id => $personSummary) {

            $fireDept = FireDepartment::find($dept_id);

            if(!$fireDept) {
                continue;
            }

            $sickLeavePpl = $personSummary->formation_person_items()->rank(['sick_leave','sick'])->get();

            $result[$fireDept->title] = [
                'sick_leave' => $sickLeavePpl,
            ];
        }

        return $result;
    }

    private function peopleOdInactive()
    {
        $od_people_inactive = [
            'vacation' => 'отпуск',
            'other' => 'другое',
            'study' => 'учебный',
            'maternity' => 'декрет',
            'business_trip' => 'командировка',
            'sick' => 'больничный'
        ];

        $od_depts_names = [
            'ipl' => 'ИПЛ',
            'kshm' => 'КШМ',
            'dspt' => 'ДСПТ',
        ];

        $queryArr = [];

        foreach ($od_depts_names as $name => $title) {
            foreach ($od_people_inactive as $inactive_item_name => $inactive_item) {
                $combineName = "{$name}_{$inactive_item_name}";
                $queryArr[] = $combineName;
            }
        }

        $reportId = $this->formationReport->id;
        $result = FormationOdPersonItem::whereHas('report.report', function($q) use ($reportId){
            $q->where('id', $reportId);
        })
            ->whereIn('rank', $queryArr)
            ->get();

        $res = [];

        foreach ($od_depts_names as $name => $title) {
            foreach ($od_people_inactive as $inactive_item_name => $inactive_item) {
                $combineName = "{$name}_{$inactive_item_name}";
                $res[$title][$inactive_item] = $result->filter(function ($q) use ($combineName){
                    return $q->rank === $combineName;
                })->map(function ($qq) {
                    return $qq->staff->name ?? null;
                })->toArray();
            }
        }

        return $res;
    }

    private function peopleByDept()
    {
        $people = $this->people;
        $result = [];
        foreach ($people as $dept_id => $personSummary) {

            $fireDept = FireDepartment::find($dept_id);

            if(!$fireDept) {
                continue;
            }

            $vacationPpl = $personSummary->formation_person_items()->rank('vacation')->get();
            $studyPpl = $personSummary->formation_person_items()->rank('study')->get();
            $other_reasons = $personSummary->formation_person_items()->rank('other')->get();
            $dispatchersPpl = $personSummary->formation_person_items()->rank('dispatchers')->get();
            $sickPpl = $personSummary->formation_person_items()->rank('sick')->get();
            $sickLeavePpl = $personSummary->formation_person_items()->rank('sick_leave')->get();
            $businessPpl = $personSummary->formation_person_items()->rank('business_trip')->get();
            $maternityPpl = $personSummary->formation_person_items()->rank('maternity')->get();

            /*ОД*/
            $gdzs_base = $personSummary->formation_person_items_od()->rank('gdzs_base')->get();
            $crb = $personSummary->formation_person_items_od()->rank('crb')->get();
            $tulpar1 = $personSummary->formation_person_items_od()->rank('tulpar1')->get();
            $tulpar2 = $personSummary->formation_person_items_od()->rank('tulpar2')->get();
            $tulpar3 = $personSummary->formation_person_items_od()->rank('tulpar3')->get();
            $tulpar4 = $personSummary->formation_person_items_od()->rank('tulpar4')->get();
            $tulpar5 = $personSummary->formation_person_items_od()->rank('tulpar5')->get();
            $tulpar7 = $personSummary->formation_person_items_od()->rank('tulpar7')->get();
            $tulpar8 = $personSummary->formation_person_items_od()->rank('tulpar8')->get();
            $tulpar10 = $personSummary->formation_person_items_od()->rank('tulpar10')->get();
            $kshm = $personSummary->formation_person_items_od()->rank('kshm')->get();
            $ipl_zhalyn = $personSummary->formation_person_items_od()->rank('ipl_zhalyn')->get();
            $doctor = $personSummary->formation_person_items_od()->rank('doctor')->get();
            $dspt = $personSummary->formation_person_items_od()->rank('dspt')->get();
            $cpps = $personSummary->formation_person_items_od()->rank('cpps')->get();
            $edds = $personSummary->formation_person_items_od()->rank('edds')->get();
            $ipl = $personSummary->formation_person_items_od()->rank('ipl')->get();
            $water_supply = $personSummary->formation_person_items_od()->rank('water_supply')->get();
            $senior_communication_master = $personSummary->formation_person_items_od()->rank('senior_communication_master')->get();

            /*пч-13*/
            $post1_president_residence = $personSummary->formation_person_items()->rank('post1_president_residence')->get();
            $post2_president_archive = $personSummary->formation_person_items()->rank('post2_president_archive')->get();
            $post3_state_archive = $personSummary->formation_person_items()->rank('post3_state_archive')->get();
            $post4_national_bank = $personSummary->formation_person_items()->rank('post4_national_bank')->get();

            $result[$fireDept->title] = [
                'vacation' => $vacationPpl->map(function ($item) {
                    return ($item->staff->initials ?? null) . " $item->comment" . ($item->date_from ? " с $item->date_from" : '') . ($item->date_to ? " по $item->date_to" : ''). " ({$item->staff->rank} {$item->staff->position})";
                })->toArray(),
                'study' => $studyPpl->map(function ($item) {
                    return ($item->staff->initials ?? null) . " $item->comment" . ($item->date_from ? " с $item->date_from" : '') . ($item->date_to ? " по $item->date_to" : ''). " ({$item->staff->rank} {$item->staff->position})";
                })->toArray(),
                'maternity' => $maternityPpl->map(function ($item) {
                    return ($item->staff->initials ?? null) . " $item->comment" . ($item->date_from ? " с $item->date_from" : '') . ($item->date_to ? " по $item->date_to" : ''). " ({$item->staff->rank} {$item->staff->position})";
                })->toArray(),
                'dispatchers' => $dispatchersPpl->map(function ($item) {
                    return ($item->staff->initials ?? null) . "({$item->staff->rank} {$item->staff->position})";
                })->toArray(),
                'sick' => $sickPpl->map(function ($item) {
                    return ($item->staff->initials ?? null) . " $item->comment" . ($item->date_from ? " с $item->date_from" : '') . ($item->date_to ? " по $item->date_to" : ''). " ({$item->staff->rank} {$item->staff->position})";
                })->toArray(),
                'sick_leave' => $sickLeavePpl->map(function ($item) {
                    return ($item->staff->initials ?? null) . " $item->comment" . ($item->date_from ? " с $item->date_from" : '') . ($item->date_to ? " по $item->date_to" : ''). " ({$item->staff->rank} {$item->staff->position})";
                })->toArray(),
                'business_trip' => $businessPpl->map(function ($item) {
                    return ($item->staff->initials ?? null) . " $item->comment" . ($item->date_from ? " с $item->date_from" : '') . ($item->date_to ? " по $item->date_to" : ''). " ({$item->staff->rank} {$item->staff->position})";
                })->toArray(),
                'other_reasons' => $other_reasons->map(function ($item) {
                    return ($item->staff->initials ?? null) . " $item->comment" . ($item->date_from ? " с $item->date_from" : '') . ($item->date_to ? " по $item->date_to" : ''). " ({$item->staff->rank} {$item->staff->position})";
                })->toArray(),

                'gdzs_base' => $gdzs_base->map(function ($item) {
                    return $item->staff->name;
                })->toArray(),

                'crb' => $crb->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'post1_president_residence' => $post1_president_residence->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'post2_president_archive' => $post2_president_archive->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'post3_state_archive' => $post3_state_archive->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'post4_national_bank' => $post4_national_bank->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'tulpar1' => $tulpar1->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'tulpar2' => $tulpar2->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'tulpar3' => $tulpar3->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'tulpar4' => $tulpar4->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'tulpar5' => $tulpar5->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'tulpar7' => $tulpar7->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'tulpar8' => $tulpar8->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'tulpar10' => $tulpar10->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'kshm' => $kshm->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),
                'ipl_zhalyn' => $ipl_zhalyn->map(function ($item) {
                    return ($item->staff->name ?? null) . ' '.$item->gsm_count;
                })->toArray(),

                'doctor' => $doctor->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'dspt' => $dspt->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'cpps' => $cpps->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'edds' => $edds->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'ipl' => $ipl->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'water_supply' => $water_supply->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'senior_communication_master' => $senior_communication_master->map(function ($item) {
                    return $item->staff->name ?? null;
                })->toArray(),
                'guard_number_id' => $sickLeavePpl->map(function ($item) {
                    return $item->guard_number_id;
                })->toArray(),
            ];
        }

        return $result;
    }

    private function getRepairedTech()
    {
        $repairedTech = $this->formationReport->tech_reports->map(function ($item) {
            return $item->items()->status('repair')->get()->toArray();
        });

        $repairedTech = $repairedTech->filter(function ($item) {
            return count($item);
        });

        $result = [];
        foreach ($repairedTech as $arr) {
            foreach ($arr as $item) {
                $vehicle = Vehicle::find($item['vehicle_id']);
                $result[$vehicle->fireDepartment->title][] = $vehicle->name . ' ' . ($vehicle->base ? "($vehicle->base) " : '') . $item['comment']. ($item['date_from_formatted'] ? " c {$item['date_from_formatted']}" : '');
            }
        }

        return $result;
    }

    private function getRepairedDvr()
    {
        $dvrs = $this->data['inactive_dvrs'];

        $result = [];
        foreach ($dvrs as $item) {
            $vehicle = Vehicle::find($item['vehicle_id']);
            $status = $item['status_title'];
            $result[$vehicle->fireDepartment->title][] = $vehicle->name . ' ' . ($vehicle->base ? "($vehicle->base) " : '')."($status)" .' - '. $item['comment'];
        }

        return $result;
    }

    private function addBottomText(Section $section)
    {
        $people = $this->peopleByDept();
        $people = array_replace(array_flip(self::$sortedDepartmentNamesBottom), $people); // сортируем

        foreach ($people as $key => $persons) {
            if(!is_array($persons)){
                unset($people[$key]);
            }
        }

        $sickPeople = $this->getSickLeavePeople();
        $sickPeople = array_replace(array_flip(self::$sortedDepartmentNamesBottom), $sickPeople); // сортируем

        $repairedTech = $this->getRepairedTech();
        $repairedDvr = $this->getRepairedDvr();

        $odInactivePeople = $this->peopleOdInactive();

        $formationCard101Others = $this->data['formationCard101Others'];

        $sections = [
            'dispatchers' => 'Диспетчера: ',
            'vacation' => 'Отпуск: ',
            'maternity_title' => '',
            'sick' => 'Больничный: ',
            'business_trip' => 'Командировка: ',
            'other_reasons' => 'Другие причины: ',
            'crb' => 'ЦРБ: ',
            'gdzs_base' => 'База ГДЗС: ',
            'doctor' => 'Врач: ',
            'just_title' => 'Посты ПЧ-13: ',
            'just_title2' => 'Оперативные дежурные автомашины: ',
            'tulpar1' => 'Тулпар-1: ',
            'tulpar2' => 'Тулпар-2: ',
            'tulpar3' => 'Тулпар-3: ',
            'tulpar4' => 'Тулпар-4: ',
            'tulpar5' => 'Тулпар-5: ',
            'tulpar7' => 'Тулпар-7: ',
            'tulpar8' => 'Тулпар-8: ',
            'tulpar10' => 'Тулпар-10: ',
            'kshm' => 'КШМ: ',
            'ipl_zhalyn' => 'ИПЛ «Жалын»: '
//            'sick_leave' => 'Больничные: ',
        ];

        $generalFontStyle = ['name' => 'Times New Roman', 'size' => 8];
        $generalBoldFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true];
        $generalBoldItalicFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true, 'italic' => true];
        $generalBoldItalicUnderlineFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true, 'italic' => true, 'underline' => 'single'];
        $redFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'bold' => true, 'color' => 'red', 'underline' => 'single'];

        $section->addText(
            '',
            $generalBoldFontStyle,
            ['align' => Jc::BOTH]
        );

        foreach ($sections as $sectionName => $title) {
            if ($sectionName === 'just_title') {
                $posts = [
                    'post1_president_residence' => '1 пост',
                    'post2_president_archive' => '2 пост',
                    'post3_state_archive' => '3 пост',
                    'post4_national_bank' => '4 пост'
                ];
                $postNames = [
                    'post1_president_residence' => 'Резиденция Президента РК',
                    'post2_president_archive' => 'Архив Президента РК',
                    'post3_state_archive' => 'Государственный архив РК',
                    'post4_national_bank' => 'Национальный Банк РК'
                ];

                $section->addText(
                    $title,
                    $redFontStyle,
                    array_merge(['align' => Jc::BOTH], self::$noPaddingPS)
                );

                foreach ($people as $fireDept => $persons) {
                    foreach (['post1_president_residence', 'post2_president_archive', 'post3_state_archive', 'post4_national_bank'] as $subKey) {
                        if (isset($persons[$subKey]) && \count($persons[$subKey])) {
                            $textRun = $section->addTextRun(self::$noPaddingPS);
                            $textRun->addText("$posts[$subKey]:\t\t", $generalBoldFontStyle, self::$noPaddingPS);
                            $textRun->addText("$postNames[$subKey] – ", $generalFontStyle, self::$noPaddingPS);
                            $textRun->addText(implode(', ', array_unique($persons[$subKey])), $generalFontStyle, self::$noPaddingPS);
                        }
                    }
                }

                /*$section->addText(
                    '',
                    $generalFontStyle,
                    self::$noPaddingPS
                );*/
            }
            else {

                if (!in_array($sectionName, self::$exceptions)) {
                    $section->addText(
                        $title,
                        $redFontStyle,
                        array_merge(['align' => Jc::BOTH], self::$noPaddingPS)
                    );
                }

                foreach ($people as $fireDept => $persons) {
                    if ((isset($persons[$sectionName]) && count($persons[$sectionName])) || ($sectionName == 'vacation' && (count($persons['maternity']) || count($persons['study'])) )) {

                        $peopleByComma = count($persons[$sectionName]) ? implode(', ', array_unique($persons[$sectionName])) : '-';

                        $textRun = $section->addTextRun(self::$noPaddingPS);
                        if (in_array($sectionName, self::$exceptions)) {
                            $textRun->addText(
                                $title,
                                $redFontStyle,
                                array_merge(['align' => Jc::BOTH], self::$noPaddingPS)
                            );
                        }
                        if ($fireDept != 'ОД') {
                            $textRun->addText("$fireDept:\t\t", $generalBoldFontStyle, self::$noPaddingPS);
                        }

                        if ($sectionName == 'vacation') {
                            if (count($persons['vacation'])) {
                                $prefix = 'Трудовой-';
                                $textRun->addText($prefix, $generalBoldFontStyle, self::$noPaddingPS);
                                $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);
                            }
                            if (count($persons['study'])) {
                                $prefix = 'Учебный-';
                                $textRun = $section->addTextRun(['indentation' => ['left' => 1430]]);
                                $textRun->addText($prefix, $generalBoldFontStyle, self::$noPaddingPS);
                                $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);
                            }
                            if (count($persons['maternity'])) {
                                if (count($persons['vacation'])) {
                                    $textRun = $section->addTextRun(['indentation' => ['left' => 1430]]);
                                }
                                $prefix = 'Декрет-';
                                $textRun->addText($prefix, $generalBoldItalicUnderlineFontStyle, self::$noPaddingPS);

                                $peopleByCommaMaternity = count($persons['maternity']) ? implode(', ', array_unique($persons['maternity'])) : '-';
                                $textRun->addText($peopleByCommaMaternity, $generalFontStyle, self::$noPaddingPS);
                            }
                        }
                        else {
                            $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);
                        }
                    }
                    elseif (isset($persons[$sectionName]) && !count($persons[$sectionName]) && $fireDept == 'ОД'){
                        if (in_array($sectionName, self::$exceptions)) {
                            $textRun = $section->addTextRun(self::$noPaddingPS);
                            $textRun->addText(
                                $title,
                                $redFontStyle,
                                array_merge(['align' => Jc::BOTH], self::$noPaddingPS)
                            );
                            $peopleByComma = count($persons[$sectionName]) ? implode(', ', array_unique($persons[$sectionName])) : '-';
                            $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);

                        }
                    }
                }

                if($sectionName === 'vacation') {
                    foreach ($odInactivePeople as $depTitle => $odInactivePersons) {
                        foreach ($odInactivePersons as $inactiveType => $odInactivePerson) {
                            if(count($odInactivePerson) && $inactiveType == 'отпуск'){
                                $peopleByComma = implode(', ', $odInactivePerson);
                                $textRun = $section->addTextRun(self::$noPaddingPS);
                                $textRun->addText("$depTitle $inactiveType:\t\t", $generalBoldFontStyle, self::$noPaddingPS);
                                $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);
                            }
                        }
                    }
                }

                if($sectionName === 'business_trip') {
                    foreach ($odInactivePeople as $depTitle => $odInactivePersons) {
                        foreach ($odInactivePersons as $inactiveType => $odInactivePerson) {
                            if(count($odInactivePerson) && $inactiveType == 'командировка'){
                                $peopleByComma = implode(', ', $odInactivePerson);
                                $textRun = $section->addTextRun(self::$noPaddingPS);
                                $textRun->addText("$depTitle $inactiveType:\t\t", $generalBoldFontStyle, self::$noPaddingPS);
                                $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);
                            }
                        }
                    }
                }

                if($sectionName === 'sick') {
                    foreach ($odInactivePeople as $depTitle => $odInactivePersons) {
                        foreach ($odInactivePersons as $inactiveType => $odInactivePerson) {
                            if(count($odInactivePerson) && $inactiveType == 'больничный'){
                                $peopleByComma = implode(', ', $odInactivePerson);
                                $textRun = $section->addTextRun(self::$noPaddingPS);
                                $textRun->addText("$depTitle $inactiveType:\t\t", $generalBoldFontStyle, self::$noPaddingPS);
                                $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);
                            }
                        }
                    }
                }
            }
        }

        $date = Carbon::parse($this->formationReport->created_at)
                ->addDay()
                ->format('d-m-Y') . 'г.';

        $section->addText(
            "Расстановка на {$date}: ",
            $redFontStyle,
            ['align' => Jc::BOTH]
        );

        foreach ($formationCard101Others as $key => $item) {
            $textRun = $section->addTextRun(self::$noPaddingPS);
            $index = ++$key;
            $textRun->addText(
                "{$index}.". ($item->fire_department ? $item->fire_department->title : ''). ' ',
                ['name' => 'Times New Roman', 'size' => 8, 'bold' => true],
                ['align' => Jc::BOTH]
            );

            $textRun->addText(
                "начало в {$item->time_begin } {$item->object_name} {$item->direction} {$item->note}" . ($item->date_from ? " c {$item->date_from}" : ''),
                ['name' => 'Times New Roman', 'size' => 8, 'bold' => true],
                ['align' => Jc::BOTH]
            );
        }

        /*$section->addText(
            '',
            ['name' => 'Times New Roman', 'size' => 8, 'bold' => true],
            ['align' => Jc::BOTH]
        );*/

        $section->addText(
            'Неисправная техника',
            $redFontStyle,
            ['align' => Jc::BOTH]
        );

        $techIndex = 1;
        foreach ($repairedTech as $fireDept => $tech) {
//            $tabs = strlen($fireDept) > 5 ? "\t" : "\t\t";
            $tabs = "\t";
            $section->addText(
                "{$techIndex}. {$fireDept}:$tabs" . implode(', ', $tech),
                ['name' => 'Times New Roman', 'size' => 8, 'bold' => true],
                ['align' => Jc::BOTH]
            );
            $techIndex++;
        }

        $inactive_tech_cnt = $this->data['inactive_tech_cnt'];
        $inactive_tech_cnt_str = '';
        foreach ($inactive_tech_cnt as $name => $count) {
            $inactive_tech_cnt_str .= "{$name} - {$count->count()}, ";
        }
        $section->addText(
            "Всего:\t\t" . $inactive_tech_cnt_str,
            $redFontStyle,
            ['align' => Jc::BOTH]
        );

        $section->addText(
            'Неисправные видеорегистраторы',
            $redFontStyle,
            ['align' => Jc::BOTH]
        );

        $techIndex = 1;
        foreach ($repairedDvr as $fireDept => $tech) {
//            $tabs = strlen($fireDept) > 5 ? "\t" : "\t\t";
            $tabs = "\t";
            $section->addText(
                "{$techIndex}. {$fireDept}:$tabs" . implode(', ', $tech),
                ['name' => 'Times New Roman', 'size' => 8, 'bold' => true],
                ['align' => Jc::BOTH]
            );
            $techIndex++;
        }

        $inactive_dvr_cnt = $this->data['inactive_dvrs_cnt'];
        $inactive_dvr_cnt_str = '';
        foreach ($inactive_dvr_cnt as $name => $count) {
            $inactive_dvr_cnt_str .= "{$name} - {$count->count()}, ";
        }
        $section->addText(
            "Всего:\t\t" . $inactive_dvr_cnt_str,
            $redFontStyle,
            ['align' => Jc::BOTH]
        );

        /*$section->addText(
            '',
            ['name' => 'Times New Roman', 'size' => 10, 'bold' => true],
            ['align' => Jc::BOTH]
        );*/

        $section->addText(
            'Больничные:',
            $redFontStyle,
            ['align' => Jc::BOTH]
        );



//        $section->addText(
//            $title,
//            $redFontStyle,
//            array_merge(['align' => Jc::BOTH], self::$noPaddingPS)
//        );
        $guard_numbers = GuardNumber::all();
        foreach ($guard_numbers as $guard_number) {
            $section->addText(
                $guard_number->name.':',
                $generalBoldFontStyle,
                array_merge(['align' => Jc::BOTH], self::$noPaddingPS)
            );

            foreach ($sickPeople as $fireDept => $persons) {
                if ($persons['sick_leave'] && $persons['sick_leave']->count()) {
                    foreach ($persons['sick_leave'] as $person) {
                        if($guard_number->id == $person['guard_number_id']) {
                            $peopleByComma = ($person->staff->initials ?? null) . " $person->comment" . ($person->date_from ? " с $person->date_from" : '') . ($person->date_to ? " по $person->date_to" : ''). "({$person->staff->rank} {$person->staff->position})";
                            $textRun = $section->addTextRun(self::$noPaddingPS);
                            $textRun->addText("$fireDept:\t\t", $generalBoldFontStyle, self::$noPaddingPS);
                            $textRun->addText($peopleByComma, $generalFontStyle, self::$noPaddingPS);
                        }
                    }
                }
            }

            /*$section->addText(
                '',
                $generalFontStyle,
                self::$noPaddingPS
            );*/
        }
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
        // все департаменты
        /** @var FireDepartment $department */
        foreach ($this->departments as $department) {
            if ($department->id !== 13) { // суеверия
                $row = $table->addRow();
                $rowData = $this->getSecondTableRowForDepartment($department);
                foreach ($rowData as $key => $value) {
                    $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
                    if ($key === 0 || $key === (\count(array_keys($rowData)) - 1)) {
                        $fontStyle['bold'] = true;
                    }

                    $cellStyles = [];

                    if ($key > 18 && $key < 22) {

                        $fontStyle['bold'] = true;
                    }

                    if ($key == 19) {
                        $cellStyles = ['borderLeftSize' => 10, 'borderColor' => '000000'];
                    }

                    if ($key == 21) {
                        $cellStyles = ['borderRightSize' => 10, 'borderColor' => '000000'];
                    }


                    $this->addDataCellToRow($row, $value, $cellStyles, $fontStyle, self::$noPaddingPS);
                }
            }
        }

        // сумма
        $row = $table->addRow();
        foreach ($this->getSecondTableSumRow() as $value) {
            $this->addDataCellToRow($row, $value, ['borderSize' => 10, 'borderColor' => '000000'], ['name' => 'Times New Roman', 'size' => 8, 'bold' => true], self::$noPaddingPS);
        }
    }

    private function addSecondTableHeaders(\PhpOffice\PhpWord\Element\Table $table)
    {
        $cellRowSpanV = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanVThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowSpanH = ['vMerge' => 'restart', 'valign' => Jc::CENTER];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = array_merge(['align' => Jc::CENTER, 'valign' => Jc::CENTER], self::$noPaddingPS);

        $table->addRow(700);

        $table->addCell(1200, $cellRowSpanV)->addText('Наименование пожарных подразделений', $hcFontStyle, $hcAlignStyle);
        $table->addCell(9000, ['gridSpan' => 17, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Имеется на автомобилях в боевом расчете', $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText('Пенообразователя в резерве / на складе', $hcFontStyle, $hcAlignStyle);
        $table->addCell(1400, ['gridSpan' => 3, 'align' => Jc::CENTER, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'])->addText('Количество неисправных водоисточников', $hcFontStyle, $hcAlignStyle);
        $table->addCell(800, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER, 'borderLeftSize' => 10, 'borderColor' => '000000'])->addText('В боевом расчете', $hcFontStyle, $hcAlignStyle);
        $table->addCell(800, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('В резерве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText("1 генератор<w:br/>2 дымосос<w:br/>3 гирсы,  ИУП", $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, ['align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText("Видео<w:br/>регистр.", $hcFontStyle, $hcAlignStyle);
        $table->addCell(600, $cellRowSpanV)->addText('Ф.И.О Нач. караула<w:br/>или лица его подменяющего', $hcFontStyle, $hcAlignStyle);

        $table->addRow(1250);

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, ['gridSpan' => 4, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Рукавов', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Лафетн. Ств. стац', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Лафетн. Ств. переносной', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('ГПС-600', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Пурга', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Переносная радиостанция', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Электрофонари', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Прожектора', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('ТОК/Л-1', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Ранцевые аппараты', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Лопаты', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Хлопушки', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Спасательные веревки', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('Пенообразователя', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'])->addText('ПГ', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanVThick)->addText('ПВ', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('бензин', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('дизель', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('бензин', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('дизель', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
//        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowSpanV)->addText('В расчете<w:br/>В резерве<w:br/>Неисправные', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);


        $table->addRow(1250);

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowSpanV)->addText('125мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('75мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('77мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanV)->addText('51мм', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowSpanVThick)->addText('уличный', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanVThick)->addText('объектовый', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
//        $table->addCell(null, $cellRowContinue);
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
        $department13 = null;
        // все департаменты
        /** @var FireDepartment $department */
        foreach ($this->departments as $department) {
            if ($department->id !== 13) { // суеверия
                $row = $table->addRow();
                foreach ($this->getFirstTableRowForDepartment($department) as $key => $value) {
                    $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
                    if ($key <= 16) {
                        $fontStyle['bold'] = true;
                    }

                    $cellStyles = [];
                    if ($key == 8) {
                        $cellStyles = ['borderLeftSize' => 10, 'borderColor' => '000000'];
                    }

                    if ($key == 10 || $key == 13) {
                        $cellStyles = ['borderRightSize' => 10, 'borderColor' => '000000'];
                    }

                    $this->addDataCellToRow($row, $value, $cellStyles, $fontStyle, self::$noPaddingPS);
                }
            } else {
                $department13 = $department;
            }
        }

        // сумма
        $row = $table->addRow();
        foreach ($this->getFirstTableSumRow() as $value) {
            $this->addDataCellToRow($row, $value, ['borderSize' => 10, 'borderColor' => '000000'], ['bold' => true, 'name' => 'Times New Roman', 'size' => 8], self::$noPaddingPS);
        }

        if ($department13) {
            $row = $table->addRow();
            foreach ($this->getFirstTableRowForDepartment($department13) as $key => $value) {
                $fontStyle = ['name' => 'Times New Roman', 'size' => 8];
                if ($key <= 16) {
                    $fontStyle['bold'] = true;
                }

                $cellStyles = [];
                if ($key == 8) {
                    $cellStyles = ['borderLeftSize' => 10, 'borderColor' => '000000'];
                }

                if ($key == 10 || $key == 13) {
                    $cellStyles = ['borderRightSize' => 10, 'borderColor' => '000000'];
                }

                $this->addDataCellToRow($row, $value, $cellStyles, $fontStyle, self::$noPaddingPS);
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
        $cellRowSpan = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER];
        $cellRowSpanThick = ['vMerge' => 'restart', 'textDirection' => Cell::TEXT_DIR_BTLR, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'];
        $cellRowContinue = ['vMerge' => 'continue', 'valign' => Jc::CENTER];
        $hcFontStyle = ['name' => 'Times New Roman', 'size' => 8, 'valign' => Jc::CENTER];
        $hcAlignStyle = ['align' => Jc::CENTER, 'valign' => Jc::CENTER, 'space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];
        //'borderSize' => 8, 'borderColor' => '000000'

        $table->addRow(700);

        $table->addCell(900, $cellRowSpan)->addText('Наименование пожарных подразделений', $hcFontStyle, $hcAlignStyle);
        $table->addCell(500, $cellRowSpan)->addText('В карауле по списку л/с', $hcFontStyle, $hcAlignStyle);

        $table->addCell(3600, ['gridSpan' => 6, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('На лицо личного состава', $hcFontStyle, $hcAlignStyle);
        $table->addCell(3600, ['gridSpan' => 6, 'align' => Jc::CENTER, 'valign' => Jc::CENTER, 'borderSize' => 10, 'borderColor' => '000000'])->addText('Отсутствуют', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('ГДЗС', $hcFontStyle, $hcAlignStyle);
        $table->addCell(700, $cellRowSpan)->addText('Мотопомпы<w:br/>Водяная<w:br/>Грязевая', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 6, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('Пожарная техника', $hcFontStyle, $hcAlignStyle);

        $table->addRow();

        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, $cellRowSpan)->addText('Всего', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Нач.кар', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Ком.отд', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Водители', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Ряд.состав', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Ряд.Диспетчеров', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanThick)->addText('Отпуск', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanThick)->addText('Учебный', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanThick)->addText('Декрет', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanThick)->addText('Больные', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanThick)->addText('Командировка', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpanThick)->addText('Др.причины', $hcFontStyle, $hcAlignStyle);

        $table->addCell(null, $cellRowSpan)->addText('Газодымозащитники', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('АСВ/ДАСК', $hcFontStyle, $hcAlignStyle);

//        $table->addCell(null, $cellRowContinue);
//        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('В боевом расчёте', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('В резерве', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, ['gridSpan' => 2, 'align' => Jc::CENTER, 'valign' => Jc::CENTER])->addText('На ремонте', $hcFontStyle, $hcAlignStyle);

        $table->addRow(1200);

//        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);

        $table->addCell(null, $cellRowSpan)->addText('Тип осн. пожарного а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Марка спец. а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Тип осн. а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Марка спец. а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Тип осн. а/м', $hcFontStyle, $hcAlignStyle);
        $table->addCell(null, $cellRowSpan)->addText('Марка спец. а/м', $hcFontStyle, $hcAlignStyle);
    }



    private function addFirstPageTopData(Section $section)
    {
        $operGroup = $this->getOperGroupName();
        // заголовок
        $section->addText(
            'Строевая записка на ' . Carbon::parse($this->formationReport->created_at)
                ->addDay()
                ->format('d.m.Y') . 'г. '.$operGroup,
            ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
            ['align' => Jc::CENTER]
        );

        $tableStyle = new Table;
        $tableStyle->setBorderColor('ffffff');
        $tableStyle->setUnit(TblWidth::PERCENT);
        $tableStyle->setWidth(100 * 50);

        $table = $section->addTable($tableStyle);

        $headCellFontStyle = ['name' => 'Times New Roman', 'size' => 9, 'bold' => true];
//        $paragraphStyle = ['space' => ['before' => 0, 'after' => 0], 'indentation' => ['left' => 0, 'right' => 0]];

        $people = $this->peopleByDept()['ОД'] ?? [];
        $dspt = implode(', ', $people['dspt'] ?? []);
        $cpps = implode(', ', $people['cpps'] ?? []);
        $edds = implode(', ', $people['edds'] ?? []);
        $ipl = implode(', ', $people['ipl'] ?? []);
        $water_supply = implode(', ', $people['water_supply'] ?? []);
        $senior_communication_master = implode(', ', $people['senior_communication_master'] ?? []);

        $row = $table->addRow();
        $row->addCell()->addText('ДСПТ: ' . $dspt . ';', $headCellFontStyle, self::$noPaddingPS);
        $row->addCell()->addText('ЕДДС: ' . $edds . ';', $headCellFontStyle, self::$noPaddingPS);
        $row->addCell()->addText('Ст. мастер связи: ' . $senior_communication_master . ';', $headCellFontStyle, self::$noPaddingPS);

        $row = $table->addRow();
        $row->addCell()->addText('ЦППС: ' . $cpps . ';', $headCellFontStyle, self::$noPaddingPS);
        $row->addCell()->addText('ИПЛ: ' . $ipl . ';', $headCellFontStyle, self::$noPaddingPS);
        $row->addCell()->addText('Водоканал: ' . $water_supply . ';', $headCellFontStyle, self::$noPaddingPS);

    }

    // @todo PDF не работает корректно
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
