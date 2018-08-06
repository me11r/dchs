<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ServiceTypeInterface;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    private $repository;

    public function __construct(ServiceTypeInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function index()
    {
        $items = $this->repository->getByInfo();

        return View::make('information.index')
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        abort(418, 'Раздел в разработке');
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
        abort(418, 'Раздел в разработке');
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