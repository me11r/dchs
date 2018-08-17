<?php

namespace App\Repositories\Contracts;


interface HydrantRepositoryInterface extends RepositoryInterface
{
    public function searchForPointByRadius($latitude, $longitude, $distance);
}
