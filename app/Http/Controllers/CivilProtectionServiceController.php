<?php

namespace App\Http\Controllers;

use App\CivilProtectionService;
use App\CivilProtectionServiceBlock;
use App\CivilProtectionServiceBlockItem;
use App\Services\ReportExport\ReportCivilProtectionServicesWordExport;
use Illuminate\Http\Request;

class CivilProtectionServiceController extends Controller
{
    private $base_view = 'civil-protection-services';

    public function index(Request $request)
    {
        $view = "$this->base_view.index";
        $data = [];
        $data['per_page'] = $request->input('per_page', 15);
        $data['records'] = CivilProtectionService::orderBy('date', 'desc')
            ->paginate($data['per_page']);

        return view($view, $data);
    }

    public function create()
    {
        $view = "$this->base_view.edit-create";
        $latestRecord = CivilProtectionService::with(['items'])
            ->orderBy('date', 'desc')
            ->first();

        if ($latestRecord) {
            $latestRecord->id = null;
            $latestRecord->date = now()->format('Y-m-d');
            $latestRecord->is_active = true;
        }

        $data['record'] = $latestRecord ? $latestRecord : json_encode(null);
        $data['blocks'] = CivilProtectionServiceBlock::withTrashed()->orderBy('sort_order')
            ->get();

        return view($view, $data);
    }

    public function edit(Request $request, $id)
    {
        $view = "$this->base_view.edit-create";
        $data = [];
        $data['record'] = CivilProtectionService::with(['items'])->findOrFail($id);
        $data['blocks'] = CivilProtectionServiceBlock::withTrashed()->orderBy('sort_order')
            ->get();

        return view($view, $data);
    }

    public function store(Request $request)
    {
        $all = $request->all();

        if (!$request->id) {
            CivilProtectionService::getlatestRecord(true);
        }

        $record = CivilProtectionService::updateOrCreate([
            'id' => $request->id,
        ],[
            'date' => $request->date,
            'note' => $request->note,
            'is_active' => true,
        ]);

        $record->items()->delete();

        foreach ($all['items'] as $item) {

            $record->items()->create([
                'cp_service_id' => $record->id,
                'cp_service_block_id' => $item['cp_service_block_id'],
                'position' => $item['position'],
                'name' => $item['name'],
                'contacts' => $item['contacts'],
                'inventory1' => $item['inventory1'],
                'inventory2' => $item['inventory2'],
                'inventory3' => $item['inventory3'],
            ]);
        }

        return response()->json('ok');
    }

    public function destroy(Request $request, $id)
    {
        CivilProtectionService::destroy($id);
        return back();
    }

    public function export(Request $request, $id)
    {
        $data['record'] = CivilProtectionService::with(['items'])->findOrFail($id);
        $data['blocks'] = CivilProtectionServiceBlock::withTrashed()->orderBy('sort_order')
            ->get();
        $report = new ReportCivilProtectionServicesWordExport($data);
        return $report->export();
    }
}
