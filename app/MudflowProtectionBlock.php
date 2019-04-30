<?php

namespace App;

use App\Models\MudflowProtection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MudflowProtectionBlock extends Model
{
    protected $fillable = [
        'date',
        'text_header',
        'text_footer',
    ];

    public function items()
    {
        return $this->hasMany(MudflowProtection::class, 'block_id');
    }

    public function getDateHumanAttribute()
    {
        return $this->date ? Carbon::parse($this->date)->format('d.m.Y') : null;
    }
}
