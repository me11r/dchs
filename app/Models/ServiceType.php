<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ServiceType
 *
 * @property int $id
 * @property string $name
 * @property int $info
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereName($value)
 * @mixin \Eloquent
 * @property int|null $head_user_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\User|null $headUser
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ServiceType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereHeadUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ServiceType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ServiceType withoutTrashed()
 */
class ServiceType extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $table = 'service_types';

    public $timestamps = false;

    public $fillable = ['name', 'head_user_id'];

    public function headUser()
    {
        return $this->belongsTo(User::class, 'head_user_id');
    }
}
