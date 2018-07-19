<?php

namespace App\Repositories;

use App\Models\Card112\Card112;
use App\Repositories\Contracts\Card112RepositoryInterface;

class EloquentCard112Repository extends Repository implements Card112RepositoryInterface
{
    public function model()
    {
        return Card112::class;
    }
}