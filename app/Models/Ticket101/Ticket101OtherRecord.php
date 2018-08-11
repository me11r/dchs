<?php

namespace App\Models\Ticket101;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ticket101\Ticket101OtherRecord
 *
 * @property int $id
 * @property int $ticket101_id
 * @property string $time
 * @property string $comment
 * @property int $trunk_id
 * @property int $count
 * @property float $square
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereTicket101Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereTrunkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101OtherRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ticket101OtherRecord extends Model
{
    public $table = 'ticket101_other_records';

    public $fillable = [
        'ticket101_id',
        'time',
        'comment',
        'trunk_id',
        'count',
        'square'
    ];

    public $guarded = [
        'id'
    ];
}
