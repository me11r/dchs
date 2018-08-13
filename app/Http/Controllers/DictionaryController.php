<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:26
 */

namespace App\Http\Controllers;


use App\Dictionary;
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