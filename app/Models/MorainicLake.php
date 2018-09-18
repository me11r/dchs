<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
