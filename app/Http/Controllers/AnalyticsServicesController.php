<?php

namespace App\Http\Controllers;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use App\DrillType;
use App\FireDepartment;
use App\Models\IncidentType;
use App\NormType;
use App\ObjectClassification;
use App\RideType;
use App\Ticket101;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AnalyticsServicesController extends Controller
{
    public function index()
    {
        $data = [];
        $data['records'] = [];
        $data['reports'] = [
            ['name' => 'ГУ "РОСО"', 'slug' => 'roso', 'right' => ''],
            ['name' => 'ССА', 'slug' => 'ssa', 'right' => ''],
            ['name' => 'ЦМК', 'slug' => 'cmk', 'right' => ''],
            ['name' => 'АГЭУ ГУ "Казселезащита"', 'slug' => 'mudflow', 'right' => ''],
        ];
        $data['incident_types'] = IncidentType::all();
        $data['city_areas'] = CityArea::all();
        $data['ride_types'] = RideType::all();
        $this->makeRequest();

        return view('analytics-services.index',$data);
    }

    public function makeRequest()
    {
        $client = new Client();
        $results = [];
        $url = "http://devpoint.kz/xml/emergency/get_period/21.11.2019/30.11.2019/";
        $response = $client->request('GET', $url);
        $response = $response->getBody() ? $response->getBody()->getContents() : null;
        $xml = new \SimpleXMLElement($response);
        foreach ($xml as $item) {
            $results[] = [
                'date' => $item->DATE->__toString(),
                'category' => $item->CAT->__toString(),
                'sub_category' => $item->SUBCAT->__toString(),
                'state' => $item->STATE->__toString(),
                'district' => $item->DISTRICT->__toString(),
                'street' => $item->STREET->__toString(),
                'house' => $item->HOUSE->__toString(),
                'injured_count' => $item->INJURED_COUNT->__toString(),
                'lost_count' => $item->LOST_COUNT->__toString(),
                'saved_count' => $item->SAVED_COUNT->__toString(),
            ];
        }
        dd($results);
    }
}
