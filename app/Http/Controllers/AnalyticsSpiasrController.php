<?php

namespace App\Http\Controllers;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
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
        $data['burntObjects'] = BurntObject::orderBy('name')->get();
        $data['reasons'] = TripResult::orderBy('name')->get();
        $data['cityAreas'] = CityArea::orderBy('name')->get();
        $data['fireDepartments'] = FireDepartment::all();
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

        /** @var Collection $itemsByTheYear */
        $itemsByTheYear = Ticket101::whereYear('created_at', $year)->get(['created_at', 'drill_type_id', 'drill_type_id', 'object_classification_id']);

        foreach ($drillTypes as $type) {

            foreach (range(1, 12) as $month) {
                $date = Carbon::create($year, $month);
                $itemsByTheMonth = $itemsByTheYear
                    ->where('created_at', '>=', $date->format('Y-m-d'))
                    ->where('created_at', '<=', $date->addMonth()->format('Y-m-d'));

                $data['counts'][$type->name]['per_month'][$month] = $itemsByTheMonth
                    ->where('drill_type_id',$type->id)
                    ->count();

                foreach ($data['object_classes'] as $object_class) {

                    $data['records'][$type->name][$object_class->name][$month] = $itemsByTheMonth
                        ->where('drill_type_id',$type->id)
                        ->where('object_classification_id', $object_class->id)
                        ->count();

                    $data['counts'][$type->name]['per_object'][$object_class->name] = $itemsByTheYear
                        ->where('drill_type_id',$type->id)
                        ->where('object_classification_id', $object_class->id)
                        ->count();
                }
            }
        }

//        $drillTypes = DrillType::whereIn('name', ['ПТЗ','ПТУ'])->get();
//
//        $year = now()->format('Y');
//
//
//        foreach ($drillTypes as $type) {
//
//            foreach (range(1, 12) as $month) {
//
//                $data['counts'][$type->name]['per_month'][$month] = Ticket101::whereYear('created_at', $year)
//                    ->whereMonth('created_at', $month)
//                    ->where('drill_type_id',$type->id)
//                    ->count();
//
//                foreach ($data['object_classes'] as $object_class) {
//
//                    $data['records'][$type->name][$object_class->name][$month] = Ticket101::whereYear('created_at', $year)
//                        ->where('object_classification_id', $object_class->id)
//                        ->whereMonth('created_at', $month)
//                        ->where('drill_type_id',$type->id)->count();
//
//                    $data['counts'][$type->name]['per_object'][$object_class->name] = Ticket101::whereYear('created_at', $year)
//                        ->where('drill_type_id',$type->id)
//                        ->where('object_classification_id', $object_class->id)
//                        ->count();
//                }
//            }
//        }



        return view('analytics_spiasr.index',$data);
    }
}
