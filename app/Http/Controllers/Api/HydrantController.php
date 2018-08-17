<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\HydrantResource;
use App\Repositories\Contracts\HydrantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HydrantController extends Controller
{
    private $repository;

    public function __construct(HydrantRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $items = $this->repository->with(['fireDepartment'])->get();
        return HydrantResource::collection($items);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $this->repository->create($request->all());
    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        $this->repository->update($request->all(), $id);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
    }

    public function getHydrantsForPointByRadius(Request $request)
    {
        $items = $this->repository
            ->searchForPointByRadius(
                (double)$request->get('latitude'),
                (double)$request->get('longitude'),
                (double)$request->get('distance')
            )
            ->all();
        return HydrantResource::collection($items);
    }
}
