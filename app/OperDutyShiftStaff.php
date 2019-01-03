<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OperDutyShiftStaff
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OperDutyShiftStaffItem[] $shifts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaff whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaff whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OperDutyShiftStaff extends Model
{
    protected $fillable = [
        'name',
        'guard_number_id',
    ];

    public function shifts()
    {
        return $this->hasMany(OperDutyShiftStaffItem::class, 'staff_id');
    }
}
