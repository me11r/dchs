<?php

namespace App\Services;


class CommonHelper
{
    public function getMemoryUsed()
    {
        return round(memory_get_peak_usage() / 1024 / 1024, 2);// . ' Mb';
    }

    public function percent_difference($number1, $number2)
    {
        try {
            return (($number2 - $number1) / $number1) * 100;
        }
        catch (\Exception $e){
            return 0;
        }
    }

    public function execution_time($begin = null, $need_milliseconds = false)
    {
        if($begin == null){
            return microtime(true);
        }

        else {
            // Get the difference between start and end in microseconds, as a float value
            $diff = microtime(true) - $begin;

            // Break the difference into seconds and microseconds
            $sec = intval($diff);
            $micro = $diff - $sec;

            // Format the result as you want it
            // $final will contain something like "00:00:02.452"
            if($need_milliseconds){
                $final = strftime('%T', mktime(0, 0, $sec)) . str_replace('0.', '.', sprintf('%.3f', $micro));
            }
            else{
                $final = strftime('%T', mktime(0, 0, $sec));
            }

            return $final;
        }
    }

    # generates unique numbers in range
    function UniqueRandomNumbersWithinRange($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

}