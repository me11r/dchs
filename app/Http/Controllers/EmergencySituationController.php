<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\Http\Resources\EmergencySituationResource;
use App\Models\EmergencySituation;
use App\PopupNotification;
use App\Repositories\Contracts\EmergencySituationRepositoryInterface;
use App\Right;
use App\Services\ReportExport\EmergencySituationWordExport;
use Carbon\Carbon;
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
    public function index(Request $request)
    {
        $popup_notification_types = [101, 112];

        if(Auth::user()->hasRight([Right::CAN_SEE_ALL_EMERGENCY_SITUATIONS])){
            $items = $this->repository->with(['user', 'user.service_type']);
        }
        else{
            $items = $this->repository->with(['user', 'user.service_type'])
                ->whereHas('user.service_type', function ($q){
                    $q->where('id', Auth::user()->service_type_id);
                });
        }

        $popupNotification = $request->popup_notification;

        if ($popupNotification == 101) {
            $items = $items->where('notification_101', true);
        }

        if ($popupNotification == 112) {
            $items = $items->where('notification_112', true);
        }


        $items = $items->orderBy('id', 'DESC')
            ->get();

        return View::make('emergency-situation.index')
            ->with('items', $items)
            ->with('popup_notification', $popupNotification)
            ->with('popup_notification_types', $popup_notification_types)
            ->render();
    }

    /**
     * @return string
     */
    public function create()
    {
        if(!Auth::user()->hasRight(['CAN_CREATE_EMERGENCY_SITUATION'])){
            $this->throwAccessDenied();
        }
        return View::make('emergency-situation.edit')
            ->with('item', new EmergencySituation())
            ->with('title', 'Добавление оперативной информации')
            ->with('user', Auth::user())
            ->render();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if(!Auth::user()->hasRight(['CAN_CREATE_EMERGENCY_SITUATION'])){
            $this->throwAccessDenied();
        }
        $all = $request->all();

        $all['notification_101'] = $request->notification_101 ? 1 : 0;
        $all['notification_112'] = $request->notification_112 ? 1 : 0;

        $all['user_id'] = Auth::id();
        $date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : now()->format('Y-m-d');
        $time = $request->time ? Carbon::parse($request->time)->format('H:i:s') : now()->format('H:i:s');
        $all['date_time'] = "$date $time";
        unset($all['date'], $all['time']);
        $id = $this->repository->create($all)->id;

        $serviceName = Auth::user()->service_type->name ?? null;

        if ($request->notification_101) {
            PopupNotification::sendAccordingToRight([
                'sender_id' => $all['user_id'],
                'message' => "Оперативная информация для службы {$serviceName} изменена пользователем ".Auth::user()->name,
                'url' => "/emergency-situation/{$id}/edit",
            ],['CAN_RECEIVE_EMERGENCY_SITUATION_NOTIFICATION_101']);
        }

        if ($request->notification_112) {
            PopupNotification::sendAccordingToRight([
                'sender_id' => $all['user_id'],
                'message' => "Оперативная информация для службы {$serviceName} изменена пользователем " . Auth::user()->name,
                'url' => "/emergency-situation/{$id}/edit",
            ], ['CAN_RECEIVE_EMERGENCY_SITUATION_NOTIFICATION_112']);
        }

        return redirect(route('emergency-situation.index'));
    }


    /**
     * @param $id
     * @return string
     */
    public function show($id)
    {
        if(!Auth::user()->hasRight(['CAN_SEE_EMERGENCY_SITUATION'])){
            $this->throwAccessDenied();
        }
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
        if(!Auth::user()->hasRight(['CAN_EDIT_EMERGENCY_SITUATION'])){
            $this->throwAccessDenied();
        }
        return View::make('emergency-situation.edit')
            ->with('item', $this->repository->with(['user', 'user.service_type'])->find($id))
            ->with('title', 'Изменение оперативной информации')
            ->with('user', Auth::user())
            ->render();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        if(!Auth::user()->hasRight(['CAN_EDIT_EMERGENCY_SITUATION'])){
            $this->throwAccessDenied();
        }

        $all = $request->all();

        $all['notification_101'] = $request->notification_101 ? 1 : 0;
        $all['notification_112'] = $request->notification_112 ? 1 : 0;

        $all['user_id'] = Auth::id();
        $date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : now()->format('Y-m-d');
        $time = $request->time ? Carbon::parse($request->time)->format('H:i:s') : now()->format('H:i:s');
        $all['date_time'] = "$date $time";
        unset($all['date'], $all['time']);

        $serviceName = Auth::user()->service_type->name ?? null;

        if ($request->notification_101) {
            PopupNotification::sendAccordingToRight([
                'sender_id' => $all['user_id'],
                'message' => "Оперативная информация для службы {$serviceName} изменена пользователем ".Auth::user()->name,
                'url' => "/emergency-situation/{$id}/edit",
            ],['CAN_RECEIVE_EMERGENCY_SITUATION_NOTIFICATION_101']);
        }

        if ($request->notification_112) {
            PopupNotification::sendAccordingToRight([
                'sender_id' => $all['user_id'],
                'message' => "Оперативная информация для службы {$serviceName} изменена пользователем ".Auth::user()->name,
                'url' => "/emergency-situation/{$id}/edit",
            ],['CAN_RECEIVE_EMERGENCY_SITUATION_NOTIFICATION_112']);
        }

        $this->repository->update($all, $id);
        return redirect(route('emergency-situation.edit', $id));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        if(!Auth::user()->hasRight(['CAN_DELETE_EMERGENCY_SITUATION'])){
            $this->throwAccessDenied();
        }
        $this->repository->delete($id);
        return redirect(route('emergency-situation.index'));
    }

    public function download($id)
    {
        $item = $this->repository->find($id);
        $arrayData = (new EmergencySituationResource($item))->toArray($item);
        $word = new EmergencySituationWordExport($arrayData);

        $writer = $word->getWriter('Word2007');
        $fileName = 'Информация по ЧС - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }
}
