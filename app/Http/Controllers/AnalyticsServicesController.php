<?php

namespace App\Http\Controllers;

use App\ApiDictionary;
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
use App\Services\API\ReportService;
use App\Ticket101;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AnalyticsServicesController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data['records'] = [];
        $dictionary['ssa'] = ApiDictionary::getByServiceTypeByName('Служба спасения-109 г. Алматы')->first();
        $dictionary['ssa'] = $dictionary['ssa'] ? $dictionary['ssa']->data : [];

        $dictionary['mudflow'] = [];
        $dictionary['cmk'] = [];
        $dictionary['roso'] = [];

        $data['reports'] = [
            ['name' => 'ГУ "РОСО"', 'slug' => 'roso', 'right' => '', 'dictionaries' => $dictionary['roso']],
            ['name' => 'ССА', 'slug' => 'ssa', 'right' => '', 'dictionaries' => $dictionary['ssa']],
            ['name' => 'ЦМК', 'slug' => 'cmk', 'right' => '', 'dictionaries' => $dictionary['cmk']],
            ['name' => 'АГЭУ ГУ "Казселезащита"', 'slug' => 'mudflow', 'right' => '', 'dictionaries' => $dictionary['mudflow']],
        ];

        $data['incident_types'] = IncidentType::all();
        $data['city_areas'] = CityArea::all();
        $data['ride_types'] = RideType::all();

        return view('analytics-services.index', $data);
    }

    public function search(Request $request)
    {
        $service = new ReportService('emergency');
        $requestData = [
            'date_from' => $request->dateFrom,
            'date_to' => $request->dateTo,
        ];
        $results = $service->getPeriod($requestData);
        return response()->json(['records' => $results]);
    }
}
