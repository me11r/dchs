<?php

namespace App\Http\Controllers;

use App\Dictionary\Street;
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


    public function index()
    {
        $items = $this->repository->with([
            'street',
            'street.area'
        ])->get();

        return View::make('card112.index')
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        return View::make('card112.create')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect(ServiceType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('model', new Card112())
            ->render();
    }

    public function store(Request $request)
    {
        $this->repository->createFilledWithRelations($request->all());
        return redirect('/card112');
    }

    public function show($id)
    {
        // @TODO to do
    }

    public function edit($id)
    {
        return View::make('card112.edit')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect(ServiceType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('model', $this->repository->where('id', '=', $id)->with(['serviceReactions', 'chronology'])->first())
            ->render();
    }

    public function update(Request $request, $id)
    {
        //@TODO
        abort('418', 'Раздел в разработке.');
    }

    public function destroy($id)
    {
        // @TODO to test
        $this->repository->delete($id);
        return redirect('/card112');
    }
}
