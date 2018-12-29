<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 12.07.2018
 * Time: 21:15
 */

namespace App;


use App\Models\FormationPersonsItem;
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport todayRecords()
 * @property string|null $total
 * @property string|null $active
 * @property string|null $head_guards
 * @property string|null $commander_squads
 * @property string|null $drivers
 * @property string|null $privates
 * @property string|null $dispatchers
 * @property string|null $vacation
 * @property string|null $study
 * @property string|null $maternity
 * @property string|null $sick
 * @property string|null $business_trip
 * @property string|null $other
 * @property string|null $gas_smoke_protection_service
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormationPersonsItem[] $formation_person_items
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereBusinessTrip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereCommanderSquads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereDispatchers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereDrivers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereGasSmokeProtectionService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereHeadGuards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereMaternity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport wherePrivates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereSick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereStudy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationPersonsReport whereVacation($value)
 * @property-read \App\FireDepartment $fireDepartment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FormationOdPersonItem[] $formation_person_items_od
 */
class FormationPersonsReport extends Model
{
    protected $table = 'formation_persons_report';
    protected $guarded = ['id'];

    protected $fillable = [
        'total',
        'form_id',
        'dept_id',
        'active',
        'head_guards',
        'commander_squads',
        'drivers',
        'privates',
        'dispatchers',
        'vacation',
        'study',
        'maternity',
        'sick',
        'business_trip',
        'other',
        'gas_smoke_protection_service',
        'trainee',
        'sick_leave',
    ];
    
    public $od_staff = [
        'dspt' => StaffDspt::class,
        'cpps' => StaffCpps::class,
        'crb' => StaffCrb::class,
        'doctor' => StaffDoctor::class,
        'duty_vehicle' => StaffDutyVehicle::class,
        'edds' => StaffEdds::class,
        'gdzs_base' => StaffGdzsBase::class,
        'ipl' => StaffIpl::class,
        'kshm' => StaffKshm::class,
        'senior_communication_master' => StaffSeniorCommunicationMaster::class,
        'water_supply' => StaffWaterCanal::class,
        'zhalin' => StaffZhalin::class,
    ];

    public function scopeTodayRecords($q)
    {
        return $q->whereDate('created_at', date('Y-m-d'));
    }

    public function fireDepartment()
    {
        return $this->belongsTo(FireDepartment::class, 'dept_id');
    }

    public function formation_person_items()
    {
        return $this->hasMany(FormationPersonsItem::class, 'report_id');
    }

    public function getTraineeCount($position)
    {
        return $this->formation_person_items()->where('trainee_type', $position)->count();
    }

    public function formation_person_items_od()
    {
        return $this->hasMany(FormationOdPersonItem::class, 'report_id');
    }
    
    public function getODStaff()
    {
        $od_people = $this->od_staff;
        $result = [];
        foreach ($od_people as $key => $od_person) {
            $result[$key] = $od_person::all();
            foreach ($result[$key] as $item) {
                $item->staff;
            }
        }

        $result['dspt_vacation'] = StaffDspt::all();
        $result['dspt_sick'] = StaffDspt::all();
        $result['dspt_business_trip'] = StaffDspt::all();

        return $result;
    }
}