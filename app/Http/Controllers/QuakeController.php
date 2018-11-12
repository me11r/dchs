<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuakeResource;
use App\Repositories\Contracts\QuakeInterface;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class QuakeController extends Controller
{
    private $repository;

    public function __construct(QuakeInterface $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function index()
    {
        $items = $this->repository->orderBy('date_almaty', 'DESC')->get();

        return View::make('quakes.index')
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        return View::make('quakes.add')
            ->render();
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        return View::make('quakes.edit')
            ->with('model', new QuakeResource($this->repository->find($id)))
            ->render();
    }

    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect(route('quakes.index'));
    }

    public function update(Request $request, $id)
    {
        $this->repository->update($request->all(), $id);
        return redirect(route('quakes.index'));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect(route('quakes.index'));
    }
}
