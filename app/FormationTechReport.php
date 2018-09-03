<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 12.07.2018
 * Time: 21:13
 */

namespace App;


use App\Models\FormationTechItem;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationTechReport
 *
 * @property int $id
 * @property int $form_id
 * @property int $dept_id
 * @property string $field_0
 * @property string $field_1
 * @property string $field_3_0
 * @property string $field_3_1
 * @property string $field_4_1_0
 * @property string $field_4_1_1
 * @property string $field_4_2_0
 * @property string $field_4_2_1
 * @property string $field_4_3_0
 * @property string $field_4_3_1
 * @property string $field_5_1_0
 * @property string $field_5_1_1
 * @property string $field_5_1_2
 * @property string $field_5_1_3
 * @property string $field_5_0
 * @property string $field_5_1
 * @property string $field_5_2
 * @property string $field_5_3
 * @property string $field_5_4
 * @property string $field_5_5
 * @property string $field_5_6
 * @property string $field_5_7
 * @property string $field_5_8
 * @property string $field_5_9
 * @property string $field_5_10
 * @property string $field_5_11
 * @property string $field_5_12
 * @property string $field_2
 * @property string $field_7_1_0
 * @property string $field_7_1_1
 * @property string $field_7_0
 * @property string $field_8_0
 * @property string $field_8_1
 * @property string $field_9_0
 * @property string $field_9_1
 * @property string $field_3
 * @property string $field_4
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereDeptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField30($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField31($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField410($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField411($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField420($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField421($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField430($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField431($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField50($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField51($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField510($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField511($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField512($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField513($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField52($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField53($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField54($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField55($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField56($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField57($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField58($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField59($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField70($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField710($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField711($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField80($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField81($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField90($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereField91($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationTechReport todayRecords()
 */
class FormationTechReport extends Model
{
    protected $table = 'formation_tech_report';
    protected $guarded = ['id'];
    protected $fillable = [
        'form_id',
        'dept_id',
        'field_0',
        'field_1',
        'field_3_0',
        'field_3_1',
        'field_4_1_0',
        'field_4_1_1',
        'field_4_2_0',
        'field_4_2_1',
        'field_4_3_0',
        'field_4_3_1',
        'field_5_1_0',
        'field_5_1_1',
        'field_5_1_2',
        'field_5_1_3',
        'field_5_0',
        'field_5_1',
        'field_5_2',
        'field_5_3',
        'field_5_4',
        'field_5_5',
        'field_5_6',
        'field_5_7',
        'field_5_8',
        'field_5_9',
        'field_5_10',
        'field_5_11',
        'field_5_12',
        'field_2',
        'field_7_1_0',
        'field_7_1_1',
        'field_7_0',
        'field_8_0',
        'field_8_1',
        'field_9_0',
        'field_9_1',
        'field_3',
        'field_4',
    ];

    public function scopeTodayRecords($q)
    {
        return $q->whereDate('created_at', date('Y-m-d'));
    }

    public function formation_tech_items()
    {
        return $this->hasMany(FormationTechItem::class, 'formation_tech_report_id');
    }
}
