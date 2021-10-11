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
        Commands\WordOfTheDay::class,        
        Commands\MailToCartUser::class,        
        Commands\SaveLongLat::class,        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('word:day')
                ->yearly();
        $schedule->command('add_to_cart:mail_daily')
                ->daily();
        $schedule->command('add_to_cart:mail_three_day')
                ->cron('* * 3 * *');
        $schedule->command('add_to_cart:mail_weekly')
                ->weekly();
        $schedule->command('unpaid:cron')
                ->daily();
        $schedule->command('seller:kyc')
                ->everyMinute();
        $schedule->command('SaveLongLat:Daily')
                ->daily();
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
