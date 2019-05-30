<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CivilProtectionService extends Model
{
    protected $fillable = [
        'date',
        'note',
        'is_active',
    ];

    public function items()
    {
        return $this->hasMany(CivilProtectionServiceBlockItem::class,'cp_service_id');
    }

    public function scopeGetLatestRecord($q, $setInactive = false)
    {
        $latestRecord = CivilProtectionService::with(['items'])
            ->orderBy('date', 'desc')
            ->first();

        if ($latestRecord) {
            /*если нам нужно скопировать данные, имитируем новую запись, затирая айдишник и дату*/
            if (!$setInactive) {
                $latestRecord->id = null;
                $latestRecord->date = now()->format('Y-m-d');
                $latestRecord->is_active = true;
            }
            else {
                /*если мы создаем запись, то предыдущую ставим в inactive*/
                $latestRecord->is_active = false;
                $latestRecord->save();
            }
        }

        return $latestRecord;
    }
}
