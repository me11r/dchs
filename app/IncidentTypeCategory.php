<?php

namespace App;

use App\Models\IncidentType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\IncidentTypeCategory
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\IncidentType[] $incidents
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentTypeCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentTypeCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentTypeCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentTypeCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IncidentTypeCategory extends Model
{
    protected $fillable = [
        'name'
    ];

    public function incidents()
    {
        return $this->hasMany(IncidentType::class, 'category_id');
    }
}
