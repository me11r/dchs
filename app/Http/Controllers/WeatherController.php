<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use App\Repositories\Contracts\WeatherInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

    public function index(Request $request)
    {
        $items = Weather::orderBy('date', 'DESC');
        $items = $items->stormRecords($request->filter_storm);

        $per_page = $request->input('per_page', 50);

        $items = $items->paginate($per_page);
        $user = Auth::user();

        return View::make('weather.index')
            ->with('items', $items)
            ->with('user', $user)
            ->with('per_page', $per_page)
            ->with('filter_storm', $request->filter_storm)
            ->render();
    }

    public function create()
    {
        $record['date'] = today();
        $next_date = Carbon::tomorrow();
        return view('weather.add', compact('record', 'next_date'));
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        $record = Weather::find($id);
        $next_date = Carbon::parse($record->date)->addDay()->format('d-m-Y');
        return \view('weather.add', compact('record', 'next_date'));
    }

    public function store(Request $request)
    {
        $this->repository->createModel($request);
        return redirect(route('weather.index'));
    }

    public function update(Request $request, $id)
    {
        $this->repository->updateModel($request, $id);
        return back();
    }

    public function destroy($id)
    {
        $weather = Weather::destroy($id);
        return back();
    }
}