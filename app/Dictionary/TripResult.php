<?php
namespace App\Dictionary;


use App\Ticket101;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Dictionary\TripResult
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TripResult extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'dict_trip_result';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function cards101()
    {
        return $this->hasMany(Ticket101::class, 'trip_result_id');
    }

}
