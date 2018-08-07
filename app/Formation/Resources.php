<?php


namespace App\Formation;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Formation\Resources
 *
 * @property int $id
 * @property int $formation_savers_report_id
 * @property string|null $formation
 * @property int $staff_count
 * @property int $people_count
 * @property int $on_duty
 * @property int $tech_count
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereFormation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereFormationSaversReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereOnDuty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources wherePeopleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereStaffCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereTechCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Resources whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resources extends Model
{
    protected $table = 'formation_savers_resources';
    protected $guarded = ['id'];
}