<?php

namespace App\Console\Commands;

use App\OperationalGroup;
use App\OperationalGroupSchedule;
use Illuminate\Console\Command;

class CreateDailyOperationalGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:operational_group {--id=}';

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
        $operGroupId = $this->option('id') ?? null;
        if($operGroupId && $operGroup = OperationalGroup::findOperGroup($operGroupId)->first()){

            OperationalGroupSchedule::updateOrCreate([
                'date_begin' => today()->addHours(18),
                'date_end' => today()->addHours(42),
            ],[
                'group_id' => $operGroup->id,
                'date_begin' => today()->addHours(18),
                'date_end' => today()->addHours(42),
            ]);
        }
        else{
            $operational = OperationalGroup::next();
        }
    }
}
