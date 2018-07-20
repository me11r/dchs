<?php

namespace App\Repositories;

use App\Dictionary\FireObject;
use App\Repositories\Contracts\FireObjectInterface;

class EloquentFireObjectRepository extends Repository implements FireObjectInterface
{

    public function model()
    {
        return FireObject::class;
    }

    public function getByName($name)
    {
        return $this->model
            ->where('name', $name)
            ->first();
    }

}