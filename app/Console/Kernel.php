<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\DispatchInMail;
use App\Console\Commands\DispatchOutMail;
use App\Console\Commands\IssueInventoryEmail;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DispatchInMail::class,
        Commands\DispatchOutMail::class,
        Commands\IssueInventoryEmail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('dispatchin:daily')->weekdays()->at('11:55')->timezone('Asia/karachi');
         $schedule->command('dispatchout:daily')->weekdays()->at('16:30')->timezone('Asia/karachi');
//         $schedule->command('dispatchout:daily')->fridays()->at('12:00')->timezone('Asia/karachi');
//         $schedule->command('dispatchout:daily')->saturdays()->at('12:30')->timezone('Asia/karachi');
         $schedule->command('issueEmail:daily')->weekdays()->at('10:00')->timezone('Asia/karachi');

//        ->fridays()
//        ->at('17:00')

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
