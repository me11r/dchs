<?php

namespace App\Repositories\Contracts;


use App\Models\Card112\Card112;

interface Card112RepositoryInterface extends RepositoryInterface
{
    public function createFilledWithRelations(array $data): Card112;

    public function updateFilledWithRelations(array $data, int $id): Card112;
}