<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\SirenSpeechTech;
use Illuminate\Http\Request;

class SirenSpeechTechController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $data['items'] = SirenSpeechTech::orderBy('id', 'desc')->paginate(15);
        $data['per_page'] = $perPage;
        return view('reports.siren-speech-tech.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Данные по СРУ (создать)';
        return view('reports.siren-speech-tech.edit', $data);
    }

    public function edit($id)
    {
        $data['record'] = SirenSpeechTech::find($id);
        $data['title'] = 'Данные по СРУ за дату '.$data['record']->created_at->format('d-m-Y H:i').' (редактировать)';
        return view('reports.siren-speech-tech.edit', $data);

    }

    public function delete(Request $request, $id)
    {
        SirenSpeechTech::find($id)->delete();

        return back();
    }

    public function update(Request $request, $id)
    {
        $data['record'] = SirenSpeechTech::find($id);
        $data['record']->sst = $request->sst;
        $data['record']->motor = $request->motor;
        $data['record']->demounted = $request->demounted;
        $data['record']->broken = $request->broken;
        $data['record']->inactive = $request->inactive;

        $data['record']->save();

        $data['record']->items()->delete();

        foreach ($request->input('demounted_text', []) as $item) {
            if($item != null){
                $data['record']->items()->create([
                    'text' => $item,
                    'type' => 'demounted',
                ]);
            }
        }

        foreach ($request->input('broken_text', []) as $item) {
            if($item != null){
                $data['record']->items()->create([
                    'text' => $item,
                    'type' => 'broken',
                ]);
            }
        }

        foreach ($request->input('inactive_text', []) as $item) {
            if($item != null){
                $data['record']->items()->create([
                    'text' => $item,
                    'type' => 'inactive',
                ]);
            }
        }


        return back();
    }

    public function store(Request $request)
    {
        $f = $request->all();

        $sirenTech = SirenSpeechTech::create([
            'sst' => $request->sst,
            'motor' => $request->motor,
            'demounted' => $request->demounted,
            'broken' => $request->broken,
            'inactive' => $request->inactive,
        ]);

        foreach ($request->input('demounted_text', []) as $item) {
            if($item != null){
                $sirenTech->items()->create([
                    'text' => $item,
                    'type' => 'demounted',
                ]);
            }
        }

        foreach ($request->input('broken_text', []) as $item) {
            if($item != null){
                $sirenTech->items()->create([
                    'text' => $item,
                    'type' => 'broken',
                ]);
            }
        }

        foreach ($request->input('inactive_text', []) as $item) {
            if($item != null){
                $sirenTech->items()->create([
                    'text' => $item,
                    'type' => 'inactive',
                ]);
            }
        }

        return redirect('reports/siren-speeches/edit/'.$sirenTech->id);
    }
}

