<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OperationalGroup extends Model
{
    protected $fillable = [
        'name'
    ];

    public function schedules()
    {
        return $this->hasMany(OperationalGroupSchedule::class, 'group_id');
    }

    public function scopeCurrentGroup($q)
    {
        $operSchedule = OperationalGroupSchedule::latest()->first();
        return $operSchedule->group ?? null;
    }

    public function scopeFindOperGroup($q, $search)
    {
        return $q->where('id', $search)
            ->orWhere('name', $search);
    }

    public function scopeNext($q, $name = null)
    {
        $latestSchedule = OperationalGroupSchedule::select('*')
            ->latest()
            ->first();

        if(!$latestSchedule && $name){

            $nextOperGroup = OperationalGroup::findOperGroup($name)
                ->first();

            if($nextOperGroup){
                return OperationalGroupSchedule::firstOrCreate([
                    'group_id' => $nextOperGroup->id,
                    'date_begin' => today()->addHours(18),
                    'date_end' => today()->addHours(42),
                ]);
            }

            return null;
        }

        $nextOperGroupId = $latestSchedule->group_id === 4 ? 1 : ($latestSchedule->group_id + 1);
        $nextFrom = Carbon::parse($latestSchedule->date_begin)->addDay();
        $nextTo = Carbon::parse($latestSchedule->date_end)->addDay();

        return OperationalGroupSchedule::firstOrCreate([
            'group_id' => $nextOperGroupId,
            'date_begin' => $nextFrom,//today()->addHours(18),
            'date_end' => $nextTo//today()->addHours(42),
        ]);

    }
}
