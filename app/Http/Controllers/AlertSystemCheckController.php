<?php

namespace App\Http\Controllers;

use App\AlertSystemCheck;
use App\Direction;
use App\Services\ReportExport\AlertSystemCheckWordExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertSystemCheckController extends Controller
{
    public function index(Request $request)
    {
        $data['per_page'] = $request->input('per_page', 50);
        $data['records'] = AlertSystemCheck::orderBy('id', 'desc')->paginate($data['per_page']);

        return view('reports.alert-system-checks.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $data['user'] = Auth::user();

        $data['check'] = AlertSystemCheck::findOrFail($id);
        $data['directions'] = Direction::reserved(false)
            ->sorted()
            ->get();

        $data['directions_reserved'] = Direction::reserved(true)
            ->sorted()
            ->get();

        $data['check_items'] = $data['check']->items()->get()->keyBy('direction_id');
        $data['select_items'] = [
            ['name' => '', 'id' => null],
            ['name' => 'не исправен', 'id' => 0],
            ['name' => 'исправен', 'id' => 1],
        ];

        return view('reports.alert-system-checks.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $record = AlertSystemCheck::find($id);
        foreach ($request->directions as $directionId => $direction) {
            $record->items()->updateOrCreate([
                'check1' => $request->input("directions.{$directionId}.check1", null),
                'check2' => $request->input("directions.{$directionId}.check2", null),
                'check3' => $request->input("directions.{$directionId}.check3", null),
                'note' => $request->input("directions.{$directionId}.note", null),
                'direction_id' => $directionId,
            ]);
        }
        return back();
    }

    public function download(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from)->format('Y-m-d') : now();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to)->format('Y-m-d') : now();

        $data['directions'] = Direction::reserved(false)
            ->sorted()
            ->get();

        $data['directions_reserved'] = Direction::reserved(true)
            ->sorted()
            ->get();

        $checks = AlertSystemCheck::date([$dateFrom,$dateTo])
            ->orderBy('date')
            ->get();

        foreach ($checks as $check) {
            $data['check_items'][$check->id] = $check->items()->get()->keyBy('direction_id');
        }

        $data['tables'] = [];

        $data['portions'] = $checks->chunk(8);

        foreach ($data['portions'] as $portionNum => $portion) {
            foreach ($data['directions'] as $directionKey => $direction) {
                $rowNum = ++$directionKey;
                $data['tables'][$portionNum]['rows'][$directionKey][] = $rowNum;
                $data['tables'][$portionNum]['rows'][$directionKey][] = $direction->name;
                foreach ($portion as $check_) {
                    $data['tables'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['check1_title'];
                    $data['tables'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['check2_title'];
                    $data['tables'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['check3_title'];
//                    $data['tables'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['note'];
                }
            }
        }

        foreach ($data['portions'] as $portionNum => $portion) {
            foreach ($data['directions_reserved'] as $directionKey => $direction) {
                $rowNum = ++$directionKey;
                $data['tables_reserved'][$portionNum]['rows'][$directionKey][] = $rowNum;
                $data['tables_reserved'][$portionNum]['rows'][$directionKey][] = $direction->name;
                foreach ($portion as $check_) {
                    $data['tables_reserved'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['check1'];
                    $data['tables_reserved'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['check2'];
                    $data['tables_reserved'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['check3'];
//                    $data['tables_reserved'][$portionNum]['rows'][$directionKey][] = $data['check_items'][$check_->id][$direction->id]['note'];
                }
            }
        }

        $report = new AlertSystemCheckWordExport($data);

        $writer = $report->getWriter('Word2007');
        $fileName = 'Тех.проверка системы оповещения - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }
}
