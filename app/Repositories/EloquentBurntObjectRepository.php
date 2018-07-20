<?php

namespace App\Repositories;

use App\Dictionary\BurntObject;
use App\Repositories\Contracts\BurntObjectInterface;

class EloquentBurntObjectRepository extends Repository implements BurntObjectInterface
{

    public function model()
    {
        return BurntObject::class;
    }

    public function getByName($name)
    {
        return $this->model
            ->where('name', $name)
            ->first();
    }

}