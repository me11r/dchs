<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function scopeSkipNullValue($q, $field, $search)
    {
        return $search ? $q->where($field, $search) : $q;
    }
}
