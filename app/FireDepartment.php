<?php


namespace App;


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
 */
class FireDepartment extends Model
{
    use SoftDeletes;
    protected $table = 'fire_departments';
    protected $guarded = ['id'];

    public function getNameAttribute()
    {
        return $this->attributes['title'];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['title'] = $value;
    }
}
