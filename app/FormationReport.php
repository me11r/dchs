<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationReport
 *
 * @property int $id
 * @property string $report_date
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationReport extends Model
{
    protected $table = 'formation_reports';
    protected $guarded = ['id'];
}