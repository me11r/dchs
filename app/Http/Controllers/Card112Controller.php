<?php

namespace App\Http\Controllers;

use App\AvalancheType;
use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\DiseaseType;
use App\ElevatorEmergencyType;
use App\EmergencyName;
use App\EmergencyType;
use App\FloodingPlace;
use App\FloodingReason;
use App\Http\Resources\Card112\Card112Resource;
use App\Models\Card112\Card112;
use App\Models\IncidentType;
use App\Models\Notification\NotificationGroup;
use App\Models\ServiceType;
use App\Repositories\Contracts\Card112RepositoryInterface;
use App\Ticket101ServicePlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Card112Controller extends Controller
{
    private $repository;

    public function __construct(Card112RepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }


    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = trim($request->search);

        $sort = $request->get('sort', 'created_at');
        $id = $request->input('filter.id', '');
        $city_area = $request->input('filter.city_area', '');


        $city_areas = Card112::groupBy('city_area_id')
            ->get(['city_area_id'])
            ->pluck('city_area_id')
            ->toArray();

        $city_areas = CityArea::whereIn('id', $city_areas)->get();

        if ($id) {
            $items = $this
                ->repository
                ->with([
                    'street',
                    'street.area'
                ])
                ->orderBy($sort, 'desc')
                ->where('id', $id)
                ->paginate($perPage);
        } elseif ($city_area) {

            $items = $this
                ->repository
                ->with([
                    'street',
                    'street.area'
                ])
                ->orderBy($sort, 'desc')
                ->paginate($perPage);
        } elseif ($search) {
            if (is_numeric($search)) {
                $items = $this
                    ->repository
                    ->with(['street', 'street.area'])
                    ->where('id', $search)
                    ->orderBy($sort, 'desc')
                    ->paginate($perPage);
            } else {
                try {
                    $date = Carbon::parse(str_replace(['/', '.'], '-', $search));
                } catch (\Exception $e) {
                    $date = null;
                }

                $items = $this
                    ->repository
                    ->with(['street', 'street.area'])
                    ->where('location', "like", "$search%")
                    ->orWhereDate('created_at', $date)
                    ->orWhereHas('cityArea', function ($q) use ($search) {
                        $q->where('name', "like", "$search%");
                    })
                    ->orderBy($sort, 'desc')
                    ->paginate($perPage);
            }

        } else {
            $items = $this
                ->repository
                ->with([
                    'street',
                    'street.area'
                ])
                ->orderBy($sort, 'desc')
                ->paginate($perPage);
        }


        return View::make('card112.index')
            ->with('items', $items)
            ->with('search', $search)
            ->with('city_areas', $city_areas)
            ->with('per_page', $perPage)
            ->render();
    }

    public function create()
    {
        $ticket101_service_plans = [];
        $serviceTypes = ServiceType::orderBy('name')->get(['id', 'name']);
        foreach ($serviceTypes as $item) {
            $ticket101_service_plans[] = new Ticket101ServicePlan();
        }


        return View::make('card112.create')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name', 'city_area_id']))->toArray())
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes)->toArray())
            ->with('notificationGroups', (new NotificationGroup())->get())
            ->with('model', new Card112Resource(new Card112()))
            ->with('service_plans', $ticket101_service_plans)
            ->with('emergencyTypes', EmergencyType::all())
            ->with('emergencyNames', EmergencyName::all())
            ->with('flooding_places', FloodingPlace::all())
            ->with('flooding_reasons', FloodingReason::all())
            ->with('avalanche_types', AvalancheType::all())
            ->with('elevator_emergency_types', ElevatorEmergencyType::all())
            ->with('disease_types', DiseaseType::all())
            ->with('currentTabIndex', 0)
            ->with('canChangeCreatedAt', Auth::user()->hasRight('CAN_CHANGE_CARD112_DATE'))
            ->render();
    }

    public function store(Request $request)
    {
        $index = $request->currentTabIndex;

        $req = $request->except([
            'notification_services',
            'custom_created_at',
        ]);
        $data = $this->repository->createFilledWithRelations($req);

        if ($index) {
            $back = "/card112/{$data->id}/edit/#return={$index}";
        }
        else{
            $back = "/card112/{$data->id}/edit/";
        }

        return redirect($back)
            ->with('currentTabIndex', $index);
    }

    public function show($id)
    {
        // there is no task for this
    }

    public function edit(Request $request, $id)
    {
        $ticket101_service_plans = Ticket101ServicePlan::where('card112_id', $id)->get();
        $serviceTypes = ServiceType::orderBy('name')->get(['id', 'name']);
        $model = new Card112Resource($this->repository->where('id', '=', $id)
            ->with([
                'serviceReactions',
                'service_plans',
                'service_plans.service_type',
                'chronology',
                'notificationGroups',
                'popupNotifications',
                'popupNotifications.user',
                'popupNotifications.status',
                'popupNotifications.group'
            ])
            ->first());

        if(!$model->resource) {
            return redirect('/card112/create');
        }

        return View::make('card112.edit')
            ->with('streets', collect(Street::orderBy('name')->get(['id', 'name', 'city_area_id']))->toArray())
            ->with('cityAreas', collect(CityArea::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('incidentTypes', collect(IncidentType::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('serviceTypes', collect($serviceTypes->toArray()))
            ->with('service_plans', $ticket101_service_plans)
            ->with('notificationGroups', (new NotificationGroup())->get())
            ->with('currentTabIndex', $request->input('currentTabIndex', 0))
            ->with('emergencyTypes', EmergencyType::all())
            ->with('emergencyNames', EmergencyName::all())
            ->with('flooding_places', FloodingPlace::all())
            ->with('flooding_reasons', FloodingReason::all())
            ->with('avalanche_types', AvalancheType::all())
            ->with('elevator_emergency_types', ElevatorEmergencyType::all())
            ->with('disease_types', DiseaseType::all())
            ->with('canChangeCreatedAt', Auth::user()->hasRight('CAN_CHANGE_CARD112_DATE'))
            ->with('model', $model);
    }

    public function update(Request $request, $id)
    {
        $index = $request->currentTabIndex;
        $data = $request->except([
            'notification_services',
        ]);
        $data['custom_created_at'] = isset($data['custom_created_at']) ? Carbon::parse($data['custom_created_at'])->format('Y-m-d H:i') : null;
        $this->repository->updateFilledWithRelations($data, $id);
        $this->repository->updateServicePlans($request->input('notification_services', []), $id);
        return redirect(route('card112.edit', $id))->with('currentTabIndex', $index);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect(route('card112.index'));
    }
}
