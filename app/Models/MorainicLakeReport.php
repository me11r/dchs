<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MorainicLakeReport
 *
 * @property int $id
 * @property string|null $note
 * @property string|null $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeReport whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeReport whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MorainicLakeReport extends Model
{
    protected $fillable = [
        'date',
        'note',
    ];
}
