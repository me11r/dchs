<?php


namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Dictionary\LiquidationMethod
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\LiquidationMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\LiquidationMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\LiquidationMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\LiquidationMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\LiquidationMethod onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\LiquidationMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\LiquidationMethod withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\LiquidationMethod withoutTrashed()
 */
class LiquidationMethod extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'dict_liquidation_method';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

}
