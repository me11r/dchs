<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\WeatherInterface;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    private $repository;

    public function __construct(WeatherInterface $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function index()
    {
        $items = $this->repository->orderBy('date', 'DESC')->get();

        return View::make('weather.index')
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        return View::make('weather.add')
            ->render();
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function store(Request $request)
    {
        $this->repository->createModel($request);
        return redirect(route('weather.index'));
    }

    public function update(Request $request, $id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function destroy($id)
    {
        abort(418, 'Раздел в разработке');
    }
}