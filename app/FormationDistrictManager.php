<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationDistrictManager
 *
 * @property int $id
 * @property string $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FormationDistrictManagerItem[] $items
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManager date($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManager whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManager whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationDistrictManager extends Model
{
    protected $fillable = [
        'date'
    ];

    public function scopeDate($q, $search)
    {
        return $q->where('date', $search);
    }

    public function items()
    {
        return $this->hasMany(FormationDistrictManagerItem::class, 'report_id');
    }

    public function items_active()
    {
        return $this->hasMany(FormationDistrictManagerItem::class, 'report_id')
            ->whereNull('inactive_type');
    }

    public function items_inactive()
    {
        return $this->hasMany(FormationDistrictManagerItem::class, 'report_id')
            ->whereNotNull('inactive_type');
    }
}
