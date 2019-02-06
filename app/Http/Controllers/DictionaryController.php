<?php

namespace App\Http\Controllers;


use App\Aircraft;
use App\AircraftType;
use App\Dictionary;
use App\DistrictManager;
use App\FireDepartment;
use App\IncidentTypeCategory;
use App\Models\DailyReportPerson;
use App\Models\IncidentType;
use App\Models\NotificationService;
use App\Models\OperationalPlan;
use App\Models\ServiceType;
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

    private $dictionaries = [];
    private $user = null;

    private $additional_dicts = [
        [
            'title' => 'Пожарные части',
            'href' => '/dictionaries/fire-departments',
        ],
        [
            'title' => 'Типы инцидентов',
            'href' => '/dictionaries/incident-types',
        ],
        [
            'title' => 'Опер планы',
            'href' => '/dictionaries/operational-plans',
        ],
        [
            'title' => 'Опер карточки',
            'href' => '/dictionaries/operational-cards',
        ],
        [
            'title' => 'Типы воздушных судов',
            'href' => '/dictionaries/aircraft-types',
        ],
        [
            'title' => 'Воздушные суда',
            'href' => '/dictionaries/aircrafts',
        ],
        [
            'title' => 'Ответственные по районам',
            'href' => '/dictionaries/district-managers',
        ],
        [
            'title' => 'Персоны суточного отчета',
            'href' => '/dictionaries/daily-report-persons',
        ],
    ];

    public function __construct(Request $request)
    {
        parent::__construct();
    }

    public function before()
    {
        parent::before();
        $this->needRight(Right::CAN_EDIT_DICTIONARIES);
        $user = \auth()->user();
        $this->user = $user;
        foreach (Dictionary::all() as $dict) {
            if(!$user->isAdmin() && !$user->hasRight($dict->title)){
                continue;
            }
            else{
                $this->dictionaries[] = $dict;
            }
        }
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
        $data['fire_departments'] = FireDepartment::all();
        $data['city_areas'] = Dictionary\CityArea::all();
        $data['search'] = $request->search;

        $data['user'] = Auth::user();

        $sort = $request->sort ? $request->sort : 'id';

        if($name == 'incident-types'){
            if($request->search){
                $data['records'] = IncidentType::where('name', 'like',"%$request->search%")
                    ->paginate($data['per_page']);
            }
            else{
                $data['records'] = IncidentType::paginate($data['per_page']);
            }
            $data['title'] = "Типы инцидентов";
        }
        elseif($name == 'operational-plans'){

            $specialPlan = SpecialPlan::orderBy('fire_department_id')
                ->orderBy($sort)
            ;

            $page = $request->page ?? 1;

            if ($request->search) {
                $specialPlan = $specialPlan
                    ->where(function ($query) use ($request) {
                        $query->where('object_name', 'like', "%$request->search%");
                        $query->orWhere('location', 'like', "%$request->search%");
                        $query->orWhereHas('city_area', function ($q) use ($request) {
                            $q->where('name', $request->search);
                        });
                        $query->orWhereHas('fire_department', function ($q) use ($request) {
                            $q->where('title', $request->search);
                        });
                    });
            }

            if ($request->filter_department) {
                $specialPlan = $specialPlan
                    ->where('fire_department_id', '=', $request->filter_department);
            }

            if(!Auth::user()->department){

                $data['records'] = $specialPlan
                    ->paginate($data['per_page']);
            }
            else{
                $data['records'] = $specialPlan
                    ->where('fire_department_id', Auth::user()->fire_department_id)
                    ->paginate($data['per_page']);
            }
            $data['filter_department'] = $request->filter_department;
            $data['title'] = "Оперативные планы";
            $data['page'] = $page;
        }
        elseif($name == 'operational-cards'){

            $operCard = OperationalCard::orderBy('fire_department_id')
                ->orderBy('id')
                ->orderBy('oc_number');

            if ($request->search) {
                $operCard = $operCard
                    ->where(function ($query) use ($request) {
                        $query->where('object_name', 'like', "%$request->search%");
                        $query->orWhere('location', 'like', "%$request->search%");
                        $query->orWhere('oc_number', $request->search);
                        $query->orWhereHas('fire_department', function ($q) use ($request) {
                            $q->where('title', $request->search);
                        });
                    });
            }

            if ($request->filter_department) {
                $operCard = $operCard
                    ->where('fire_department_id', '=', $request->filter_department);
            }

            if(!Auth::user()->department){
                $data['records'] = $operCard->paginate($data['per_page']);
            }
            else{
                $data['records'] = $operCard->where('fire_department_id', Auth::user()->fire_department_id)
                    ->paginate($data['per_page']);
            }

            $data['filter_department'] = $request->filter_department;
            $data['title'] = "Оперативные карточки";
        }
        elseif($name == 'aircraft-types'){
            $aircraftType = AircraftType::orderBy('id');

            if($request->search){
                $aircraftType = $aircraftType->where('name', 'like', "%$request->search%");
            }
            $data['records'] = $aircraftType->paginate($data['per_page']);

            $data['title'] = "Типы воздушных судов";
        }
        elseif($name == 'aircrafts'){
            $aircraft = Aircraft::orderBy('id');

            if($request->search){
                $aircraft = $aircraft->where('name', 'like', "%$request->search%");
            }

            $data['records'] = $aircraft->paginate($data['per_page']);
            $data['title'] = "Воздушные суда";
        }
        elseif($name == 'district-managers'){
            $model = DistrictManager::orderBy('id');

            if($request->search){
                $model = $model->where('name', 'like', "%$request->search%")
                    ->orWhere('rank', 'like', "%$request->search%")
                    ->orWhere('nickname', 'like', "%$request->search%")
                    ->orWhere('position', 'like', "%$request->search%")
                    ->orWhereHas('city_area', function ($q) use ($request){
                        $q->where('name', $request->search);
                    })
                    ->orWhereHas('phones', function ($q) use ($request){
                        $q->where('phone', $request->search);
                    });
            }

            $data['records'] = $model->paginate($data['per_page']);
            $data['title'] = "Ответственные по районам";
        }
        elseif($name == 'fire-departments'){
            $dept = FireDepartment::orderBy($sort);

            if($request->search){
                $data['search'] = $request->search;
                $dept = $dept->where('title', 'like',"$request->search")
                    ->orWhereHas('city_area', function ($q) use ($request){
                        $q->where('name', $request->search);
                    });
            }

            if($request->city_area_id){
                $data['records'] = $dept
                ->where('city_area_id', $request->city_area_id);
                $data['city_area'] = $request->city_area_id;
            }

            if($request->filter_department){
                $data['records'] = $dept
                    ->where('id', $request->filter_department);
                $data['filter_department'] = $request->filter_department;
            }

            $data['records'] = $dept
                ->paginate($data['per_page']);

            $data['title'] = "Пожарные части";
        }
        elseif($name == 'daily-report-persons') {
            $reports = DailyReportPerson::orderBy('id');

            if($request->search){
                $reports = $reports->where('name', 'like', "%$request->search%");
            }

            $data['records'] = $reports->paginate($data['per_page']);
            $data['title'] = "Персоны суточного отчета";
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

        if(Auth::user()->anyRole([
            'admin',
            'dispatcher_od',
            'Диспетчер 101',
            'Диспетчер ОД 101',
        ])){
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
        elseif($name == 'daily-report-persons'){
            $data['record'] = DailyReportPerson::find($id);
            $data['title'] = "Персоны суточного отчета";
            $data['types'] = [
                'header' => 'Шапка',
                'footer_first' => 'Подвал 1',
                'footer_second' => 'Подвал 2',
            ];

            $data['reportTypes'] = [
                '101_daily' => '101',
                '112_daily' => '102',
            ];

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
        elseif($name == 'fire-departments'){
            $data['record'] = FireDepartment::find($id);
            $data['city_areas'] = Dictionary\CityArea::all();
            $data['title'] = "Пожарная часть";
        }
        elseif($name == 'district-managers'){
            $data['record'] = DistrictManager::find($id);
            $data['city_areas'] = Dictionary\CityArea::all();
            $data['title'] = "Ответственный по району";
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

            if($request->file){
                $fileName = time().'.'.$request->file->getClientOriginalExtension();
                $request->file->storeAs('operational-plans',$fileName);

                $record->file = $fileName;
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

            return redirect('/dictionaries/operational-plans')->with('_message', [
                'type' => 'success',
                'text' => 'Запись в справочнике успешно сохранена'
            ]);

        }
        elseif($name == 'operational-cards'){
            $record  = OperationalCard::firstOrNew(['id' => $request->id]);

            if($request->file){
                $fileName = time().'.'.$request->file->getClientOriginalExtension();
                $request->file->storeAs('operational-cards',$fileName);

                $record->file = $fileName;
            }

            $record->fire_level_id = $request->fire_level_id;
//            $record->city_area_id = $request->city_area_id;
            $record->object_name = $request->object_name;
            $record->fire_department_id = $request->fire_department_id;
            $record->location = $request->location;
            $record->note = $request->note;
            $record->oc_number = $request->oc_number;

            $record->save();

            $data['record'] = $record;
            $data['title'] = "Оперативные карты";

            return redirect('/dictionaries/operational-cards')->with('_message', [
                'type' => 'success',
                'text' => 'Запись в справочнике успешно сохранена'
            ]);

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
        elseif ($name == 'daily-report-persons') {
            $record  = DailyReportPerson::firstOrNew(['id' => $request->id]);
            $record->name = $request->name;
            $record->position = $request->position;
            $record->city = $request->city;
            $record->post = $request->post;
            $record->type = $request->type;
            $record->report_type = $request->report_type;
            $record->save();
        }
        elseif($name == 'fire-departments'){
            $record  = FireDepartment::firstOrNew(['id' => $request->id]);
            $record->title = $request->title;
            $record->city_area_id = $request->city_area_id;
            $record->recommend = $request->recommend;
            $record->goes_in_formation_report = $request->goes_in_formation_report;
            $record->address = $request->address;

            $record->save();
        }
        elseif($name == 'district-managers'){
            $record  = DistrictManager::firstOrNew(['id' => $request->id]);
            $record->name = $request->name;
            $record->rank = $request->rank;
            $record->nickname = $request->nickname;
            $record->position = $request->position;
            $record->city_area_id = $request->city_area_id;

            $record->save();

            if($request->phone_id){
                $record->phones()->delete();
                foreach ($request->phone_id as $phone) {
                    if($phone){
                        $record->phones()->create([
                            'phone' => $phone
                        ]);
                    }
                }
            }
        }

        if($request->id){
            return back()->with('_message', [
                'type' => 'success',
                'text' => 'Запись в справочнике успешно обновлена'
            ]);
        }

        return redirect('/dictionaries')->with('_message', [
            'type' => 'success',
            'text' => 'Запись в справочнике успешно сохранена'
        ]);

    }

    private function setAdditionalData($dict){
        if ($dict instanceof ServiceType){
            $this->set('users', User::all());
        }
    }

    public function getIndex()
    {
        $dicts = $this->dictionaries;

        $user = $this->user;

        $additional_dicts = [];

        foreach ($this->additional_dicts as $dict) {
            if(!$user->isAdmin() && !$user->hasRight($dict['title'])){
                continue;
            }
            else{
                $additional_dicts[] = $dict;
            }
        }

        $this->set('dictionaries', $dicts)
            ->set('additional_dicts', $additional_dicts)
        ;
    }

    public function getList(Request $request, $dict_id)
    {
        $dictionary = (new \App\Dictionary)->find($dict_id);
        $this->set('dictinfo', $dictionary);

        $search = trim($request->search);

        $dict = new $dictionary->model;

        $fields = $this->getEditableFields($dict);

        if(array_search('name',$fields) !== false) {
            $orderBy = 'name';
        }
        else {
            $orderBy = 'id';
        }

        if(in_array('title', $fields) && $search){
            $this->set('dictionary', $dict
                ->where('title', 'like', "%$search%")
                ->orderBy($orderBy)
                ->get());
        }
        elseif(in_array('name', $fields) && $search){
            $this->set('dictionary', $dict
                ->where('name', 'like', "%$search%")
                ->orderBy($orderBy)
                ->get());
        }
        else{
            $this->set('dictionary', $dict
                ->orderBy($orderBy)
                ->get());
        }

        $this->set('fields', $fields);
        $this->set('search', $search);
    }

    public function getEdit($dict_id, $row_id = 0)
    {
        $dictionary = (new \App\Dictionary)->find($dict_id);
        $this->set('dictinfo', $dictionary);
        $dict = new $dictionary->model;
        $this->set('record', $dict->findOrNew($row_id));
        $fields = $this->getEditableFields($dict);
        $this->set('fields', $fields);

        $this->setAdditionalData($dict);

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
                break;
            case 'district-managers':
                $dict = DistrictManager::class;
                break;
            case 'fire-departments':
                $dict = FireDepartment::class;
                break;
            case 'aircraft-types':
                $dict = AircraftType::class;
                break;
            case 'daily-report-persons':
                $dict = DailyReportPerson::class;
                break;
            case 'aircrafts':
                $dict = Aircraft::class;
                break;
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
