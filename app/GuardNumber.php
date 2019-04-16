<?php

namespace App;

use App\Models\BaseModel;
use App\Models\FormationPersonsItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuardNumber extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'name'
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function formation_persons_item()
    {
        return $this->hasMany(FormationPersonsItem::class, 'guard_number_id');
    }
}
