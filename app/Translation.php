<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends \Barryvdh\TranslationManager\Models\Translation
{
    protected $fillable = [
        'status',
        'locale',
        'group',
        'key',
        'value',
    ];



    public function scopeGetByLocale($q, $search)
    {
        return $q->where('locale', $search);
    }

    public function scopeGetByKey($q, $search)
    {
        return $q->where('key', $search);
    }
}
