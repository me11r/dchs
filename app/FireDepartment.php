<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @mixin \Eloquent
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