<?php

namespace App\Models\Card112;

use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Card112\Card112ServiceReaction
 *
 * @property int $id
 * @property int $card112_id
 * @property int $service_type_id
 * @property string $message_time
 * @property string $name
 * @property string $departure_time
 * @property string $arrival_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereArrivalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereCard112Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereMessageTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereServiceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112ServiceReaction whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Card112\Card112 $card112
 * @property-read \App\Models\ServiceType $service_type
 */
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

    public function card112()
    {
        return $this->belongsTo(Card112::class, 'card112_id');
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }
}
