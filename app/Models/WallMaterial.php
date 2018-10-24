<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WallMaterial
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Building[] $buildings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WallMaterial name($title)
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WallMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WallMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WallMaterial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WallMaterial whereUpdatedAt($value)
 */
class WallMaterial extends Model
{
    protected $fillable = [
        'name',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class, 'wall_material_id');
    }

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}
