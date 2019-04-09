<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:30
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Dictionary
 *
 * @property int $id
 * @property string $table
 * @property string $title
 * @property string $url
 * @property integer $sort_order
 * @property integer $dictionary_category_id
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
    use SoftDeletes;

    protected $table = 'dictionaries';
    protected $guarded = ['id'];
    protected $fillable = [
        'table',
        'title',
        'url',
        'dictionary_category_id',
        'sort_order',
        'model'
    ];

    public function scopeName($q, $search)
    {
        return $q->where('title', $search)
            ->orWhere('table', $search)
            ->orWhere('model', $search)
            ;
    }

    public function category()
    {
        return $this->belongsTo(DictionaryCategory::class,'dictionary_category_id');
    }

}
