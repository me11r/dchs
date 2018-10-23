<?php

namespace App;

use App\Models\Card112\Card112;
use App\Models\FireDepartmentResult;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Model;

class Ticket101ServicePlan extends Model
{
    protected $fillable = [
        'service_type_id',
        'card_id',
        'card112_id',
        'return_time',
        'is_closed',
        'is_accepted',
        'arrive_time',
        'name_accepted',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket101::class, 'card_id');
    }

    public function ticket112()
    {
        return $this->belongsTo(Card112::class, 'card112_id');
    }

    public function scopeDepartment($q, $search)
    {
        return $q->where('service_type_id', $search);
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function results()
    {
        return $this->hasMany(FireDepartmentResult::class, 'dispatch_id');
    }
}
