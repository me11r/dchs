<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomQueue extends Model
{
    protected $fillable = [
        'name',
        'options',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeGetOptions($q, $name = null)
    {
        $data = $name ? $q->name($name)->first() : $this;
        return $data ? $data->options_decoded : null;
    }

    public function scopeName($q, $s)
    {
        return $q->where('name', $s);
    }

    public function getOptionsDecodedAttribute()
    {
        return $this->options ? json_decode($this->options, true) : null;
    }
}
