<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class StaffOd extends Model
{
    protected $appends = ['unique'];

    public $attributeNames = [];

    public function getUniqueAttribute()
    {
        return $this->unique();
    }

    public function unique()
    {
        return $this->id . $this->created_at->timestamp;
    }

    public function getInitialsAttribute()
    {
        $fullName = explode(' ', $this->name);

        $surname = $fullName[0] ?? null;
        $name = $fullName[1] ?? null;
        $patronymic = $fullName[2] ?? null;

        $resultStr = $this->surname.' ';

        if($name){
            $resultStr .= mb_substr($name, 0, 1, 'utf-8').'.';
        }

        if($this->patronymic){
            $resultStr .= mb_substr($this->patronymic, 0, 1, 'utf-8').'.';
        }

        return $resultStr;

    }
}
