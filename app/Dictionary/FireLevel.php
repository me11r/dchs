<?php


namespace App\Dictionary;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Dictionary\FireLevel
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireLevel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireLevel whereUpdatedAt($value)
 * @mixin \Eloquent | Builder
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireLevel name($title)
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\FireLevel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireLevel whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\FireLevel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\FireLevel withoutTrashed()
 */
class FireLevel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'dict_fire_level';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}
