<?php

namespace App;

use App\Models\MudflowProtection;
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
}
