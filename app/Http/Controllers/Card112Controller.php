<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\Http\Resources\Card112\Card112Resource;
use App\Models\Card112\Card112;
use App\Models\IncidentType;
use App\Models\ServiceType;
use App\Repositories\Contracts\Card112RepositoryInterface;
use App\Ticket101ServicePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Card112Controller extends Controller
{
    private $repository;

    public function __construct(Card112RepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }


    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = trim($request->search);

        $sort = $request->get('sort', 'created_at');
        $id = $request->input('filter.id', '');
        $city_area = $request->input('filter.city_area', '');


        $city_areas = Card112::groupBy('city_area_id')
            ->get(['city_area_id'])
            ->pluck('city_area_id')
            ->toArray();

        $city_areas = CityArea::whereIn('id', $city_areas)->get();

        if($id){
            $items = $this
                ->repository
                ->with([
                    'street',
                    'street.area'
                ])
                ->orderBy($sort, 'desc')
                ->where('id',$id)
                ->paginate($perPage);
        }
        elseif($city_area){

            $items = $this
                ->repository
                ->with([
                    'street',
                    'street.area'
                ])
                ->orderBy($sort, 'desc')
                ->paginate($perPage);
        }
        elseif($search){
            if(is_numeric($search)){
                $items = $this
                    ->repository
                    ->with(['street', 'street.area'])
                    ->where('id', $search)
                    ->orderBy($sort,'desc')
                    ->paginate($perPage);
            }
            else{
                $items = $this
                    ->repository
                    ->with(['street', 'street.area'])
                    ->where('location', "like", "$search%")
                    ->orWhereHas('cityArea', function ($q) use ($search){
                        $q->where('name', "like", "$search%");
                    })
                    ->orderBy($sort,'desc')
                    ->paginate($perPage);
            }

        }
        else{
            $items = $this
                ->repository
                ->with([
                    'street',
                    'street.area'
                ])
                ->orderBy($sort, 'desc')
                ->paginate($perPage);
        }


        return View::make('card112.index')
            ->with('items', $items)
            ->with('search', $search)
            ->with('city_areas', $city_areas)
            ->with('per_page', $perPage)
            ->render();
    }

    public function create()
    {
        $ticket101_service_plans = [];
        $serviceTypes = ServiceType::orderBy('name')->get(['id', 'name']);
        foreach ($serviceTypes as $item) {
            $ticket101_service_plans[] = new Ticket101ServicePlan();
        }

        $services = ServiceType::all()->pluck('id')->toArray();

        /*$model = new Card112();
        $model->call_time = now();
        $model->city_area_id = CityArea::select('*')->first()->id;
        $model->save();

        foreach ($services as $service) {
            Ticket101ServicePlan::create([
                'card112_id' => $model->id
            ]);
        }*/

        return View::make('card112.create')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name', 'city_area_id']))->toArray())
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes)->toArray())
            ->with('model', new Card112Resource(new Card112()))
//            ->with('model', $model)
            ->with('service_plans', $ticket101_service_plans)
            ->render();
    }

    public function store(Request $request)
    {
        $data = $this->repository->createFilledWithRelations($request->all());
        return redirect('/card112/'.$data->id.'/edit');
        return redirect(route('card112.index'));
    }

    public function show($id)
    {
        // there is no task for this
    }

    public function edit($id)
    {
        $ticket101_service_plans = Ticket101ServicePlan::where('card112_id', $id)->get();
        $serviceTypes = ServiceType::orderBy('name')->get(['id', 'name']);

        return View::make('card112.edit')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name', 'city_area_id']))->toArray())
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes->toArray()))
            ->with('service_plans', $ticket101_service_plans)
            ->with('model', new Card112Resource($this->repository->where('id', '=', $id)->with(['serviceReactions', 'chronology'])->first()));
    }

    public function update(Request $request, $id)
    {
        $this->repository->updateFilledWithRelations($request->all(), $id);
        return redirect(route('card112.edit', $id));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect(route('card112.index'));
    }
}
