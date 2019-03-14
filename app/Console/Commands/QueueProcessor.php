<?php

namespace App\Console\Commands;

use App\CustomQueue;
use App\Services\ReportProcessor;
use Illuminate\Console\Command;

class QueueProcessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:queue';

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
        $reportProcessor = new ReportProcessor();
        $queueItems = CustomQueue::all();
        foreach ($queueItems as $queueItem) {
            $reportProcessor->fire($queueItem);
        }
    }
}
