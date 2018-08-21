<?php

namespace App\Models;

use App\Enums\FormationOrganisation;
use Illuminate\Database\Eloquent\Model;

class FormationRecord extends Model
{
    public $guarded = ['id'];

    public function organisationName(): string
    {
        return FormationOrganisation::getNameByType($this->organisation);
    }
}
