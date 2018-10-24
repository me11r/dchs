<?php

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Dictionary\BurntObject
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\BurntObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\BurntObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\BurntObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\BurntObject whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\BurntObject name($title)
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\BurntObject onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\BurntObject whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\BurntObject withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\BurntObject withoutTrashed()
 */
class BurntObject extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'dict_burn_object';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}
