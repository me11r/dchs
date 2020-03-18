<?php

namespace App\Services\ReportExport;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;

class Ticket101PeriodExcelExport
{

    use CommonExportTools;

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    private $stat;

    const HStyle = [
        'font' => [
            'size' => 10
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
            'wrapText' => true
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ]
    ];

    const VStyle = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
            'textRotation' => 90
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ]
    ];

    public function __construct($stat)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->stat = $stat;

        $this->prepareSpreadsheet();
    }

    /**
     * @return Xls
     */
    public function getXlsWriter(): IWriter
    {
        return new Xls($this->spreadsheet);
    }

    private function prepareSpreadsheet()
    {
        Cell::setValueBinder(new AdvancedValueBinder());

        $sheet = $this->getActiveSheetByIndex(0);
        $sheet->setTitle('Отчет по карточке 101 за период');
        $this->addTableData($sheet);

        $sheet = $this->getActiveSheetByIndex(1);
        $sheet->setTitle('Итоги по карточке 101 за период');
        $this->addResultData($sheet);
    }

    /**
     * @param Worksheet $sheet
     * @param int $startRowIndex
     * @throws Exception
     */
    private function addResultData(Worksheet $sheet)
    {
        $sheet->mergeCells('A5:B5');
        $this->setCell($sheet, 'Время следования', "A5", "A5", self::HStyle);

        $this->setCell($sheet, 'До 5 минут', "A6", "A6", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['on_way_totals']['less_5'], "B6", "B6", self::HStyle);

        $this->setCell($sheet, 'До 10 минут', "A7", "A7", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['on_way_totals']['less_10'], "B7", "B7", self::HStyle);

        $this->setCell($sheet, 'Более 10 минут', "A8", "A8", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['on_way_totals']['more_10'], "B8", "B8", self::HStyle);


        $sheet->mergeCells('D5:E5');
        $this->setCell($sheet, 'Время ликвидации', "D5", "D5", self::HStyle);

        $this->setCell($sheet, 'До 15 минут', "D6", "D6", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_15'], "E6", "E6", self::HStyle);

        $this->setCell($sheet, 'До 30 минут', "D7", "D7", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_30'], "E7", "E7", self::HStyle);

        $this->setCell($sheet, 'До 1 часа', "D8", "D8", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_60'], "E8", "E8", self::HStyle);

        $this->setCell($sheet, 'До 2 часов', "D9", "D9", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['less_120'], "E9", "E9", self::HStyle);

        $this->setCell($sheet, 'Более 2 часов', "D10", "D10", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['liqv_totals']['more_120'], "E10", "E10", self::HStyle);


        $sheet->mergeCells('G5:H5');
        $this->setCell($sheet, 'Звенья ГДЗС', "G5", "G5", self::HStyle);

        $this->setCell($sheet, 'одним звеном', "G6", "G6", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['elements_of_gdzs']['one'], "H6", "H6", self::HStyle);

        $this->setCell($sheet, 'двумя и более', "G7", "G7", self::HStyle);
        $this->setCell($sheet, $this->stat['totals']['elements_of_gdzs']['many'], "H7", "H7", self::HStyle);

        $this->setCell($sheet, 'Общее количество выездов: ' . $this->stat['items']->count(), 'A12', 'A12', self::HStyle);

        foreach (range('A', 'I') as $item) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }
    }


    /**
     * @param Worksheet $sheet
     * @param int $startRowIndex
     * @throws Exception
     */
    private function addTableData(Worksheet $sheet, $startRowIndex = 1)
    {
        $rowIndex = $startRowIndex;

        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        $sheet->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->getPageMargins()
            ->setLeft(0.3);

        $sheet->getPageMargins()
            ->setRight(0.3);

        $sheet->getPageMargins()
            ->setTop(0.3);

        $sheet->getPageMargins()
            ->setBottom(0.3);
        /*
        $headers = [
            "Дата выезда\nВремя",
            'ФИО',
            'Телефон',
            'Район города',
            'Адрес',
            "Наименование объектов\nКлассификация объектов",
            'Этажность',
            'Ранг пожара',
            "Cледование\nТушение",
            "Локализация\nЛиквидация", // 10
            'Ликвидировано стволами', 
            'Пенные стволы',
            "Время\nработы\nстволов",
            'Звенья ГДЗС',
            "Время\nработы\nГДЗС", // 15
            "Спасено\nЭвакуировано",
            "Травмы\nГибель",
            "Площадь\nгорения",
            'Результат выезда',
        ];
        
        $sheet->fromArray($headers, null, 'A'.$rowIndex);

        $sheet->getStyle("A$rowIndex:S$rowIndex")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 9
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_TOP
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);
        
        $rowIndex += 1;

        foreach ($this->stat['items'] as $item) {
            $arr = [
                $item->custom_created_at_date."\n".$item->custom_created_at_hours,
                $item->caller_name,
                $item->caller_phone,
                $item->city_area_name,
                $item->location,
                $item->object_name."\n".$item->object_classification_name,
                strval($item->storey_count),
                $item->result_fire_level_name,
                $item->on_way_time."\n".$item->loc_time_total, 
                $item->loc_time."\n".$item->liqv_time,
                $item->liquidation_method_name,
                $item->trunks_event_info_arrived_names,
                $item->trunks_chronology_working_time,
                $item->event_info_arrived_names,
                strval($item->gdzs_count),
                strval($item->rescued_count)."\n".strval($item->evac_count),
                strval($item->gpt_burns_count)."\n".strval($item->total_death_count),
                strval($item->max_square),
                $item->trip_result_name
            ];

            $sheet->fromArray($arr, null, "A$rowIndex");

            $url = env('APP_URL','http://emergency.iteamsolutions.kz')."/card/add101/{$item->id}#return=0";

            $sheet->getCell("E{$rowIndex}")->setValue($arr[4])->getHyperlink()->setUrl($url);

            $sheet->getStyle("A$rowIndex:S$rowIndex")->applyFromArray(self::HStyle);
            $rowIndex += 1;
        }

        foreach (range('A', 'S') as $item) {
            
            switch($item) {
                
                case 'E':
                    $sheet->getColumnDimension($item)->setWidth(21);
                    break;

                case 'L':
                    $sheet->getColumnDimension($item)->setWidth(19);
                    break;
                
                case 'B':
                    $sheet->getColumnDimension($item)->setWidth(15);
                    break;

                case 'H':
                    $sheet->getColumnDimension($item)->setWidth(15);
                    break;

                case 'F':
                    $sheet->getColumnDimension($item)->setWidth(20);
                    break;

                case 'N':
                    $sheet->getColumnDimension($item)->setWidth(23);
                    break;

                case 'K':
                    $sheet->getColumnDimension($item)->setWidth(27);
                    break;

                case 'S':
                    $sheet->getColumnDimension($item)->setWidth(27);
                    break;
                    
                case 'D':
                    $sheet->getColumnDimension($item)->setWidth(14);
                    break;

                case 'A':
                    $sheet->getColumnDimension($item)->setWidth(11);
                    break;

                case 'I':
                    $sheet->getColumnDimension($item)->setWidth(10);
                    break;

                case 'J':
                    $sheet->getColumnDimension($item)->setWidth(11);
                    break;
                
                case 'C':
                case 'P':
                    $sheet->getColumnDimension($item)->setWidth(12);
                    break;
                
                case 'G':
                    $sheet->getColumnDimension($item)->setWidth(9);
                    break;
                
                case 'M':
                case 'O':
                case 'Q':
                    $sheet->getColumnDimension($item)->setWidth(7);
                    break;
                
                case 'R':
                    $sheet->getColumnDimension($item)->setWidth(8);
                    break;

                default:
                    $sheet->getColumnDimension($item)->setAutoSize(true);
            }
        }
        */

        $headers = [
            "Дата\nвыезда\nВремя",
            'ФИО',
            'Телефон',
            "Район\nгорода",
            'Адрес',
            "Наименование\nобъектов",
            "Классификация\nобъектов",
            //            'Участники тушения',
            "Ранг\nпожара",
            "Ликвидировано\nстволами",
            "Время\nследования",
            "Время\nтушения",
            'Локализация',
            'Ликвидация',
            "Пенные\nстволы",
            "Время\nработы\nстволов",
            "Звенья\nГДЗС",
            "Время\nработы\nГДЗС",
            "Спасено\nлюдей",
            "Эвакуировано\nлюдей",
            'Травмы',
            'Гибель',
            "Результат\nвыезда",
            "Площадь\nгорения",
            //            'Затраченное время на локализацию',
            //            'Затраченное время на ликвидацию',
            'Этажность',
        ];
        
        $sheet->fromArray($headers, null, "A{$rowIndex}");
        $sheet->getStyle("A{$rowIndex}:X{$rowIndex}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 9
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_TOP
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);

        $rowIndex += 1;

        foreach ($this->stat['items'] as $item) {
            $arr = [
                $item->custom_created_at_date."\n".$item->custom_created_at_hours,
                $item->caller_name,
                $item->caller_phone,
                $item->city_area_name,
                $item->location,
                $item->object_name,
                $item->object_classification_name,
                $item->result_fire_level_name,
                $item->liquidation_method_name,
                $item->on_way_time,
                $item->loc_time_total,
                $item->loc_time,
                $item->liqv_time,
                $item->trunks_event_info_arrived_names,
                $item->trunks_chronology_working_time,
                $item->gdzs_count,
                $item->event_info_arrived_names,
                $item->rescued_count,
                $item->evac_count,
                $item->gpt_burns_count,
                $item->total_death_count,
                //                $item->loc_time_total,
                //                $item->liqv_time_total,
                $item->trip_result_name,
                $item->max_square,
                $item->storey_count
            ];

            $sheet->fromArray($arr, null, "A{$rowIndex}");

            $url = env('APP_URL', 'http://emergency.iteamsolutions.kz') . "/card/add101/{$item->id}#return=0";

            $sheet->getCell("E{$rowIndex}")->setValue($arr[4])->getHyperlink()->setUrl($url);

            $sheet->getStyle("A{$rowIndex}:X{$rowIndex}")->applyFromArray(self::HStyle);
            
            $rowIndex += 1;
        }

        foreach (range('A', 'X') as $item) {

            switch ($item) {

                case 'E':
                    $sheet->getColumnDimension($item)->setWidth(21);
                    break;

                case 'B':
                    $sheet->getColumnDimension($item)->setWidth(15);
                    break;

                case 'H':
                    $sheet->getColumnDimension($item)->setWidth(8);
                    break;

                case 'F':
                case 'G':
                    $sheet->getColumnDimension($item)->setWidth(15);
                    break;

                case 'Q':
                    $sheet->getColumnDimension($item)->setWidth(13);
                    break;

                case 'N':
                    $sheet->getColumnDimension($item)->setWidth(13);
                    break;

                case 'V':
                    $sheet->getColumnDimension($item)->setWidth(17);
                    break;

                case 'S':
                    $sheet->getColumnDimension($item)->setWidth(13);
                    break;

                case 'D':
                    $sheet->getColumnDimension($item)->setWidth(14);
                    break;

                case 'A':
                    $sheet->getColumnDimension($item)->setWidth(11);
                    break;

                case 'I':
                    $sheet->getColumnDimension($item)->setWidth(18);
                    break;
                

                case 'C':
                    $sheet->getColumnDimension($item)->setWidth(12);
                    break;

                case 'O':
                    $sheet->getColumnDimension($item)->setWidth(7);
                    break;

                default:
                    $sheet->getColumnDimension($item)->setAutoSize(true);
            }
        }
    }

    /**
     * @param int $index
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function getActiveSheetByIndex(int $index): Worksheet
    {
        $this->spreadsheet->createSheet($index);
        $this->spreadsheet->setActiveSheetIndex($index);
        return $this->spreadsheet->getActiveSheet();
    }

    private function setCell(
        Worksheet $sheet,
        string $value,
        string $cell1,
        string $cell2,
        array $styles
    ) {
        if ($cell2 && $cell2 !== $cell1) {
            $styleCoordinates = "$cell1:$cell2";
            $sheet->mergeCells("$cell1:$cell2");
        } else {
            $styleCoordinates = $cell1;
        }

        $cell = $sheet->getCell($cell1);

        if (!$cell) {
            throw new Exception("Cell '$cell1'' not found");
        }
        $cell->setValue($value);

        $sheet->getStyle($styleCoordinates)->applyFromArray($styles);

        return $cell;
    }
}
