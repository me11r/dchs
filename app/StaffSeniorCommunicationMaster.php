<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaffSeniorCommunicationMaster
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffSeniorCommunicationMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffSeniorCommunicationMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffSeniorCommunicationMaster whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffSeniorCommunicationMaster whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffSeniorCommunicationMaster extends StaffOd
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'guard_number_id',
    ];

    public function guard_number()
    {
        return $this->belongsTo(GuardNumber::class, 'guard_number_id');
    }
}
