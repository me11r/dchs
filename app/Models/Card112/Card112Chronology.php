<?php

namespace App\Models\Card112;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Card112\Card112Chronology
 *
 * @property int $id
 * @property int $card112_id
 * @property string $time
 * @property string $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112Chronology whereCard112Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112Chronology whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112Chronology whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112Chronology whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112Chronology whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112Chronology whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Card112Chronology extends Model
{
    public $table = 'cards_112_chronologies';

    public $fillable = [
        'card112_id',
        'time',
        'comment'
    ];

    public $guarded = [
        'id'
    ];
}
