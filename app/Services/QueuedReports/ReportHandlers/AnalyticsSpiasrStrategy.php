<?php

namespace App\Services\QueuedReports\ReportHandlers;

use App\Enums\ReportType;
use App\EventInfoArrived;
use App\Models\QueuedReport;
use App\Services\ReportExport\Ticket101PeriodExcelExport;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsSpiasrStrategy implements ReportHandlerStrategyInterface
{

    use MysqlAdditionalFunctions;

    /**
     * @param QueuedReport $queuedReport
     * @param $reportData
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function saveToFile(QueuedReport $queuedReport, $reportData): string
    {
        $exportService = new Ticket101PeriodExcelExport($reportData);
        $writer = $exportService->getXlsWriter();

        $fileName = $this->getFileName($queuedReport);
        $directory = storage_path('reports' . DIRECTORY_SEPARATOR . ReportType::ANALYTICS_SPIASR . DIRECTORY_SEPARATOR);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = $directory . $fileName;

        $writer->save($filePath);

        return $filePath;
    }

    /**
     * @param QueuedReport $queuedReport
     * @return mixed
     */
    public function getData(QueuedReport $queuedReport)
    {
        $data = $queuedReport->report_data;

        $date_begin = Arr::get($data, 'date_begin');
        $date_end = Arr::get($data, 'date_end');
        $result_id = Arr::get($data, 'result_id');
        $burnt_id = Arr::get($data, 'burnt_id');
        $city_area_id = Arr::get($data, 'city_area_id');
        $time_onway = Arr::get($data, 'time_onway');
        $time_liqv = Arr::get($data, 'time_liqv');

        return $this->getResult($date_begin, $date_end, $result_id, $burnt_id, $city_area_id, $time_onway, $time_liqv);
    }

    /**
     * @param QueuedReport $queuedReport
     * @return string
     */
    private function getFileName(QueuedReport $queuedReport)
    {
        return 'Отчет-1_' .
            Carbon::parse($queuedReport->date_start)->format('d.m.Y') .
            '_' .
            Carbon::parse($queuedReport->date_end)->format('d.m.Y') .
            '_' .
            $queuedReport->id .
            '.xls';
    }

    public function getResult($date_begin, $date_end, $result_id = null, $burnt_id = null, $city_area_id = null, $time_onway = null, $time_liqv = null)
    {
        $dateBegin = $date_begin ? Carbon::parse($date_begin) : now()->subDays(3);
        $dateEnd = $date_end ? Carbon::parse($date_end) : now();
        $gdzsEventInfoArrivedId = Cache::rememberForever('gdzs_event_info_arrived_id', function () {
            return EventInfoArrived::where('name', '=', 'ГДЗС')->first()->id;
        });

        $this->defineTimeDiffSpike();

        DB::statement(DB::raw('SET @on_way_time_minutes = 0;'));
        DB::statement(DB::raw('SET @on_way_category = 0;'));
        DB::statement(DB::raw('SET @liqv_time_total_minutes = 0;'));
        DB::statement(DB::raw('SET @gdzs_count = 0;'));

        $items = DB::table('ticket101')
            ->select([
                'ticket101.id',
                DB::raw('date_format(ticket101.custom_created_at, "%d.%m.%Y") as custom_created_at_date'),
                DB::raw('date_format(ticket101.custom_created_at, "%H:%i") as custom_created_at_hours'),
                'ticket101.caller_name',
                'ticket101.caller_phone',
                DB::raw('dict_city_area.name as city_area_name'),
                'ticket101.location',
                'ticket101.object_name',
                DB::raw('dict_fire_level.name as result_fire_level_name'),
                DB::raw('dict_liquidation_method.name as liquidation_method_name'),
                DB::raw('first_result.arrive_time as first_result_arrived_time'),
                DB::raw("TIME_DIFF_SPIKE(first_result.out_time, first_result.arrive_time) as on_way_time"),
                DB::raw('TIME_FORMAT(ticket101.loc_time, "%H:%i") as loc_time'),
                DB::raw('TIME_FORMAT(ticket101.liqv_time, "%H:%i") as liqv_time'),
                DB::raw('TIME_DIFF_SPIKE(first_result.arrive_time, ticket101.loc_time) as loc_time_total'),
                DB::raw("(GROUP_CONCAT(CONCAT('Тип: ', event_info_arrived.name, ', Количество: ', gdzs_chronology.working_time) SEPARATOR '\n')) as `event_info_arrived_names`"),
                DB::raw("(GROUP_CONCAT(CONCAT('Тип: ', trunk_event_info_arrived.name, ', Количество: ', non_gdzs_chronology.working_time) SEPARATOR '\n')) as `trunks_event_info_arrived_names`"),
                'ticket101.rescued_count',
                'ticket101.evac_count',
                'ticket101.gpt_burns_count',
                DB::raw('COALESCE(ticket101.people_death_count, 0) + COALESCE(ticket101.children_death_count, 0) as total_death_count'),
                DB::raw('TIME_DIFF_SPIKE(first_result.arrive_time, ticket101.liqv_time) as liqv_time_total'),
                'ticket101.rescued_count',
                DB::raw('trip_result.name as trip_result_name'),
                'ticket101.max_square',
                'ticket101.storey_count',

                DB::raw('@on_way_time_minutes := ABS((TIME_TO_SEC(first_result.arrive_time)-TIME_TO_SEC(first_result.out_time))/60) as on_way_time_minutes'),
                DB::raw("@on_way_category := CASE 
                                   WHEN @on_way_time_minutes < 5 THEN 'less_5'
                                   WHEN @on_way_time_minutes > 5 AND @on_way_time_minutes < 10 THEN 'less_10'
                                   WHEN @on_way_time_minutes > 10 THEN 'more_10'
                               END as on_way_category"),

                DB::raw('@liqv_time_total_minutes := ABS((TIME_TO_SEC(TIMEDIFF(first_result.arrive_time, ticket101.liqv_time)))/60) as liqv_time_total_minutes'),
                DB::raw("CASE 
                                   WHEN @liqv_time_total_minutes < 15 THEN 'less_15'
                                   WHEN @liqv_time_total_minutes > 15 AND @liqv_time_total_minutes < 30 THEN 'less_30'
                                   WHEN @liqv_time_total_minutes > 30 AND @liqv_time_total_minutes < 60 THEN 'less_60'
                                   WHEN @liqv_time_total_minutes > 60 AND @liqv_time_total_minutes < 120 THEN 'less_120'
                                   WHEN @liqv_time_total_minutes > 120 THEN 'more_120'
                               END as liqv_category"),

                DB::raw("@gdzs_count := CASE 
                                   WHEN COUNT(gdzs_chronology.id) = 1 OR SUM(gdzs_chronology.quantity) = 1 THEN 1
                                   WHEN COUNT(gdzs_chronology.id) > 1 OR SUM(gdzs_chronology.quantity) > 1 THEN GREATEST(COUNT(gdzs_chronology.id), COALESCE(gdzs_chronology.quantity, 0))
                                   ELSE 0
                               END as gdzs_count"),
                DB::raw('SUM(gdzs_chronology.working_time) as gdzs_chronology_working_time'),
                DB::raw('SUM(non_gdzs_chronology.working_time) as trunks_chronology_working_time'),
                'object_classifications.name as object_classification_name',
            ])
            ->leftJoin('dict_city_area', 'ticket101.city_area_id', '=', 'dict_city_area.id')
//            ->leftJoin('dict_fire_level', 'ticket101.result_fire_level_id', '=', 'result_dict_fire_level.id')
            ->leftJoin('object_classifications', 'ticket101.object_classification_id', '=', 'object_classifications.id')
            ->leftJoin('dict_fire_level', 'ticket101.result_fire_level_id', '=', 'dict_fire_level.id')
            ->leftJoin('dict_liquidation_method', 'ticket101.liquidation_method_id', '=', 'dict_liquidation_method.id')
            ->leftJoin('fire_department_results as first_result', function (JoinClause $query) {
                $query
                    ->on('first_result.ticket101_id', '=', 'ticket101.id')
                    ->where('first_result.arrive_time', '=',
                        DB::raw('(SELECT MIN(arrive_time) FROM fire_department_results WHERE ticket101_id = ticket101.id)'));
            })
            ->leftJoin('chronology101s as gdzs_chronology', function (JoinClause $query) use ($gdzsEventInfoArrivedId) {
                $query
                    ->on('gdzs_chronology.ticket101_id', '=', 'ticket101.id')
                    ->where('gdzs_chronology.event_info_arrived_id', '=', $gdzsEventInfoArrivedId)
                    ->leftJoin('event_info_arriveds as event_info_arrived', 'gdzs_chronology.event_info_arrived_id', '=', 'event_info_arrived.id');
            })
            ->leftJoin('chronology101s as non_gdzs_chronology', function (JoinClause $query) use ($gdzsEventInfoArrivedId) {
                $query
                    ->on('non_gdzs_chronology.ticket101_id', '=', 'ticket101.id')
                    ->where('non_gdzs_chronology.event_info_arrived_id', '<>', $gdzsEventInfoArrivedId)
                    ->leftJoin('event_info_arriveds as trunk_event_info_arrived', 'non_gdzs_chronology.event_info_arrived_id', '=', 'trunk_event_info_arrived.id');
            })
            ->leftJoin('dict_trip_result as trip_result', 'ticket101.trip_result_id', '=', 'trip_result.id')
            ->groupBy('ticket101.id');

        $items = $items->whereBetween(
            'ticket101.custom_created_at',
            [
                $dateBegin->format('Y-m-d 00:00:00'),
                $dateEnd->addDay(1)->format('Y-m-d 00:00:00')
            ]);

        if ($result_id) {
            $items = $items->where('ticket101.trip_result_id', $result_id);
        }

        if ($burnt_id) {
            $items = $items->where('ticket101.burn_object_id', $burnt_id);
        }

        if ($city_area_id) {
            $items = $items->where('ticket101.city_area_id', $city_area_id);
        }

        $items = $items->whereNull('ticket101.drill_type_id');

        $items = $items->orderBy('custom_created_at', 'ASC');

        $itemsCollection = $items->get();

        /** @var Collection $itemsCollection */
        if ($time_onway) {
            $itemsCollection = $itemsCollection->where('on_way_category', $time_onway);
        }

        if($time_liqv) {
            $itemsCollection = $itemsCollection->where('liqv_category', $time_liqv);
        }

        $result['items'] = $itemsCollection;
        $result['totals'] = $this->getTotalsFromItems($itemsCollection);

        return $result;
    }

    private function getTotalsFromItems(Collection $items)
    {
        $result = [
            'on_way_totals' => [
                'less_5' => $items->where('on_way_category', '=', 'less_5')->count(),
                'less_10' => $items->where('on_way_category', '=', 'less_10')->count(),
                'more_10' => $items->where('on_way_category', '=', 'more_10')->count()
            ],
            'liqv_totals' => [
                'less_15' => $items->where('liqv_category', '=', 'less_15')->count(),
                'less_30' => $items->where('liqv_category', '=', 'less_30')->count(),
                'less_60' => $items->where('liqv_category', '=', 'less_60')->count(),
                'less_120' => $items->where('liqv_category', '=', 'less_120')->count(),
                'more_120' => $items->where('liqv_category', '=', 'more_120')->count()
            ],
            'elements_of_gdzs' => [
                'one' => $items->where('gdzs_count', '=', 1)->sum('gdzs_chronology_working_time'),
                'many' => $items->where('gdzs_count', '>', 1)->sum('gdzs_chronology_working_time')
            ]
        ];

        return $result;
    }

}
