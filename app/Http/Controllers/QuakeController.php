<?php

namespace App\Http\Controllers;

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
        abort(418, 'Раздел в разработке');
    }

    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect(route('quakes.index'));
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