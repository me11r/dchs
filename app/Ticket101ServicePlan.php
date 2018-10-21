<?php

namespace App;

use App\Models\FireDepartmentResult;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Ticket101ServicePlan
 *
 * @property int $id
 * @property int|null $service_type_id
 * @property int|null $card_id
 * @property string|null $return_time
 * @property string|null $arrive_time
 * @property int $is_closed
 * @property int $is_accepted
 * @property string|null $name_accepted
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FireDepartmentResult[] $results
 * @property-read \App\Models\ServiceType|null $service_type
 * @property-read \App\Ticket101|null $ticket
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan department($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereIsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereIsClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereNameAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereReturnTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereServiceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101ServicePlan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ticket101ServicePlan extends Model
{
    protected $fillable = [
        'service_type_id',
        'card_id',
        'return_time',
        'is_closed',
        'is_accepted',
        'arrive_time',
        'name_accepted',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket101::class, 'card_id');
    }

    public function scopeDepartment($q, $search)
    {
        return $q->where('service_type_id', $search);
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function results()
    {
        return $this->hasMany(FireDepartmentResult::class, 'dispatch_id');
    }
}
