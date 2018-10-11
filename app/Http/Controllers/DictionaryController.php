<?php

namespace App\Http\Controllers;


use App\Aircraft;
use App\AircraftType;
use App\Dictionary;
use App\FireDepartment;
use App\IncidentTypeCategory;
use App\Models\IncidentType;
use App\Models\OperationalPlan;
use App\Models\SpecialPlan;
use App\OperationalCard;
use App\Right;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DictionaryController extends AuthorizedController
{
    protected $hidden_attributes = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function __construct(Request $request)
    {
        parent::__construct();
    }

    public function before()
    {
        $this->needRight(Right::CAN_EDIT_DICTIONARIES);
    }

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
        $data['type'] = $name;

        $sort = $request->sort ? $request->sort : 'id';

        if($name == 'incident-types'){
            $data['records'] = IncidentType::paginate($data['per_page']);
            $data['title'] = "Типы инцидентов";
        }
        elseif($name == 'operational-plans'){
            if(!Auth::user()->department){

                $data['records'] = SpecialPlan::orderBy($sort)->paginate($data['per_page']);
            }
            else{
                $data['records'] = SpecialPlan::where('fire_department_id', Auth::user()->fire_department_id)
                    ->orderBy($sort)
                    ->paginate($data['per_page']);
            }

            if($request->filter_department){
                $data['records'] = SpecialPlan::orderBy($sort)
                    ->where('fire_department_id', $request->filter_department)
//                    ->paginate($data['per_page'])
                    ->get();
            }


            $data['user'] = Auth::user();
            $data['fire_departments'] = FireDepartment::all();
            $data['title'] = "Оперативные планы";
            $data['filter_department'] = $request->filter_department;
        }
        elseif($name == 'operational-cards'){
            if(!Auth::user()->department){
                $data['records'] = OperationalCard::paginate($data['per_page']);
            }
            else{
                $data['records'] = OperationalCard::where('fire_department_id', Auth::user()->fire_department_id)
                    ->paginate($data['per_page']);
            }
            $data['title'] = "Оперативные карточки";
        }
        elseif($name == 'aircraft-types'){
            $data['records'] = AircraftType::paginate($data['per_page']);
            $data['title'] = "Типы воздушных судов";
        }
        elseif($name == 'aircraft-types'){
            $data['records'] = AircraftType::paginate($data['per_page']);
            $data['title'] = "Типы воздушных судов";
        }
        elseif($name == 'aircrafts'){
            $data['records'] = Aircraft::paginate($data['per_page']);
            $data['title'] = "Воздушные суда";
        }


        return view($view, $data);
    }

    public function getEditByName(Request $request, $name, $id = null)
    {
        $view = "dictionary.{$name}.add-edit";
        $data['create_path'] = "/dictionaries/{$name}/create";
        $data['edit_path'] = "/dictionaries/{$name}/";
        $data['per_page'] = $request->get('per_page', 20);
        $data['type'] = $name;

        if(Auth::id() == 1){
            $fireDepts = FireDepartment::all();
        }
        else{
            $fireDepts = FireDepartment::where('id', Auth::user()->fire_department_id)
                ->get();
        }

        if($name == 'incident-types'){
            $data['record'] = IncidentType::find($id);
            $data['title'] = "Тип инцидента";
            $data['incident_categories'] = IncidentTypeCategory::all();

        }
        elseif($name == 'operational-plans'){

            $data['record'] = SpecialPlan::find($id);
            $data['title'] = "Оперативный план";
            $data['fire_levels'] = Dictionary\FireLevel::all();
            $data['city_areas'] = Dictionary\CityArea::all();
            $data['fire_departments'] = $fireDepts;
            $data['operational_plans'] = OperationalPlan::all();

        }
        elseif($name == 'operational-cards'){
            $data['record'] = OperationalCard::find($id);
            $data['title'] = "Оперативная карточка";
            $data['fire_levels'] = Dictionary\FireLevel::all();
            $data['city_areas'] = Dictionary\CityArea::all();
            $data['fire_departments'] = $fireDepts;
            $data['operational_plans'] = OperationalPlan::all();

        }
        elseif($name == 'aircraft-types'){
            $data['record'] = AircraftType::find($id);
            $data['title'] = "Тип воздушного судна";

        }
        elseif($name == 'aircrafts'){
            $data['record'] = Aircraft::find($id);
            $data['aircraft_types'] = AircraftType::all();
            $data['types'] = [
                'airplane' => 'Самолет',
                'helicopter' => 'Вертолет',
            ];
            $data['title'] = "Воздушное судно";
        }
        return view($view, $data);
    }

    public function postEditCreateByName(Request $request, $name)
    {
        $data['create_path'] = "/dictionaries/{$name}/create";
        $data['edit_path'] = "/dictionaries/{$name}/";
        $data['per_page'] = $request->get('per_page', 20);

        if($name == 'incident-types'){
            $record  = IncidentType::find($request->id);

            if(!$record){
                $record = new IncidentType();
            }

            $record->name = $request->name;
            $record->category_id = $request->category_id;
            $record->save();

            $data['record'] = $record;
            $data['title'] = "Типы инцидентов";
            $data['incident_categories'] = IncidentTypeCategory::all();

        }
        elseif($name == 'operational-plans'){
            $record  = SpecialPlan::find($request->id);

            if($request->operational_plan !== null){
                $operational_plan = OperationalPlan::firstOrCreate(['name' => $request->operational_plan]);
            }

            $operational_plan_id = isset($operational_plan) ? $operational_plan->id : $request->operational_plan_id;

            if(!$record){
                $record = new SpecialPlan();
            }

            $record->fire_level_id = $request->fire_level_id;
            $record->city_area_id = $request->city_area_id;
            $record->object_name = $request->object_name;
            $record->fire_department_id = $request->fire_department_id;
            $record->operational_plan_id = $operational_plan_id;
            $record->location = $request->location;
            $record->year_of_development = $request->year_of_development;


            $record->save();

            $data['record'] = $record;
            $data['title'] = "Оперативные планы";
            $data['incident_categories'] = IncidentTypeCategory::all();

        }
        elseif($name == 'operational-cards'){
            $record  = OperationalCard::firstOrNew(['id' => $request->id]);

            $record->fire_level_id = $request->fire_level_id;
//            $record->city_area_id = $request->city_area_id;
            $record->object_name = $request->object_name;
            $record->fire_department_id = $request->fire_department_id;
            $record->location = $request->location;
            $record->note = $request->note;

            $record->save();

            $data['record'] = $record;
            $data['title'] = "Оперативные карты";

        }
        elseif($name == 'aircraft-types'){
            $record  = AircraftType::firstOrNew(['id' => $request->id]);
            $record->name = $request->name;
            $record->save();

        }
        elseif($name == 'aircrafts'){
            $record  = Aircraft::firstOrNew(['id' => $request->id]);
            $record->name = $request->name;
            $record->number = $request->number;
            $record->type = $request->type;
            $record->aircraft_type_id = $request->aircraft_type_id;

            $record->save();

        }

        return back()->with('_message', [
            'type' => 'success',
            'text' => 'Запись в справочнике успешно обновлена'
        ]);
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

    public function delete(Request $request, $dict_id, $row_id) {
        $returnUrl = $request->get('return_url', '/dictionaries/list/' . $dict_id);

        $dictionary = (new \App\Dictionary)->find($dict_id);
        $this->set('dictinfo', $dictionary);
        $dict = new $dictionary->model;

        $record = $dict->findOrFail($row_id);
        $record->delete();

        return redirect($returnUrl)->with('_message', [
            'type' => 'success',
            'text' => 'Запись успешно закрыта/удалена'
        ]);
    }

    public function deleteByName($name, $row_id) {
        switch ($name)
        {
            case 'incident-types':
                $dict = IncidentType::class;
                break;
            case 'operational-plans':
                $dict = SpecialPlan::class;
                break;
            case 'operational-cards':
                $dict = OperationalCard::class;
        }

        $dict = new $dict;

        $record = $dict->findOrFail($row_id);
        $record->delete();

        return redirect()->back()->with('_message', [
            'type' => 'success',
            'text' => 'Запись успешно закрыта/удалена'
        ]);
    }
}
