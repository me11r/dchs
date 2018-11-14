<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DistrictManagerPhone
 *
 * @property int $id
 * @property string $phone
 * @property int $district_manager_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\DistrictManager $manager
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManagerPhone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManagerPhone whereDistrictManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManagerPhone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManagerPhone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManagerPhone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DistrictManagerPhone extends Model
{
    protected $fillable = [
        'phone',
        'district_manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(DistrictManager::class, 'district_manager_id');
    }
}
