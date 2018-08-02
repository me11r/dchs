<?php

namespace App\Models;

use App\FireDepartment;
use Illuminate\Database\Eloquent\Model;

class Hydrant extends Model
{
    public $table = 'hydrants';

    public $fillable = ['address', 'specification', 'fire_department_id', 'lat', 'long'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fireDepartment()
    {
        return $this->hasOne(FireDepartment::class, 'id', 'fire_department_id');
    }
}
