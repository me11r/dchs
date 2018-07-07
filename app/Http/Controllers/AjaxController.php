<?php

namespace App\Http\Controllers;


use App\CarRoute;
use App\IATACode;
use App\Right;
use App\Shipment;
use App\Ticket;
use App\User;
use App\City;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class AjaxController extends AuthorizedController
{
    public function getOfficeCodes(Request $request)
    {
        $q = $request->input('q', '');
        $office = IATACode::where('code', 'like', $q . '%')->get(['code as id', 'code as text'])->toArray();

        return response()->json(['items' => $office]);
    }

    public function getManagers(Request $request)
    {
        $q = $request->input('q', '');
        $managers = (new User())->where('name', 'like', '%' . $q . '%')->whereHas('rights', function ($query) {
            $query->where('user_rights.right_id', '=', Right::CAN_ASSIGN_REQUEST);
        })->get(['id', 'name as text'])->toArray();

        return response()->json(['items' => $managers]);
    }

    public function getCities(Request $request)
    {
        $cityName = Arr::get($request, 'param', null);
        $limit = (int)Arr::get($request, 'limit', 30);
        if ($cityName) {
            $cities = City::where('title_ru', 'LIKE', '%' . $cityName . '%')->orderBy('important', 'desc')->take($limit)->get();
            $items = [];
            foreach ($cities as $city) {
                $data = [];
                $data['id'] = $city->id;
                $data['name'] = $city->title . ' (';
                if ($city->region) {
                    $data['name'] .= $city->region->title . ', ';
                    $data['region'] = $city->region;
                }
                if ($city->country) {
                    $data['name'] .= $city->country->title;
                    $data['country'] = $city->country;
                }
                $data['name'] .= ')';
                $data['title_ru'] = $city->title;
                $data['code'] = $city->code;
                $items[] = $data;
            }
            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Неверные данные'
        ]);
    }

    public function getTicketDetails(Request $request)
    {
        $ticketId = $request->get('ticket_id', 0);
        $ticket = Ticket::find($ticketId);
        return response()->json($ticket);
    }

    public function getRouteList()
    {
        return response()->json(CarRoute::with(['fromCity', 'toCity', 'cities'])->get());
    }

    public function getTicketsNotInLoading()
    {
        $period = Carbon::now();
        $period->subMonth(1);
        $tickets = Ticket::with(['client', 'transport'])->whereIn('status_id', [2, 6, 7])
            ->whereNull('shipment_id')
            ->where('created_at', '>', $period)
            ->get();
        return response()->json($tickets);
    }

    public function getShipmentsForRoute($routeId)
    {
        $ships = Shipment::whereCarRouteId((int)$routeId)->with(['motorTransport'])->where('status_id', 1)->get();
        return response()->json($ships);
    }

    public function postaddShipmentForRoute($routeId)
    {
        $route = (new CarRoute())->find($routeId);
        $shipment = new Shipment();
        $shipment->from_city_id = $route->from_city_id;
        $shipment->to_city_id = $route->to_city_id;
        $shipment->car_route_id = $routeId;
        $shipment->status_id = 1;
        $shipment->carrier_id = 1;
        $shipment->save();
        $shipment->refresh();
        return response()->json($shipment);
    }

    public function getTicketsForShipment($shipmentId)
    {
        $tickets = Ticket::whereShipmentId($shipmentId)->with(['client', 'transport'])->get();
        return response()->json($tickets);
    }

    public function getClearTicketFromShipment($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        $ticket->shipment_id = null;
        $ticket->save();
        return response()->json($ticket);
    }

    public function postSetTicketForShipment($ticketId, $shipmentId)
    {
        $ticket = Ticket::find($ticketId);
        $ticket->shipment_id = $shipmentId;
        $ticket->save();
        return response()->json($ticket);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AccessDeniedException
     */
    public function postCityEdit(Request $request)
    {
        $this->noLayout();
        $this->needRight(Right::CAN_LOGIN);
        $cityId = $request->get('id', -1);
        $city = City::find($cityId);
        $city->title_ru = $request->get('title_ru', '');
        $city->code = $request->get('code', '');
        $city->save();
        if ($city) {
            $result = [
                'all' => $request->all(),
                'success' => true,
            ];
        } else {
            $result = [
                'all' => $request->all(),
                'success' => false,
            ];
        }
        return response()->json($result);
    }
}