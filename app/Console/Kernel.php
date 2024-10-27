<?php

namespace App\Console;

use App\Models\Product_promotion;
use App\Models\Promotion;
use Dymantic\InstagramFeed\Profile;
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
        $schedule->call(function(){
            $promotions = Promotion::where('status','1')->get();
            foreach($promotions as $promotion){
                $curdate = strtotime(date('Y-m-d H:i')); $expiry = strtotime($promotion->end_date.' '.$promotion->end_time);
                if($curdate > $expiry){
                    Product_promotion::where('promotion_id',$promotion->id)->update(['status'=>'0']);
                    $promotion->update(['status'=>'0']);
                }
            }
        })->everyMinute();

        $schedule->call(function(){
            Profile::where('username','mbrella_fashion')->first()->refreshFeed(8);
        })->twiceDaily();

        $schedule->command('instagram-feed:refresh-tokes')->monthlyOn(15, '03:00');
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
