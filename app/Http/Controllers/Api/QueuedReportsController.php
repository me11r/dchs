<?php

namespace App\Http\Controllers\Api;

use App\Models\QueuedReport;
use App\Services\QueuedReports\QueuedReportsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QueuedReportsController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $items = new QueuedReport();

        $items = $items->with(['reportType', 'status']);
        $items = $items->orderBy('id', 'DESC')->paginate($request->get('per_page', 10));

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(QueuedReport::with(['reportType', 'status'])->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
