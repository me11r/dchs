<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\Repositories\Contracts\EmergencySituationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EmergencySituationController extends Controller
{
    /**
     * @var EmergencySituationRepositoryInterface
     */
    private $repository;

    /**
     * EmergencySituationController constructor.
     * @param EmergencySituationRepositoryInterface $repository
     */
    public function __construct(EmergencySituationRepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }


    /**
     * @return string
     */
    public function index()
    {
        $items = $this->repository->all();

        return View::make('emergency-situation.index')
            ->with('items', $items)
            ->render();
    }

    /**
     * @return string
     */
    public function create()
    {
        return View::make('emergency-situation.create')
            ->with('cityAreas', collect((new CityArea)->orderBy('name')->get(['id', 'name']))->toArray())
            ->render();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect(route('emergency-situation.index'));
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        // there is no task for this
    }

    /**
     * @param $id
     * @return string
     */
    public function edit($id)
    {
        return View::make('emergency-situation.edit')
            ->with('cityAreas', collect((new CityArea)->orderBy('name')->get(['id', 'name']))->toArray())
            ->render();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->repository->update($request->all(), $id);
        return redirect(route('emergency-situation.edit', $id));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect(route('emergency-situation.index'));
    }
}
