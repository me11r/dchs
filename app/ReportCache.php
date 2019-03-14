<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportCache extends Model
{
    protected $fillable = [
        'name',
        'data',
    ];

    public function scopeGetData($q, $name)
    {
        $data = $q->name($name)->first();
        $result = $data ? $data->data_decoded : null;
        return $result;
    }

    public function scopeName($q, $s)
    {
        return $q->where('name', $s);
    }

    public function getDataDecodedAttribute()
    {
        return $this->data ? json_decode($this->data, true) : null;
    }
}
