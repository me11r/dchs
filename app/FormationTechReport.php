<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 12.07.2018
 * Time: 21:13
 */

namespace App;


use App\Models\FormationTechItem;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationTechReport
 *
 * @property int $id
 * @property int $form_id
 * @property int $dept_id
 * @property string $field_0
 * @property string $field_1
 * @property string $field_3_0
 * @property string $field_3_1
 * @property string $field_4_1_0
 * @property string $field_4_1_1
 * @property string $field_4_2_0
 * @property string $field_4_2_1
 * @property string $field_4_3_0
 * @property string $field_4_3_1
 * @property string $field_5_1_0
 * @property string $field_5_1_1
 * @property string $field_5_1_2
 * @property string $field_5_1_3
 * @property string $field_5_0
 * @property string $field_5_1
 * @property string $field_5_2
 * @property string $field_5_3
 * @property string $field_5_4
 * @property string $field_5_5
 * @property string $field_5_6
 * @property string $field_5_7
 * @property string $field_5_8
 * @property string $field_5_9
 * @property string $field_5_10
 * @property string $field_5_11
 * @property string $field_5_12
 * @property string $field_2
 * @property string $field_7_1_0
 * @property string $field_7_1_1
 * @property string $field_7_0
 * @property string $field_8_0
 * @property string $field_8_1
 * @property string $field_9_0
 * @property string $field_9_1
 * @property string $field_3
 * @property string $field_4
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereDeptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField30($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField31($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField410($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField411($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField420($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField421($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField430($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField431($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField50($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField51($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField510($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField511($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField512($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField513($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField52($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField53($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField54($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField55($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField56($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField57($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField58($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField59($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField70($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField710($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField711($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField80($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField81($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField90($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField91($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport todayRecords()
 * @property string|null $device
 * @property string|null $motor_water_pump
 * @property string|null $motor_mud_pump
 * @property string|null $firehose_125
 * @property string|null $firehose_75
 * @property string|null $firehose_77
 * @property string|null $firehose_51
 * @property string|null $barrel_stationary
 * @property string|null $barrel_portable
 * @property string|null $pgs_600
 * @property string|null $purga
 * @property string|null $radio_station_portable
 * @property string|null $flashlight
 * @property string|null $searchlight
 * @property string|null $l1
 * @property string|null $tok
 * @property string|null $knapsack_devices
 * @property string|null $shovel
 * @property string|null $flapper
 * @property string|null $life_rope
 * @property string|null $foamer
 * @property string|null $foamer_in_stock
 * @property string|null $damaged_hydrant_street
 * @property string|null $damaged_hydrant_object
 * @property string|null $damaged_pv
 * @property string|null $active_gasoline
 * @property string|null $active_diesel
 * @property string|null $reserved_gasoline
 * @property string|null $reserved_diesel
 * @property string|null $generator
 * @property int|null $head_guard_id
 * @property string|null $iup
 * @property string|null $girs
 * @property string|null $exhauster
 * @property int|null $asv
 * @property int|null $dask
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormationTechItem[] $formation_tech_items
 * @property-read \App\Models\Staff|null $head_guard
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormationTechItem[] $items
 * @property-read \App\FormationReport $report
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereActiveDiesel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereActiveGasoline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereBarrelPortable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereBarrelStationary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereDamagedHydrantObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereDamagedHydrantStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereDamagedPv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereExhauster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFirehose125($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFirehose51($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFirehose75($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFirehose77($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFlapper($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFlashlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFoamer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFoamerInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereGenerator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereGirs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereHeadGuardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereIup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereKnapsackDevices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereL1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereLifeRope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereMotorMudPump($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereMotorWaterPump($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport wherePgs600($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport wherePurga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereRadioStationPortable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereReservedDiesel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereReservedGasoline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereSearchlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereShovel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereTok($value)
 * @property-read \App\FireDepartment $department
 */
class FormationTechReport extends Model
{
    protected $table = 'formation_tech_report';
    protected $guarded = ['id'];
    protected $appends = [
        'foamer_in_stock_reserved',
        'dvr',
    ];

    protected $fillable = [
        'form_id',
        'dept_id',
        'device',
        'motor_water_pump',
        'motor_mud_pump',
        'firehose_125',
        'firehose_75',
        'firehose_77',
        'firehose_51',
        'barrel_stationary',
        'barrel_portable',
        'pgs_600',
        'purga',
        'radio_station_portable',
        'flashlight',
        'searchlight',
        'tok',
        'l1',
        'knapsack_devices',
        'shovel',
        'flapper',
        'life_rope',
        'foamer',
        'foamer_in_stock',
        'foamer_reserved',
        'damaged_hydrant_street',
        'damaged_hydrant_object',
        'damaged_pv',
        'active_gasoline',
        'active_diesel',
        'reserved_gasoline',
        'reserved_diesel',
        'generator',

        'exhauster',
        'girs',
        'iup',
        'head_guard_id',
        'asv',
        'dask'
    ];

    public function getAsvDaskAttribute()
    {
        return "$this->asv/$this->dask";
    }

    //attribute: dvr
    public function getDvrAttribute()
    {
        $action = $this->formation_tech_items()
            ->status('action')
            ->dvr(true)
            ->count();

        $reserve = $this->formation_tech_items()
            ->whereIn('status',['reserve','repair'])
            ->dvr(true)
            ->count() + $this->other_dvrs()->status(true)->count();

        $broken = $this->formation_tech_items()
            ->dvr(false)
            ->count() + $this->other_dvrs()->status(false)->count();

        return "{$action}/{$reserve}/{$broken}";
    }

    public function getFoamerInStockReservedAttribute()
    {
        return "$this->foamer_reserved/$this->foamer_in_stock";
    }

    public function scopeTodayRecords($q)
    {
        return $q->whereDate('created_at', date('Y-m-d'));
    }

    public function formation_tech_items()
    {
        return $this->hasMany(FormationTechItem::class, 'formation_tech_report_id');
    }

    public function items()
    {
        return $this->hasMany(FormationTechItem::class, 'formation_tech_report_id');
    }

    public function head_guard()
    {
        return $this->belongsTo(Staff::class, 'head_guard_id')->withTrashed();
    }

    public function report()
    {
        return $this->belongsTo(FormationReport::class, 'form_id');
    }

    public function department()
    {
        return $this->belongsTo(FireDepartment::class, 'dept_id');
    }

    public function other_dvrs()
    {
        return $this->hasMany(Dvr::class, 'formation_tech_report_id');
    }
}
