<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NormPspDepartment extends Model
{
    protected $fillable = [
        'name',
        'norm_id',
    ];

    public function norm()
    {
        return $this->belongsTo(NormPsp::class, 'norm_id');
    }
}
