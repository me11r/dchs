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
