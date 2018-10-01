<?php

namespace App\Http\Controllers;


use App\Dictionary;
use App\IncidentTypeCategory;
use App\Models\IncidentType;
use App\Right;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DictionaryController extends AuthorizedController
{
    protected $hidden_attributes = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected function getEditableFields(Model $model) {
        $attrs = $model->getFillable();
        $attrs = array_filter($attrs, function ($elem) {
            if (\in_array($elem, $this->hidden_attributes, true)) {
                return false;
            }
            return true;
        });
        return $attrs;
    }

    public function getIndexByName(Request $request, $name)
    {
        $view = "dictionary.{$name}.index";
        $data['create_path'] = "/dictionaries/{$name}/create";
        $data['edit_path'] = "/dictionaries/{$name}/";
        $data['per_page'] = $request->get('per_page', 20);

        if($name == 'incident-types'){
            $data['records'] = IncidentType::paginate($data['per_page']);
            $data['title'] = "Типы инцидентов";
        }
        return view($view, $data);
    }

    public function getEditByName(Request $request, $name, $id)
    {
        $view = "dictionary.{$name}.add-edit";
        $data['create_path'] = "/dictionaries/{$name}/create";
        $data['edit_path'] = "/dictionaries/{$name}/";
        $data['per_page'] = $request->get('per_page', 20);

        if($name == 'incident-types'){
            $data['record'] = IncidentType::findOrFail($id);
            $data['title'] = "Типы инцидентов";
            $data['incident_categories'] = IncidentTypeCategory::all();

        }
        return view($view, $data);
    }

    public function postEditCreateByName(Request $request, $name)
    {
        $data['create_path'] = "/dictionaries/{$name}/create";
        $data['edit_path'] = "/dictionaries/{$name}/";
        $data['per_page'] = $request->get('per_page', 20);

        if($name == 'incident-types'){
            $record  = IncidentType::findOrFail($request->id);

            $record->name = $request->name;
            $record->category_id = $request->category_id;
            $record->save();

            $data['record'] = $record;
            $data['title'] = "Типы инцидентов";
            $data['incident_categories'] = IncidentTypeCategory::all();

        }
        return back()->with('_message', [
            'type' => 'success',
            'text' => 'Запись в справочнике успешно обновлена'
        ]);
    }

    public function __construct(Request $request)
    {
        parent::__construct();
    }

    public function before()
    {
        $this->needRight(Right::CAN_EDIT_DICTIONARIES);
    }

    public function getIndex()
    {
        $dicts = Dictionary::all();
        $this->set('dictionaries', $dicts);
    }

    public function getList($dict_id)
    {
        $dictionary = (new \App\Dictionary)->find($dict_id);
        $this->set('dictinfo', $dictionary);
        $dict = new $dictionary->model;
        $this->set('dictionary', $dict->get());
        $fields = $this->getEditableFields($dict);
        $this->set('fields', $fields);
    }

    public function getEdit($dict_id, $row_id = 0)
    {
        $dictionary = (new \App\Dictionary)->find($dict_id);
        $this->set('dictinfo', $dictionary);
        $dict = new $dictionary->model;
        $this->set('record', $dict->findOrNew($row_id));
        $fields = $this->getEditableFields($dict);
        $this->set('fields', $fields);

    }

    public function postEdit(Request $request, $dict_id, $row_id = null)
    {
        $dictionary = (new \App\Dictionary)->find($dict_id);
        $this->set('dictinfo', $dictionary);
        $dict = new $dictionary->model;
        $record = $dict->findOrNew($row_id);
        $record->fill($request->all());
        $record->save();
        return redirect('/dictionaries/list/' . $dict_id)->with('_message', [
            'type' => 'success',
            'text' => 'Запись в справочнике успешно сохранена'
        ]);
    }
}