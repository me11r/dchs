<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DictionaryCategory extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
    ];

    public function dictionaries()
    {
        return $this->hasMany(Dictionary::class, 'dictionary_category_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
