<?php

namespace App;

use App\Models\SpecialPlan;
use Illuminate\Database\Eloquent\Model;

class AdditionalPlan101 extends Model
{
    protected $fillable = [
        'ticket101_id',
        'special_plan_id',
        'operational_card_id',
    ];

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    public function special_plan()
    {
        return $this->belongsTo(SpecialPlan::class, 'special_plan_id');
    }

    public function operational_card()
    {
        return $this->belongsTo(OperationalCard::class, 'operational_card_id');
    }
}
