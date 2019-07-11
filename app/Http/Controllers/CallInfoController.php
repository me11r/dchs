<?php

namespace App\Http\Controllers;

use App\CallInfo;
use App\Models\DailyReportPerson;
use App\Services\ReportExport\ReportCallInfoWord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CallInfoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $data['items'] = CallInfo::orderBy('date', 'desc')->paginate($perPage);
        $data['per_page'] = $perPage;
        return view('reports.call-infos.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Информация по звонкам (создать)';
        $data['record'] = json_encode(null);
        return view('reports.call-infos.edit', $data);
    }

    public function edit($id)
    {
        $data['record'] = CallInfo::find($id);
        $data['title'] = 'Информация по звонкам за дату '.Carbon::parse($data['record']->date)->format('d-m-Y').' (редактировать)';
        return view('reports.call-infos.edit', $data);

    }

    public function delete(Request $request, $id)
    {
        CallInfo::find($id)->delete();

        return back();
    }

    public function update(Request $request, $id)
    {
        $data['record'] = CallInfo::find($id);
        $data['record']->count_101 = $request->count_101;
        $data['record']->count_102 = $request->count_102;
        $data['record']->count_103 = $request->count_103;
        $data['record']->count_info = $request->count_info;
        $data['record']->count_other = $request->count_other;
//        $data['record']->count_emergency = $request->count_emergency;
        $data['record']->count_112 = $request->count_112;
        $data['record']->count_109 = $request->count_109;
        $data['record']->note = $request->note;

        $data['record']->save();

        return back();
    }

    public function store(Request $request)
    {
        $sirenTech = CallInfo::create([
            'count_101' => $request->count_101,
            'count_102' => $request->count_102,
            'count_103' => $request->count_103,
            'count_112' => $request->count_112,
            'count_109' => $request->count_109,
            'count_info' => $request->count_info,
            'count_other' => $request->count_other,
//            'count_emergency' => $request->count_emergency,
            'note' => $request->note,
            'date' => $request->date ?? today(),
        ]);

        return redirect('reports/call-infos/edit/'.$sirenTech->id);
    }

    public function show(Request $request)
    {
        $dateFrom = $request->input('dateFrom', now()->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));

        $data = [];
        $data['reports'] = CallInfo::whereBetween('date', [$dateFrom, $dateTo])->get();

        Cache::put('call_info_dates',[
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ], 3600);

        if($request->ajax()) {
            return response()->json($data);
        }

        return view('reports.call-infos.show', $data);
    }

    public function download($type)
    {
        if ($data = Cache::get('call_info_dates')) {

            $data['header'] = DailyReportPerson::where('report_type', 'call-infos')
                ->where('type', 'header_first')
                ->first();

            $data['footer'] = DailyReportPerson::where('report_type', 'call-infos')
                ->where('type', 'footer_first')
                ->first();

            $dailyWordExport = new ReportCallInfoWord($data);

            $writer = $dailyWordExport->getWriter('Word2007');
            $fileName = 'Информация по звонкам  - '.date('d-m-Y'). '.docx';
            $writer->save(public_path($fileName));

            return response()->download(public_path($fileName));
        }
    }
}
