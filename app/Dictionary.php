<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:30
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Dictionary
 *
 * @property int $id
 * @property string $table
 * @property string $title
 * @property string $model
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary whereTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Dictionary extends Model
{
    protected $table = 'dictionaries';
    protected $guarded = ['id'];
    protected $fillable = ['name'];
}