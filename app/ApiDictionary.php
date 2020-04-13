<?php

namespace App;

use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Model;

class ApiDictionary extends Model
{
    protected $fillable = [
        'name',
        'service_type_id',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    /*getByServiceTypeByName*/
    public function scopeGetByServiceTypeByName($q, $search)
    {
        return $q->whereHas('service_type', function ($qq) use ($search) {
            $qq->where('name', $search);
        });
    }
}
