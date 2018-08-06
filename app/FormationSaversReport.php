<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationSaversReport
 *
 * @property int $id
 * @property int $saved_people
 * @property int $saved_children
 * @property int $fires
 * @property int $ignitions
 * @property int $emergencies
 * @property int $rescues
 * @property int $searches
 * @property int $others
 * @property int $false_calls
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $total
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereEmergencies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereFalseCalls($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereFires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereIgnitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereOthers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereRescues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereSavedChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereSavedPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereSearches($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationSaversReport extends Model
{
    protected $table = 'formation_savers_report';
    protected $guarded = ['id'];
}