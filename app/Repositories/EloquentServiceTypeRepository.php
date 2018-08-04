<?php

namespace App\Repositories;

use App\Models\ServiceType;
use App\Repositories\Contracts\ServiceTypeInterface;

class EloquentServiceTypeRepository extends Repository implements ServiceTypeInterface
{

    public function model()
    {
        return ServiceType::class;
    }

    public function getByInfo()
    {
        return $this->model
            ->where('info', true)
            ->get();
    }

}