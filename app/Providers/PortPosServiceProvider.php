<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PortPos;

class PortPosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PortPos::class, function ($app) {
            return new PortPos();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


}
