<?php


namespace App\Dictionary;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
 */
class FireLevel extends Model
{
    protected $table = 'dict_fire_level';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}
