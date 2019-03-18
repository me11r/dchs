<?php

namespace App\Http\Controllers;

use App\Dictionary;
use App\DictionaryCategory;
use Illuminate\Http\Request;

class DictionaryEntityController extends Controller
{
    public function edit(Request $request, $id)
    {
        $data['dictionary'] = Dictionary::findOrFail($id);
        $data['dictionary_categories'] = DictionaryCategory::all();

        return view('dictionary._entity.add-edit',$data);
    }

    public function createEdit(Request $request)
    {
        $id = $request->id;
        $data['dictionary'] = Dictionary::find($id) ? Dictionary::find($id) : new Dictionary();
        $data['dictionary_categories'] = DictionaryCategory::all();

        $data['dictionary']->table = $request->table ?? '';
        $data['dictionary']->title = $request->title;
        $data['dictionary']->url = $request->url;
        $data['dictionary']->model = $request->model ?? '';
        $data['dictionary']->dictionary_category_id = $request->dictionary_category_id ?? '';
        $data['dictionary']->sort_order = $request->sort_order;

        $data['dictionary']->save();

        return redirect("/dictionaries-entity/{$data['dictionary']->id}/edit");
    }
}
