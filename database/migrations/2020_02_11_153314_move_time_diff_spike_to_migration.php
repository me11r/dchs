<?php

use Illuminate\Database\Migrations\Migration;

class MoveTimeDiffSpikeToMigration extends Migration
{
    private static $SQL = "CREATE FUNCTION TIME_DIFF_SPIKE(first_time TIME, second_time TIME)
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

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(self::$SQL);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS TIME_DIFF_SPIKE');
    }
}
