<?php

namespace App\Http\Controllers;

use App\EventInfoArrived;
use App\Jobs\SendFcmMessages;
use App\Models\QueuedReport;
use App\Services\QueuedReports\QueuedReportManager;
use App\Services\ReportExport\Ticket101PeriodExcelExport;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TestController extends Controller
{
    public function fcm(Request $request, QueuedReportManager $reportManager)
    {
        dd(QueuedReport::find(86)->getData());
//        $reportManager->setQueuedReport(QueuedReport::find(86))->handle();

//        $reportData = Ticket101::getDetailedStat("2019-01-01", "2019-04-01", null, null, null);
//
//        $exportService = new Ticket101PeriodExcelExport($reportData);
//        $writer = $exportService->getXlsWriter();
//
//        $writer->save('C:\js_projects\emergency-management\test.xls');
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
