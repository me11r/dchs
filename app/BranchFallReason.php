<?php

namespace App;

use App\Models\Card112\Card112;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchFallReason extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function cards112()
    {
        return $this->hasMany(Card112::class, 'branch_fall_reason_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
