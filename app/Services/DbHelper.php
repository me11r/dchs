<?php

namespace App\Services;

use App\Services\FileHelper;
use Illuminate\Support\Facades\DB;

class DbHelper
{
    public $zipHelper;
    public $fileHelper;
    public $jsonHelper;
    public $path;
    public $all_tables = [];

    function __construct()
    {
        $this->fileHelper = new FileHelper();
        $this->all_tables = array_map('reset', DB::select('SHOW TABLES'));
    }

    public function cleanTable($table, $truncate = true, $echo = false)
    {
        try{
            if(is_array($table)){
                foreach ($table as $item){
                    $this->cleanTable($item, $truncate, $echo);
                }
            }
            else{
                if($truncate){
                    $this->truncate($table);
                    if($echo){
                        echo "Table [$table] was truncated" . PHP_EOL;
                    }
                }
                else{
                    DB::table($table)->delete();
                    if($echo){
                        echo "Table records of [$table] was deleted" . PHP_EOL;
                    }
                }
            }
        }
        catch (\Exception $e){
            if($echo){
                echo $e->getMessage() . PHP_EOL;
            }
            if($truncate){
                $this->cleanTable($table, false, $echo);
            }
        }
    }

    public function info($table = null)
    {
        $result = null;

        if($table){
            $result = DB::select('DESCRIBE '.$table);
        }
        else{
            foreach ($this->all_tables as $item) {
                $result[$item] = DB::select('DESCRIBE '.$item);
            }
        }

        return $result;
    }

    public function truncate($table)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}