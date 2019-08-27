<?php


namespace App;


use App\Dictionary\CityArea;
use App\Models\FireDepartmentResult;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\FireDepartment
 *
 * @property int $id
 * @property string $title
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\FireDepartment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FireDepartment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\FireDepartment withoutTrashed()
 * @mixin \Eloquent | \Illuminate\Database\Eloquent\Builder
 * @property mixed $name
 * @property string|null $address
 * @property int|null $city_area_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereCityAreaId($value)
 * @property-read \App\Dictionary\CityArea|null $city_area
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FireDepartmentResult[] $results
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Schedule[] $schedules
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment title($title)
 * @property int|null $recommend
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment recommend($search = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FireDepartment whereRecommend($value)
 */
class FireDepartment extends Model
{
    use SoftDeletes;
    protected $table = 'fire_departments';
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'title_old',
        'sort_order',
        'address',
        'recommend',
        'city_area_id',
        'goes_in_formation_report',
    ];

    public function getNameAttribute()
    {
        return $this->attributes['title'];
    }

    //title_with_old
    public function getTitleWithOldAttribute()
    {
        $old = $this->attributes['old_title'];
        $new = $this->attributes['title'];

        $old = ($old !== null && $old !== $new) ? " ({$old})" : '';

        return "{$this->attributes['title']}$old";
    }

    public function setNameAttribute($value)
    {
        $this->attributes['title'] = $value;
    }

    public function scopeTitle($q, $title)
    {
        return $q->where('title', $title);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'fire_department_main_id');
    }

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }

    public function results()
    {
        return $this->hasMany(FireDepartmentResult::class, 'fire_department_id');
    }

    public function scopeRecommend($q, $search = true)
    {
        return $q->where('recommend', $search);
    }

    public function scopeUsingInFormationReport($q, $search = true)
    {
        return $q->where('goes_in_formation_report', $search);
    }

    public function scopeSortByCustomOrder($q, $direction = 'asc')
    {
        return $q->orderBy('sort_order', $direction);
    }
}
