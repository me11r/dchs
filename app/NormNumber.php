<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NormNumber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function norm_psp()
    {
        return $this->hasMany(NormPsp::class, 'norm_number_id');
    }
}
