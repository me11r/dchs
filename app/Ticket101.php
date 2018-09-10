<?php


namespace App;


use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\FireObject;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\Street;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\Models\FireDepartmentResult;
use App\Models\OperationalPlan;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\WallMaterial;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Ticket101
 *
 * @property int $id
 * @property string|null $location
 * @property int|null $city_area_id
 * @property int|null $crossroad_1_id
 * @property int|null $crossroad_2_id
 * @property int|null $fire_object_id
 * @property int|null $storey_count
 * @property int|null $floor
 * @property string|null $building_description
 * @property string|null $caller_phone
 * @property string|null $caller_name
 * @property string|null $call_time
 * @property string|null $notify_100_time
 * @property string|null $notify_101_time
 * @property string|null $notify_102_time
 * @property string|null $notify_103_time
 * @property string|null $notify_104_time
 * @property string|null $notify_b01_time
 * @property string|null $notify_b04_time
 * @property string|null $next_notify_time
 * @property string|null $call_112_time
 * @property string|null $name_112_recv
 * @property string|null $arrival_112
 * @property string|null $call_102_time
 * @property string|null $name_102_recv
 * @property string|null $arrival_102
 * @property string|null $call_103_time
 * @property string|null $name_103_recv
 * @property string|null $arrival_103
 * @property string|null $call_104_time
 * @property string|null $name_104_recv
 * @property string|null $arrival_104
 * @property string|null $call_electro_time
 * @property string|null $name_electro_recv
 * @property string|null $arrival_electro
 * @property string|null $call_water_time
 * @property string|null $name_water_recv
 * @property string|null $arrival_water
 * @property string|null $call_smk_time
 * @property string|null $name_smk_recv
 * @property string|null $arrival_smk
 * @property string|null $ph_1_ot
 * @property string|null $ph_1_out_time
 * @property string|null $ph_1_arrive_time
 * @property string|null $ph_1_loc_time
 * @property string|null $ph_1_liqv_time
 * @property string|null $ph_1_ret_time
 * @property string|null $ph_2_ot
 * @property string|null $ph_2_out_time
 * @property string|null $ph_2_arrive_time
 * @property string|null $ph_2_loc_time
 * @property string|null $ph_2_liqv_time
 * @property string|null $ph_2_ret_time
 * @property string|null $ph_3_ot
 * @property string|null $ph_3_out_time
 * @property string|null $ph_3_arrive_time
 * @property string|null $ph_3_loc_time
 * @property string|null $ph_3_liqv_time
 * @property string|null $ph_3_ret_time
 * @property string|null $ph_4_ot
 * @property string|null $ph_4_out_time
 * @property string|null $ph_4_arrive_time
 * @property string|null $ph_4_loc_time
 * @property string|null $ph_4_liqv_time
 * @property string|null $ph_4_ret_time
 * @property string|null $ph_5_ot
 * @property string|null $ph_5_out_time
 * @property string|null $ph_5_arrive_time
 * @property string|null $ph_5_loc_time
 * @property string|null $ph_5_liqv_time
 * @property string|null $ph_5_ret_time
 * @property string|null $ph_6_ot
 * @property string|null $ph_6_out_time
 * @property string|null $ph_6_arrive_time
 * @property string|null $ph_6_loc_time
 * @property string|null $ph_6_liqv_time
 * @property string|null $ph_6_ret_time
 * @property string|null $ph_7_ot
 * @property string|null $ph_7_out_time
 * @property string|null $ph_7_arrive_time
 * @property string|null $ph_7_loc_time
 * @property string|null $ph_7_liqv_time
 * @property string|null $ph_7_ret_time
 * @property string|null $ph_8_ot
 * @property string|null $ph_8_out_time
 * @property string|null $ph_8_arrive_time
 * @property string|null $ph_8_loc_time
 * @property string|null $ph_8_liqv_time
 * @property string|null $ph_8_ret_time
 * @property string|null $ph_9_ot
 * @property string|null $ph_9_out_time
 * @property string|null $ph_9_arrive_time
 * @property string|null $ph_9_loc_time
 * @property string|null $ph_9_liqv_time
 * @property string|null $ph_9_ret_time
 * @property string|null $ph_10_ot
 * @property string|null $ph_10_out_time
 * @property string|null $ph_10_arrive_time
 * @property string|null $ph_10_loc_time
 * @property string|null $ph_10_liqv_time
 * @property string|null $ph_10_ret_time
 * @property string|null $ph_11_ot
 * @property string|null $ph_11_out_time
 * @property string|null $ph_11_arrive_time
 * @property string|null $ph_11_loc_time
 * @property string|null $ph_11_liqv_time
 * @property string|null $ph_11_ret_time
 * @property string|null $ph_12_ot
 * @property string|null $ph_12_out_time
 * @property string|null $ph_12_arrive_time
 * @property string|null $ph_12_loc_time
 * @property string|null $ph_12_liqv_time
 * @property string|null $ph_12_ret_time
 * @property string|null $ph_13_ot
 * @property string|null $ph_13_out_time
 * @property string|null $ph_13_arrive_time
 * @property string|null $ph_13_loc_time
 * @property string|null $ph_13_liqv_time
 * @property string|null $ph_13_ret_time
 * @property string|null $ph_14_ot
 * @property string|null $ph_14_out_time
 * @property string|null $ph_14_arrive_time
 * @property string|null $ph_14_loc_time
 * @property string|null $ph_14_liqv_time
 * @property string|null $ph_14_ret_time
 * @property string|null $ph_15_ot
 * @property string|null $ph_15_out_time
 * @property string|null $ph_15_arrive_time
 * @property string|null $ph_15_loc_time
 * @property string|null $ph_15_liqv_time
 * @property string|null $ph_15_ret_time
 * @property string|null $ph_16_ot
 * @property string|null $ph_16_out_time
 * @property string|null $ph_16_arrive_time
 * @property string|null $ph_16_loc_time
 * @property string|null $ph_16_liqv_time
 * @property string|null $ph_16_ret_time
 * @property string|null $ph_17_ot
 * @property string|null $ph_17_out_time
 * @property string|null $ph_17_arrive_time
 * @property string|null $ph_17_loc_time
 * @property string|null $ph_17_liqv_time
 * @property string|null $ph_17_ret_time
 * @property string|null $update_1_time
 * @property string|null $update_1_info
 * @property string|null $update_2_time
 * @property string|null $update_2_info
 * @property string|null $update_3_time
 * @property string|null $update_3_info
 * @property string|null $update_4_time
 * @property string|null $update_4_info
 * @property string|null $update_5_time
 * @property string|null $update_5_info
 * @property string|null $add_info
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket101 onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereAddInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival102($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival103($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival104($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival112($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalElectro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalSmk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereBuildingDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall102Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall103Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall104Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall112Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallElectroTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallSmkTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallWaterTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCrossroad1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCrossroad2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFireObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName102Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName103Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName104Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName112Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameElectroRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameSmkRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameWaterRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNextNotifyTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify100Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify101Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify102Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify103Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify104Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotifyB01Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotifyB04Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9ArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9LiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9LocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9Ot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9OutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9RetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereStoreyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate1Info($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate1Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate2Info($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate2Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate3Info($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate3Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate4Info($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate4Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate5Info($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdate5Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket101 withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket101 withoutTrashed()
 * @mixin \Eloquent
 * @property int $people_in_danger
 * @property int|null $fire_level_id
 * @property int $ph_1_dispatched
 * @property int|null $ph_1_dispatch_id
 * @property int $ph_2_dispatched
 * @property int|null $ph_2_dispatch_id
 * @property int $ph_3_dispatched
 * @property int|null $ph_3_dispatch_id
 * @property int $ph_4_dispatched
 * @property int|null $ph_4_dispatch_id
 * @property int $ph_5_dispatched
 * @property int|null $ph_5_dispatch_id
 * @property int $ph_6_dispatched
 * @property int|null $ph_6_dispatch_id
 * @property int $ph_7_dispatched
 * @property int|null $ph_7_dispatch_id
 * @property int $ph_8_dispatched
 * @property int|null $ph_8_dispatch_id
 * @property int $ph_9_dispatched
 * @property int|null $ph_9_dispatch_id
 * @property int $ph_10_dispatched
 * @property int|null $ph_10_dispatch_id
 * @property int $ph_11_dispatched
 * @property int|null $ph_11_dispatch_id
 * @property int $ph_12_dispatched
 * @property int|null $ph_12_dispatch_id
 * @property int $ph_13_dispatched
 * @property int|null $ph_13_dispatch_id
 * @property int $ph_14_dispatched
 * @property int|null $ph_14_dispatch_id
 * @property int $ph_15_dispatched
 * @property int|null $ph_15_dispatch_id
 * @property int $ph_16_dispatched
 * @property int|null $ph_16_dispatch_id
 * @property int $ph_17_dispatched
 * @property int|null $ph_17_dispatch_id
 * @property-read \App\Dictionary\CityArea $city_area
 * @property-read \App\Dictionary\Street $crossroad_1
 * @property-read \App\Dictionary\Street $crossroad_2
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFireLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePeopleInDanger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh10Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh11Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh12Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh13Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh14Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh15Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh16Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh17Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh1Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh2Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh3Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh4Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh5Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh6Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh7Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh8Dispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9DispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePh9Dispatched($value)
 * @property-read \App\Dictionary\FireLevel $fire_level
 * @property-read \App\Dictionary\FireObject $fire_object
 * @property string|null $detailed_address
 * @property int|null $burn_object_id
 * @property int|null $trip_result_id
 * @property int|null $liquidation_method_id
 * @property int|null $result_fire_level_id
 * @property int $vu_found
 * @property int $animal_death
 * @property int $car_crash
 * @property int|null $rescued_count
 * @property int|null $evac_count
 * @property int|null $co2_poisoned_count
 * @property int|null $gpt_burns_count
 * @property int|null $people_death_count
 * @property int|null $children_death_count
 * @property int|null $hospitalized_count
 * @property string|null $more_info
 * @property int $hydrant_found
 * @property float|null $distance
 * @property string|null $owner
 * @property string|null $explanation
 * @property int|null $ch4_poisoned_count
 * @property string|null $register_time
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FireDepartment[] $departments
 * @property-read \App\Dictionary\LiquidationMethod $liquidation_method
 * @property-read \App\Dictionary\TripResult $trip_result
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereAnimalDeath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereBurnObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCarCrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCh4PoisonedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereChildrenDeathCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCo2PoisonedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereDetailedAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereEvacCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereGptBurnsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereHospitalizedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereHydrantFound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereLiquidationMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereMoreInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePeopleDeathCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereRegisterTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereRescuedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereResultFireLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereTripResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereVuFound($value)
 * @property string $object_name
 * @property int $operational_plan_id
 * @property int $fire_department_id
 * @property-read \App\Models\OperationalPlan $fire_department
 * @property-read \App\Models\OperationalPlan $operational_plan
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereOperationalPlanId($value)
 * @property int $pre_information_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket101\Ticket101OtherRecord[] $other_records
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePreInformationId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FireDepartmentResult[] $results
 * @property-read \App\Dictionary\WaterSupplySource $water_supply_source
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 canEditTicket()
 */
class Ticket101 extends Model
{
    use SoftDeletes;
    protected $table = 'ticket101';
    protected $fillable = [];
    protected $guarded = ['id'];

    public function crossroad_1()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_1_id');
    }

    public function crossroad_2()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_2_id');
    }

    public function city_area()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }

    public function fire_level(){
        return $this->hasOne(FireLevel::class, 'id', 'fire_level_id');
    }

    public function fire_object()
    {
        return $this->hasOne(BurntObject::class, 'id', 'fire_object_id');
    }

    public function trip_result()
    {
        return $this->hasOne(TripResult::class, 'id', 'trip_result_id');
    }

    public function liquidation_method()
    {
        return $this->hasOne(LiquidationMethod::class, 'id', 'liquidation_method_id');
    }

    public function departments()
    {
        return $this->belongsToMany(FireDepartment::class, 'roadtrip_plan', 'card_id', 'department_id');
    }

    public function operational_plan()
    {
        return $this->hasOne(OperationalPlan::class, 'id', 'operational_plan_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class,'fire_department_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function other_records()
    {
        return $this->hasMany(Ticket101OtherRecord::class, 'ticket101_id', 'id');
    }

    public function results()
    {
        return $this->hasMany(FireDepartmentResult::class, 'ticket101_id');
    }

    public function water_supply_source()
    {
        return $this->belongsTo(WaterSupplySource::class, 'water_supply_source_id');
    }

    public function scopeCanEditTicket($q)
    {
        $created_date = $this->created_at;
        if($created_date == null){
            return true;
        }
        $is_yesterday = $created_date->isYesterday();
        $is_today = $created_date->isToday();
        $pass_after_9 = now()->format('H') > 9;

        if(!$is_yesterday && !$is_today){
            return false;
        }
        elseif($is_today && !$pass_after_9){
            return true;
        }
        elseif($is_today){
            return true;
        }
        elseif($is_yesterday && !$pass_after_9){
            return false;
        }

        return false;
    }

    public function wall_material()
    {
        return $this->belongsTo(WallMaterial::class, 'wall_material_id');
    }


}
