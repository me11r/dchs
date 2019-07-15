<?php

namespace App\Http\Controllers;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use App\DrillType;
use App\FireDepartment;
use App\NormType;
use App\ObjectClassification;
use App\RideType;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AnalyticsSpiasrController extends Controller
{
    public function index()
    {
        $data = [];
        $data['records'] = [];
        $data['burntObjects'] = FireObject::orderBy('name')->get();
        $data['reasons'] = TripResult::orderBy('name')->get();
        $data['cityAreas'] = CityArea::orderBy('name')->get();
        $data['fireDepartments'] = FireDepartment::sortByCustomOrder()->get();
        $data['user'] = Auth::user();

        $normTypes = NormType::all()->map(function ($item) {
            return $item->name;
        })->toArray();

        $data['normTypes'] = array_merge($normTypes, [
            'РКШУ',
            'ТСУ',
            'ПТУ',
            'ПТЗ',
            'ТДК',
            'Учения',
        ]);

        $data['rideTypes'] = RideType::all();

        $data['object_classes'] = ObjectClassification::all();

        $drillTypes = Cache::rememberForever('spi_asr_drill_types', function (){
            return DrillType::whereIn('name', ['ПТЗ','ПТУ'])->get();
        });

        $year = now()->format('Y');

        foreach ($drillTypes as $type) {

            foreach (range(1, 12) as $month) {
                //Если тип учения = ПТЗ, считаем карточки с ПТЗ и НПТЗ, т.к. это одно и то же (ARM-584)
                if($type->name == 'ПТЗ') {
                    $typesArr = [$type->id,8];
                }
                else {
                    $typesArr = [$type->id];
                }

                $data['counts'][$type->name]['per_month'][$month] = Ticket101::whereYear('custom_created_at', $year)
                    ->whereMonth('custom_created_at', $month)
                    ->whereIn('drill_type_id',$typesArr)
                    ->count();

                foreach ($data['object_classes'] as $object_class) {

                    $data['records'][$type->name][$object_class->name][$month] = Ticket101::whereYear('custom_created_at', $year)
                        ->where('object_classification_id', $object_class->id)
                        ->whereMonth('custom_created_at', $month)
                        ->whereIn('drill_type_id',$typesArr)->count();

                    $data['counts'][$type->name]['per_object'][$object_class->name] = Ticket101::whereYear('custom_created_at', $year)
                        ->whereIn('drill_type_id',$typesArr)
                        ->where('object_classification_id', $object_class->id)
                        ->count();
                }
            }
        }

        return view('analytics_spiasr.index',$data);
    }
}
