<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\QueuedReport
 *
 * @property int $id
 * @property int|null $report_type_id
 * @property int|null $queue_status_id
 * @property string|null $file_path
 * @property string|null $date_start
 * @property string|null $date_end
 * @property array $report_data
 * @property int $attempts
 * @property string|null $error_text
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ReportType $reportType
 * @property-read \App\Models\QueueStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereErrorText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereQueueStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereReportData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereReportTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\QueuedReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QueuedReport extends Model
{
    /**
     * @var string
     */
    public $table = 'queued_reports';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $casts = [
        'report_data' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reportType()
    {
        return $this->hasOne(ReportType::class, 'id', 'report_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne(QueueStatus::class, 'id', 'queue_status_id');
    }

}
