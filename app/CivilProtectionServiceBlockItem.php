<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CivilProtectionServiceBlockItem extends Model
{
    protected $fillable = [
        'cp_service_id',
        'cp_service_block_id',

        'position',
        'name',
        'contacts',
        'inventory1',
        'inventory2',
        'inventory3',
    ];

    public function cp_service()
    {
        return $this->belongsTo(CivilProtectionService::class, 'cp_service_id');
    }

    public function cp_service_block()
    {
        return $this->belongsTo(CivilProtectionServiceBlock::class, 'cp_service_block_id');
    }
}
