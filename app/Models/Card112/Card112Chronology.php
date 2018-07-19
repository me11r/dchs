<?php

namespace App\Models\Card112;

use Illuminate\Database\Eloquent\Model;

class Card112Chronology extends Model
{
    public $table = 'cards_112_chronologies';

    public $fillable = [
        'cards_112_id',
        'time',
        'comment',
        'additional_comment'
    ];
}
