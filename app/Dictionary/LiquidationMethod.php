<?php


namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

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
 */
class LiquidationMethod extends Model
{
    protected $table = 'dict_liquidation_method';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

}