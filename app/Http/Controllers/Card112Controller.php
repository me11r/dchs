<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\Http\Resources\Card112\Card112Resource;
use App\Models\Card112\Card112;
use App\Models\IncidentType;
use App\Models\ServiceType;
use App\Repositories\Contracts\Card112RepositoryInterface;
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
        $items = $this
            ->repository
            ->with([
                'street',
                'street.area'
            ])
            ->paginate($perPage);

        return View::make('card112.index')
            ->with('items', $items)
            ->with('per_page', $perPage)
            ->render();
    }

    public function create()
    {
        return View::make('card112.create')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name', 'city_area_id']))->toArray())
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect(ServiceType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('model', new Card112Resource(new Card112()))
            ->render();
    }

    public function store(Request $request)
    {
        $this->repository->createFilledWithRelations($request->all());
        return redirect(route('card112.index'));
    }

    public function show($id)
    {
        // there is no task for this
    }

    public function edit($id)
    {
        return View::make('card112.edit')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name', 'city_area_id']))->toArray())
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect(ServiceType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('model', new Card112Resource($this->repository->where('id', '=', $id)->with(['serviceReactions', 'chronology'])->first()))
            ->render();
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
