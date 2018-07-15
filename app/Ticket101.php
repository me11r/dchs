<?php


namespace App;


use App\Dictionary\Street;
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

}