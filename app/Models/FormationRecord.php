<?php

namespace App\Models;

use App\Enums\FormationOrganisation;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FormationRecord
 *
 * @property int $id
 * @property string $organisation
 * @property string $date
 * @property int|null $field_1_0_0
 * @property int|null $field_2_0_0
 * @property int|null $field_2_1_0
 * @property int|null $field_2_2_0
 * @property int|null $field_3_0_0
 * @property int|null $field_3_0_1
 * @property int|null $field_3_1_0
 * @property int|null $field_3_1_1
 * @property int|null $field_3_2_0
 * @property int|null $field_3_2_1
 * @property int|null $field_3_3_0
 * @property int|null $field_3_3_1
 * @property int|null $field_4_0_0
 * @property int|null $field_4_1_0
 * @property int|null $field_5_0_0
 * @property int|null $field_6_0_0
 * @property int|null $field_7_0_0
 * @property int|null $field_8_0_0
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField100($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField200($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField210($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField220($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField300($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField301($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField310($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField311($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField320($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField321($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField330($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField331($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField400($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField410($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField500($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField600($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField700($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereField800($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereOrganisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationRecord extends Model
{
    public $guarded = ['id'];

    public function organisationName(): string
    {
        return FormationOrganisation::getNameByType($this->organisation);
    }
}
