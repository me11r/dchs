<?php

namespace App\Console\Commands;

use App\Analytics101;
use App\Dictionary\BurntObject;
use App\Dictionary\FireObject;
use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\Repositories\EloquentBurntObjectRepository;
use App\Repositories\EloquentFireObjectRepository;
use App\Repositories\EloquentTicket101Repository;
use App\Ticket101;
use Illuminate\Console\Command;

class CreateAnalyticsDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:analytics101report';

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
        $data['record'] = Analytics101::firstOrCreate([
            'date' => today(),
        ]);

        $report = (new Report(new EloquentTicket101Repository(), new EloquentFireObjectRepository(), new EloquentBurntObjectRepository()))->getReport();


        if(isset($report['tripResults']) && count($report['tripResults'])){
            foreach ($report['tripResults'] as $title => $items) {
                foreach ($items as $reportItem) {
                    $data['record']->items()->firstOrCreate(
                        ['ticket101_id' => $reportItem['id']],
                        [
                            'text' => $reportItem['analytics'],
                            'trip_result_id' => $reportItem['trip_result_id'],
                            'ticket101_id' => $reportItem['id'],
                        ]);
                }
            }
        }
    }
}
