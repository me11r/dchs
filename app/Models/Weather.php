<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weather
 *
 * @property int $id
 * @property string $date
 * @property string $file
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Weather extends Model
{
    public $table = 'weather';

    public $fillable = ['date', 'file'];
}
