<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Quake
 *
 * @property int $id
 * @property string $description
 * @property string $date_almaty
 * @property string $date_greenwich
 * @property string $epicenter
 * @property float $energy_class
 * @property float $mpv
 * @property string $coordinates
 * @property float $deep
 * @property string $information
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereDateAlmaty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereDateGreenwich($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereDeep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereEnergyClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereEpicenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereMpv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Quake whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Quake extends BaseModel
{
    protected $searchByDate = 'date_almaty';

    public $table = 'quakes';

    public $fillable = [
        'description',
        'date_almaty',
        'date_greenwich',
        'epicenter',
        'energy_class',
        'mpv',
        'deep',
        'coordinates',
        'information'
    ];

    public function scopeDailyRecords($q, $from = null, $to = null)
    {
        $from = $from ? $from : today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = $to ? $to : today()->addHours(7)->format('Y-m-d H:i:s');

        return $q->whereBetween('created_at', [$from, $to]);
    }

    public function getTotalInfoAttribute()
    {
        $result = '';
        $result .= "Эпицентр землетрясения: {$this->epicenter}; ";
        $result .= "магнитуда MPV: {$this->mpv}; ";
        $result .= "глубина: {$this->deep}; ";
        $result .= "сведения об ощутимости: {$this->information}; ";
        $result .= "энергетический класс землетрясения: {$this->energy_class}; ";
        $result .= "координаты эпицентра: {$this->coordinates}; ";

        return $result;
    }
}
