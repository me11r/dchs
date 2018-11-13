<?php

namespace App;

use App\Models\Ticket101\Ticket101OtherRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RideType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function ticket_101_others()
    {
        return $this->hasMany(Ticket101OtherRecord::class, '');
    }
}
