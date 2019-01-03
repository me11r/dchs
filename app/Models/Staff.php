<?php

namespace App\Models;

use App\FireDepartment;
use App\GuardNumber;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Staff
 *
 * @property int $id
 * @property int|null $department_id
 * @property string $name
 * @property string|null $date_birth
 * @property string|null $rank
 * @property string|null $position
 * @property string|null $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FireDepartment|null $department
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereDateBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Staff whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $unique
 */
class Staff extends Model
{
    protected $table = 'staff';

    protected $appends = ['unique', 'initials'];

    protected $fillable = [
        'department_id',
        'name',
        'date_birth',
        'rank',
        'position',
        'status',
        'surname',
        'patronymic',
        'guard_number_id',
    ];

    public function getUniqueAttribute()
    {
        return $this->unique();
    }

    public function getInitialsAttribute()
    {
        $fullName = explode(' ', $this->name);

        $surname = $fullName[0] ?? null;
        $name = $fullName[1] ?? null;
        $patronymic = $fullName[2] ?? null;

        $resultStr = $this->surname.' ';

        if($name){
            $resultStr .= mb_substr($name, 0, 1, 'utf-8').'.';
        }

        if($this->patronymic){
            $resultStr .= mb_substr($this->patronymic, 0, 1, 'utf-8').'.';
        }

        return $resultStr;

    }

    public function unique()
    {
        return $this->id . $this->created_at->timestamp;
    }

    public function department()
    {
        return $this->belongsTo(FireDepartment::class, 'department_id');
    }

    public function guard_number()
    {
        return $this->belongsTo(GuardNumber::class, 'guard_number_id');
    }

    public function statuses($status = null)
    {
        $arr = [
            'head_guards' => 'Нач. караула',
            'commander_squads' => 'Ком. отделения',
            'drivers' => 'Водитель',
            'privates' => 'Рядовой',
            'dispatchers' => 'Диспетчер',
            'trainee' => 'Стажер',
        ];

        if($status != null){
            return $arr[$status] ?? null;
        }

        return $arr;
    }


}
