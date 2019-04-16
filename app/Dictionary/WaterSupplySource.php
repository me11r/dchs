<?php

namespace App\Dictionary;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Dictionary\WaterSupplySource
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\WaterSupplySource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\WaterSupplySource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\WaterSupplySource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\WaterSupplySource whereUpdatedAt($value)
 */
class WaterSupplySource extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];
}
