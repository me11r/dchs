<?php

namespace App\Repositories;

use App\Models\MudflowProtection;
use App\Repositories\Contracts\MudflowProtectionInterface;

class EloquentMudflowProtectionRepository extends Repository implements MudflowProtectionInterface
{

    public function model()
    {
        return MudflowProtection::class;
    }
}