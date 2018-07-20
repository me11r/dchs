<?php

namespace App\Models\Card112;

use Illuminate\Database\Eloquent\Model;

class Card112ServiceReaction extends Model
{
    public $table = 'card_112_service_reactions';

    public $fillable = [
        'card112_id',
        'service_type_id',
        'message_time',
        'name',
        'departure_time',
        'arrival_time'
    ];
}
