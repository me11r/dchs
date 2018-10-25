<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\Http\Resources\EmergencySituationResource;
use App\Models\EmergencySituation;
use App\Repositories\Contracts\EmergencySituationRepositoryInterface;
use App\Right;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if(Auth::user()->hasRight([Right::CAN_SEE_ALL_EMERGENCY_SITUATIONS]) || Auth::user()->isAdmin()){
            $items = $this->repository->with(['user', 'user.service_type'])->orderBy('id', 'DESC')->get();
        }
        else{
            $items = $this->repository->with(['user', 'user.service_type'])
                ->whereHas('user.service_type', function ($q){
                    $q->where('id', Auth::user()->service_type_id);
                })
                ->orderBy('id', 'DESC')
                ->get();
        }

        return View::make('emergency-situation.index')
            ->with('items', $items)
            ->render();
    }

    /**
     * @return string
     */
    public function create()
    {
        return View::make('emergency-situation.edit')
            ->with('cityAreas', collect((new CityArea)->orderBy('name')->get(['id', 'name']))->toArray())
            ->with('item', new EmergencySituation())
            ->with('title', 'Добавление оперативной информации')
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
     * @return string
     */
    public function show($id)
    {
        $item = $this->repository->find($id);

        return View::make('emergency-situation.show')
            ->with('item', (new EmergencySituationResource($item))->toArray($item))
            ->render();
    }

    /**
     * @param $id
     * @return string
     */
    public function edit($id)
    {
        return View::make('emergency-situation.edit')
            ->with('cityAreas', collect((new CityArea)->orderBy('name')->get(['id', 'name']))->toArray())
            ->with('item', $this->repository->with(['user', 'user.service_type'])->find($id))
            ->with('title', 'Изменение оперативной информации')
            ->render();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        if (!$request->has('can_fix_themselves')){
            $request->request->add(['can_fix_themselves' => 0]);
        }
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
