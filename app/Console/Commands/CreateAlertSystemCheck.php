<?php

namespace App\Console\Commands;

use App\AlertSystemCheck;
use Illuminate\Console\Command;

class CreateAlertSystemCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:alert_system_check {--date=}';

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
        $date = $this->option('date') ? $this->option('date') : now()->format('Y-m-d');

        $check = AlertSystemCheck::firstOrCreate(['date' => $date]);

        foreach (\App\Direction::all() as $direction) {
            \App\AlertSystemCheckItem::firstOrCreate([
                'direction_id' => $direction->id,
                'alert_system_check_id' => $check->id,
            ]);
        }

        /*TEST DATA*/
//        foreach (range(1, 30) as $key => $item) {
//            $date = $date->addDay();
//
//            $check = AlertSystemCheck::firstOrCreate(['date' => $date]);
//
//            foreach (\App\Direction::all() as $direction) {
//                \App\AlertSystemCheckItem::firstOrCreate([
//                    'direction_id' => $direction->id,
//                    'alert_system_check_id' => $check->id,
//                    'check1' => mt_rand(0,1),
//                    'check2' => mt_rand(0,1),
//                    'check3' => mt_rand(0,1),
//                ]);
//            }
//        }
    }
}
