<?php

namespace App\Models;

use App\IncidentTypeCategory;
use App\Ticket101;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\IncidentType
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IncidentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IncidentType whereName($value)
 * @mixin \Eloquent
 * @property int|null $category_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket101[] $cards101
 * @property-read \App\IncidentTypeCategory|null $category
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IncidentType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IncidentType whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IncidentType whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IncidentType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\IncidentType withoutTrashed()
 */
class IncidentType extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $table = 'incident_types';

    public $timestamps = false;

    public $fillable = [
        'name',
        'category_id'
    ];

    /*todo связь устарела, возможно, более не актуальна*/
    public function cards101()
    {
        return $this->hasMany(Ticket101::class, 'pre_information_id');
    }

    public function category()
    {
        return $this->belongsTo(IncidentTypeCategory::class, 'category_id');
    }
}
