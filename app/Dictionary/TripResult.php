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
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket101[] $cards101
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\TripResult onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\TripResult withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\TripResult withoutTrashed()
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

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function scopeNonFires($q)
    {
        return $q->where('name', 'like', "%Загорание мусора%")
            ->orWhere('name', 'like', "%пища на газе%")
            ->orWhere('name', 'like', "%сухост%")
            ->orWhere('name', 'like', "%КЗ эл.сетей%");
    }

}
