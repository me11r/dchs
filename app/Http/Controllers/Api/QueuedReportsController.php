<?php

namespace App\Http\Controllers\Api;

use App\Enums\QueueStatusType;
use App\Enums\ReportType;
use App\Models\QueuedReport;
use App\Models\QueueStatus;
use App\Services\QueuedReports\QueuedReportsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QueuedReportsController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $items = new QueuedReport();

        $items = $items->with(['reportType', 'status'])->where('user_id', '=', Auth::user()->id);
        $items = $items->orderBy('id', 'DESC')
            ->paginate(
                $request->get('per_page', 10),
                ['id', 'report_type_id', 'queue_status_id', 'file_path', 'date_start', 'date_end', 'attempts', 'error_text', 'created_at']
            );

        return response()->json($items);
    }

    public function sendToQueue(Request $request, QueuedReportsService $queuedReportsService)
    {
        return response()->json([
            'result' => $queuedReportsService->sendToQueue(
                (int)$request->get('id')
            )
        ]);
    }

    public function userHasNotFinishedReport()
    {
        $result = QueuedReport::where('user_id', '=', Auth::user()->id)
                ->where('queue_status_id', '<>', QueueStatus::getBySlug(QueueStatusType::ENDED)->id)
                ->count() > 0;
        return response()->json([
            'result' => $result
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, QueuedReportsService $queuedReportsService)
    {
        $queuedReport = $queuedReportsService->registerNewReport(
            Carbon::parse($request->get('dateStart')),
            Carbon::parse($request->get('dateEnd')),
            $reportType = $request->get('reportType'),
            $request->get('reportData')
        );
        $result = $queuedReportsService->sendToQueue($queuedReport->id);

        return response()->json([
            'result' => $result
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(QueuedReport::with(['reportType', 'status'])->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
