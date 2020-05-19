<?php

use Illuminate\Database\Seeder;

class DiffSpikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SQL = "CREATE FUNCTION TIME_DIFF_SPIKE(first_time TIME, second_time TIME)
            RETURNS VARCHAR(20) CHARSET utf8
            LANGUAGE SQL
            READS SQL DATA 
            DETERMINISTIC
        BEGIN
            DECLARE first_date TIMESTAMP;
            DECLARE second_date TIMESTAMP;
            DECLARE output_format VARCHAR(50) CHARSET utf8 DEFAULT '%Hч. %iм.';
        
            SET first_date = TIMESTAMP('2000-01-01', first_time);
        
            IF second_time < first_time THEN
                SET second_date = TIMESTAMP('2000-01-02', second_time);
            ELSE
                SET second_date = TIMESTAMP('2000-01-01', second_time);
            END IF;
        
            RETURN TIME_FORMAT(ABS(TIMEDIFF(first_date, second_date)), output_format);
        END";

        \Illuminate\Support\Facades\DB::unprepared('DROP FUNCTION IF EXISTS TIME_DIFF_SPIKE');
        \Illuminate\Support\Facades\DB::unprepared($SQL);
    }
}
