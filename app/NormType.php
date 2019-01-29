<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NormType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function norm_psp()
    {
        return $this->hasMany(NormPsp::class, 'norm_type_id');
    }
}
