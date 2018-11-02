<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class StaffOd extends Model
{
    protected $appends = ['unique'];

    public function getUniqueAttribute()
    {
        return $this->unique();
    }

    public function unique()
    {
        return $this->id . $this->created_at->timestamp;
    }
}
