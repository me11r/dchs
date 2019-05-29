<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CivilProtectionServiceBlock extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sort_order',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
        'sort_order' => 'Порядок сортировки',
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
