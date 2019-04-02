<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\QueueStatus
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueStatus whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueueStatus whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Model | Builder
 */
class QueueStatus extends Model
{
    public $table = 'queue_statuses';

    protected $guarded = ['id'];
}
