<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\OperDutyShift
 *
 * @property int $id
 * @property string $name
 * @property string|null $direction
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OperDutyShiftStaffItem[] $items
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\OperDutyShift onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShift whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShift whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OperDutyShift withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\OperDutyShift withoutTrashed()
 * @mixin \Eloquent
 */
class OperDutyShift extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'direction',
    ];

    public function items()
    {
        return $this->hasMany(OperDutyShiftStaffItem::class, 'shift_id');
    }
}
