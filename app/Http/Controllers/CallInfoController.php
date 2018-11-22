<?php

namespace App\Http\Controllers;

use App\CallInfo;
use Illuminate\Http\Request;

class CallInfoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $data['items'] = CallInfo::orderBy('id', 'desc')->paginate($perPage);
        $data['per_page'] = $perPage;
        return view('reports.call-infos.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Информация по звонкам (создать)';
        return view('reports.call-infos.edit', $data);
    }

    public function edit($id)
    {
        $data['record'] = CallInfo::find($id);
        $data['title'] = 'Информация по звонкам за дату '.$data['record']->created_at->format('d-m-Y H:i').' (редактировать)';
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
        $data['record']->count_112 = $request->count_112;
        $data['record']->count_101 = $request->count_101;
        $data['record']->count_109 = $request->count_109;

        $data['record']->save();

        return back();
    }

    public function store(Request $request)
    {
        $f = $request->all();

        $sirenTech = CallInfo::create([
            'count_112' => $request->count_112,
            'count_101' => $request->count_101,
            'count_109' => $request->count_109,
            'date' => $request->date ?? today(),
        ]);

        return redirect('reports/call-infos/edit/'.$sirenTech->id);
    }
}
