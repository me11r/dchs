<?php

namespace App\Jobs;

use App\Models\QueuedReport;
use App\Services\QueuedReports\QueuedReportManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HandleQueuedReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $queuedReportId;

    /**
     * HandleQueuedReport constructor.
     * @param int $queuedReportId
     */
    public function __construct(int $queuedReportId)
    {
        $this->queuedReportId = $queuedReportId;
    }


    /**
     * @param QueuedReportManager $reportManager
     * @throws \Exception
     */
    public function handle(QueuedReportManager $reportManager)
    {
        set_time_limit(3600);
        DB::connection()->disableQueryLog();

        if (App::environment('local')) {
            ini_set('memory_limit','1024M');
        }

        $reportManager->setQueuedReport(QueuedReport::find($this->queuedReportId))->handle();
    }
}
