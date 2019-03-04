<?php


namespace App;


use App\Models\FireDepartmentResult;
use Illuminate\Database\Eloquent\Model;

/**
 * App\RoadtripPlan
 *
 * @property int $id
 * @property int $department_id
 * @property int $card_id
 * @property string|null $return_time
 * @property int $is_closed
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereIsClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereReturnTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\FireDepartment $department
 * @property-read \App\Ticket101 $ticket
 * @property int $is_accepted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripPlan whereIsAccepted($value)
 * @property-read \App\Models\FireDepartmentResult $result
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FireDepartmentResult[] $results
 */
class RoadtripPlan extends Model
{
    protected $table = 'roadtrip_plan';
    protected $guarded = ['id'];

    protected $fillable = [
        'department_id',
        'card_id',
        'card101_other_id',
        'return_time',
        'is_closed',
        'is_accepted',
        'printed',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket101::class, 'card_id');
    }

    public function ticket101_other()
    {
        return $this->belongsTo(Ticket101Other::class, 'card101_other_id');
    }

    public function department()
    {
        return $this->belongsTo(FireDepartment::class);
    }

    public function results()
    {
        return $this->hasMany(FireDepartmentResult::class, 'dispatch_id');
    }
}