<?php

namespace App;

use App\Models\Trunk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrunkType extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name'
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function event_info_arrived()
    {
        return $this->hasMany(EventInfoArrived::class, 'trunk_type_id');
    }
}
