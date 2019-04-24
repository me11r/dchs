<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Ticket101\Ticket101OtherRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\RideType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket101\Ticket101OtherRecord[] $ticket_101_others
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\RideType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RideType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RideType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RideType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RideType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RideType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RideType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\RideType withoutTrashed()
 * @mixin \Eloquent
 */
class RideType extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function ticket_101_others()
    {
        return $this->hasMany(Ticket101Other::class, 'ride_type_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
