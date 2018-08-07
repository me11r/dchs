<?php


namespace App\Formation;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Formation\Migrations
 *
 * @property int $id
 * @property int $formation_savers_report_id
 * @property string|null $route
 * @property string|null $from
 * @property string|null $to
 * @property int $tech_count
 * @property int $people_count
 * @property string|null $manager_name
 * @property string|null $nickname
 * @property string|null $phone
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereFormationSaversReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations wherePeopleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereTechCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Migrations whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Migrations extends Model
{
    protected $table = 'formation_savers_migrations';
    protected $guarded = ['id'];
}