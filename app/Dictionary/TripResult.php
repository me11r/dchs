<?php
namespace App\Dictionary;


use App\Models\BaseModel;
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
class TripResult extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'dict_trip_result';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'show_in_daily_report101',
        'show_in_daily_report112',
        'emergency_code',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
        'show_in_daily_report101' => 'Участвует в ежедневном отчете 101',
        'show_in_daily_report112' => 'Участвует в ежедневном отчете 112',
        'emergency_code' => 'Код ЧС',
    ];

    public function cards101()
    {
        return $this->hasMany(Ticket101::class, 'trip_result_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function scopeDailyReportConst($q)
    {
        return $q->where('show_in_daily_report101', true);
    }

    public function scopeDailyReport112Const($q)
    {
        return $q->where('show_in_daily_report112', true);
    }

    public function scopeNonFires($q)
    {
        return $q->where('name', 'like', "%Загорание мусора%")
            ->orWhere('name', 'like', "%пища на газе%")
            ->orWhere('name', 'like', "%сухост%")
            ->orWhere('name', 'like', "%КЗ эл.сетей%");
    }

}
