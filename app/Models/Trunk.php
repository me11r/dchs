<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Trunk
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Trunk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Trunk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Trunk whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Trunk whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Trunk onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Trunk whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Trunk withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Trunk withoutTrashed()
 */
class Trunk extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $table = 'dict_trunk';

    public $fillable = ['name'];
}
