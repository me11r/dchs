<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectClassification extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];
}