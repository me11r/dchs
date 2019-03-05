<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeDbDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ds = DIRECTORY_SEPARATOR;
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $path = database_path() . $ds . 'dumps'.$ds;
//        $file = 'dump-'.date('d-m-Y') . '.sql';
//        $command = sprintf('mysqldump -h %s -u %s -p\'%s\' %s > %s', $host, $username, $password, $database, $path . $file);
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $command = "mysqldump -u {$username} -p{$password} {$database} | gzip > database/dumps/dump-latest.sql.gz";

        exec($command);
    }
}
