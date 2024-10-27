<?php

namespace App\Providers;

use App\Services\PortPos;
use App\Services\GPSMS;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('portPost', PortPos::class);
        app()->bind('GP_SMS', GPSMS::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        // if(env('APP_ENV'=='production')){
        //     \URL::forceScheme('https');
        // }
        
    }


}
