<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('create:formation_report101')
             ->daily()
             ->at('18:00');

         //создание записи аналитики
         $schedule->command('create:analytics101report')
             ->daily()
             ->at('07:00');

         //создание опергруппы (смены) ОГ-1, ОГ-2 и т.д.
         $schedule->command('create:operational_group')
            ->daily()
            ->at('18:00');

         //забиваем отчет на главной странице
         $schedule->command('fill:main:reports')
            ->everyFiveMinutes();

        //создание записи "Тех.проверка системы оповещения"
        $schedule->command('create:alert_system_check')
            ->daily()
            ->at('07:00');

         //очередь отчетов
//         $schedule->command('process:queue')
//            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
