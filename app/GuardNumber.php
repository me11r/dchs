<?php

namespace App;

use App\Models\FormationPersonsItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuardNumber extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name'
    ];

    public function formation_persons_item()
    {
        return $this->hasMany(FormationPersonsItem::class, 'guard_number_id');
    }
}
