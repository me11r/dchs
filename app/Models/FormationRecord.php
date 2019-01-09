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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord filled()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord todayRecord($organisation)
 * @property string|null $head
 * @property int|null $staff_total
 * @property int|null $staff_action
 * @property int|null $staff_duty_shift
 * @property int|null $tech_main_action
 * @property int|null $tech_main_reserve
 * @property int|null $tech_special_action
 * @property int|null $tech_special_reserve
 * @property int|null $tech_additional_action
 * @property int|null $tech_additional_reserve
 * @property int|null $tech_other_action
 * @property int|null $tech_other_reserve
 * @property int|null $gsm_gasoline
 * @property int|null $gsm_diesel
 * @property int|null $radio_stations
 * @property int|null $personal_respiratory_protection
 * @property int|null $personal_protection
 * @property int|null $other_protection
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereGsmDiesel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereGsmGasoline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereOtherProtection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord wherePersonalProtection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord wherePersonalRespiratoryProtection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereRadioStations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereStaffAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereStaffDutyShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereStaffTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechAdditionalAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechAdditionalReserve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechMainAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechMainReserve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechOtherAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechOtherReserve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechSpecialAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationRecord whereTechSpecialReserve($value)
 */
class FormationRecord extends Model
{
    public $guarded = ['id'];

    protected $fillable = [
        'organisation',
        'date',

        'head',
        'staff_total',
        'staff_action',
        'staff_duty_shift',
        'staff_duty_shift_8hours',

        'tech_main_action',
        'tech_main_reserve',
        'tech_special_action',
        'tech_special_reserve',
        'tech_additional_action',
        'tech_additional_reserve',
        'tech_other_action',
        'tech_other_reserve',
        'gsm_gasoline',
        'gsm_diesel',
        'radio_stations',
        'personal_respiratory_protection',
        'personal_protection',
        'other_protection',
        'approved',
    ];

    public function getRows()
    {
        $fillable = $this->getFillable();
        $unset1 = array_search('organisation', $fillable);
        $unset2 = array_search('date', $fillable);
        $unset3 = array_search('approved', $fillable);
        unset($fillable[$unset1], $fillable[$unset2], $fillable[$unset3]);

        return $fillable;
    }

    public function organisationName(): string
    {
        return FormationOrganisation::getNameByType($this->organisation);
    }

    public function scopeTodayRecord($q, $organisation)
    {
        return $q->whereDate('date', date('Y-m-d'))
            ->where('organisation', $organisation);
    }

    public function scopeFilled($q)
    {
        return $q
            ->whereNotNull('head')
            ->whereNotNull('staff_total')
            ->whereNotNull('staff_action')
            ->whereNotNull('staff_duty_shift')
            ->whereNotNull('tech_main_action')
            ->whereNotNull('tech_main_reserve')
            ->whereNotNull('tech_special_action')
            ->whereNotNull('tech_special_reserve')
            ->whereNotNull('tech_additional_action')
            ->whereNotNull('tech_additional_reserve')
            ->whereNotNull('tech_other_action')
            ->whereNotNull('tech_other_reserve')
            ->whereNotNull('gsm_gasoline')
            ->whereNotNull('gsm_diesel')
            ->whereNotNull('radio_stations')
            ->whereNotNull('personal_respiratory_protection')
            ->whereNotNull('personal_protection')
            ->whereNotNull('other_protection')
            ;
    }
}
