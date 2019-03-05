<?php

namespace App\Console\Commands;

use App\FormationReport;
use App\FormationTechReport;
use App\Models\FireDepartmentResult;
use App\ReportCache;
use App\Ticket101;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class FillMainPageReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:main:reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [];

        $report_id = FormationReport::approved()->max('id');
        $data['reports'] = FormationTechReport::where('form_id', $report_id)
            ->has('items')
            ->orderBy('dept_id')
            ->with(['items', 'department'])
            ->get();

        foreach ($data['reports'] as $report_key => $report) {
            foreach ($report->items as $item_key => $tech_item) {

                $report->items[$item_key]['departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->count();

                $report->items[$item_key]['real_departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->doesntHave('ticket.drill_type')
                    ->whereNull('ticket101_other_id')
                    ->count();

                $report->items[$item_key]['drill_departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->has('ticket.drill_type')
                    ->count();

                $report->items[$item_key]['other_departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->whereNotNull('ticket101_other_id')
                    ->count();

                $report->items[$item_key]['status'] = Ticket101::whereHas('results', function ($q) use ($tech_item){
                    $q->shiftRecords()
                        ->where('tech_id', $tech_item->id)
                        ->whereNull('ret_time')
                        ->whereNotNull('out_time');
                })
                    ->with(['results', 'fire_level'])
                    ->first();

                $report->items[$item_key]['address'] = $report->items[$item_key]['status']->location ?? null;
                $report->items[$item_key]['fire_rank'] = $report->items[$item_key]['status']->fire_level->name ?? null;
                $report->items[$item_key]['out_time'] = $report->items[$item_key]['status']->fire_level->name ?? null;

                if($report->items[$item_key]['status']){
                    $roadtripItem = $report->items[$item_key]['status']->results()->where('tech_id', $tech_item->id)->first();
                    if($roadtripItem){
                        $report->items[$item_key]['out_time'] = $roadtripItem->out_time;
                        $report->items[$item_key]['arrive_time'] = $roadtripItem->arrive_time;
                    }
                }
                else{
                    $report->items[$item_key]['out_time'] = null;
                    $report->items[$item_key]['arrive_time'] = null;
                }

            }
        }

        $reportCache = ReportCache::updateOrCreate(['name' => 'main_page_forces'], [
            'name' => 'main_page_forces',
            'data' => json_encode($data),
        ]);

        Cache::put('report_forces_data', $data, 3600);
    }
}
