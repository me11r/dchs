<?php

namespace App\Http\Controllers;

use App\EventInfoArrived;
use App\Jobs\SendFcmMessages;
use App\Models\QueuedReport;
use App\Services\QueuedReports\QueuedReportManager;
use App\Services\QueuedReports\ReportHandlers\AnalyticsSpiasrStrategy;
use App\Services\ReportExport\Ticket101PeriodExcelExport;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TestController extends Controller
{
    public function fcm()
    {
        $strategy = new AnalyticsSpiasrStrategy();
        $result = $strategy->getResult('2019-01-01', '2019-01-31');

        $xls = new Ticket101PeriodExcelExport($result);

        dd('123');
//        dd(QueuedReport::find(86)->getData());
//        $reportManager->setQueuedReport(QueuedReport::find(86))->handle();

//        $reportData = Ticket101::getDetailedStat("2019-03-01", "2019-03-04", null, null, null);
//
//        $exportService = new Ticket101PeriodExcelExport($reportData);
//        $writer = $exportService->getXlsWriter();
//
//        $writer->save('test.xls');
//        dd($result);
//        $gdzsId = Cache::rememberForever('gdzs_event_info_arrived_id', function (){
//            return EventInfoArrived::where('name', '=', 'ГДЗС')->first()->id;
//        });

//        dd(Cache::get('gdzs_event_info_arrived_id'));
//        dd('the end');
//        $title = $request->get('title');
//        $body = $request->get('body');
//        $token = $request->get('token');
//        $info = $request->get('info');
//
//        if ($title && $body && $token) {
//            $this->dispatch(new SendFcmMessages(
//                [$token],
//                $title,
//                $body,
//                null,
//                $info
//            ));
//        }
//
//        return View::make('test.fcm', ['request' => $request->all()]);
    }

}
