<?php

namespace App\Services;


use App\Analytics101;
use App\Analytics101Item;
use App\Chronology101;
use App\CustomQueue;
use App\FireDepartment;
use App\Models\FireDepartmentResult;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\ReportCache;
use App\Ticket101;

class ReportProcessor
{
    private $queue = null;

    public function fire(CustomQueue $queue)
    {
        if(method_exists($this, $queue->name)) {
            $this->queue = $queue;
            $this->{$queue->name}($queue->options_decoded);
            CustomQueue::destroy($queue->id);
        }
    }

    //название отчета
    public function generateName()
    {
        return "{$this->queue->name}_{$this->queue->user_id}";
    }

    /*Report methods*/
    private function report1($options)
    {
        $date_begin = $options['date_begin'] ?? null;
        $date_end = $options['date_end'];
        $result_id = $options['result_id'];
        $burnt_id = $options['burnt_id'];
        $city_area_id = $options['city_area_id'];

        $result = Ticket101::getDetailedStat($date_begin, $date_end, $result_id, $burnt_id, $city_area_id);

        $nameGenerated = $this->generateName();

        $reportCache = ReportCache::updateOrCreate([
            'name' => $nameGenerated,
        ],[
            'name' => $nameGenerated,
            'data' => json_encode($result),
        ]);
    }
}