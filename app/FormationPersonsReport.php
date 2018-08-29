<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 12.07.2018
 * Time: 21:15
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationPersonsReport
 *
 * @property int $id
 * @property int $form_id
 * @property int $dept_id
 * @property string $field_0
 * @property string $field_2_0
 * @property string $field_2_1
 * @property string $field_2_2
 * @property string $field_2_3
 * @property string $field_2_4
 * @property string $field_2_5
 * @property string $field_3_0
 * @property string $field_3_1
 * @property string $field_3_2
 * @property string $field_3_3
 * @property string $field_3_4
 * @property string $field_3_5
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereDeptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField20($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField21($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField22($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField23($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField24($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField25($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField30($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField31($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField32($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField33($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField34($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereField35($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationPersonsReport extends Model
{
    protected $table = 'formation_persons_report';
    protected $guarded = ['id'];

    public function scopeTodayRecords($q)
    {
        return $q->whereDate('created_at', date('Y-m-d'));
    }
}