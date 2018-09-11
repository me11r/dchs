<?php

namespace App\Models;

use App\FireDepartment;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hydrant
 *
 * @property int $id
 * @property string $address
 * @property string $specification
 * @property int $fire_department_id
 * @property float $lat
 * @property float $long
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FireDepartment $fireDepartment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereSpecification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $number
 * @property int|null $active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hydrant whereNumber($value)
 */
class Hydrant extends Model
{
    public $table = 'hydrants';

    public $fillable = ['address', 'specification', 'fire_department_id', 'lat', 'long', 'number', 'active', 'correction_date', 'operator_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fireDepartment()
    {
        return $this->hasOne(FireDepartment::class, 'id', 'fire_department_id');
    }
}
