<?php

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

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
 */
class BurntObject extends Model
{
    protected $table = 'dict_burn_object';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}