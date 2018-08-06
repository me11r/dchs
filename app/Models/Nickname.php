<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Nickname
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nickname whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nickname whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nickname whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nickname whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Nickname extends Model
{
    public $table = 'nicknames';

    public $fillable = ['name'];
}
