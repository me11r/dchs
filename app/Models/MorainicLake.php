<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MorainicLake
 *
 * @property int $id
 * @property string $name
 * @property string|null $altitude
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MorainicLakeSummary[] $summary
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLake whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLake whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLake whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLake whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLake whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MorainicLake extends Model
{
    protected $fillable = [
        'name',
        'altitude',
    ];

    public function summary()
    {
        return $this->hasMany(MorainicLakeSummary::class, 'morainic_lake_id');
    }

    public function oneSummary($date)
    {
        return $this->summary()->where('date', $date)->first();
    }

}
