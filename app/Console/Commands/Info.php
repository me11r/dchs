<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'info {--memory=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'найти топ процессов упорядоченных по используемой памяти, в мегабайтах (MB):';

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
        //найти топ процессов упорядоченных по используемой памяти, в мегабайтах (MB):
        if($this->option('memory') == 1) {
            exec('ps axo rss,comm,pid \
                | awk \'{ proc_list[$2]++; proc_list[$2 "," 1] += $1; } \
                END { for (proc in proc_list) { printf("%d\t%s\n", \
                proc_list[proc "," 1],proc); }}\' | sort -n | tail -n 10 | sort -rn \
                | awk \'{$1/=1024;printf "%.0fMB\t",$1}{print $2}\'', $data);
            $this->line($data);
        }
    }
}
