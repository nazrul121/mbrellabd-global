<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(){
        $this->app['router']->middleware('https')->group(function () {
            $this->routes(function () {
                $this->configureRateLimiting();

        	$this->mapWebRoutes();
        	$this->mapSuperAdminRoutes();
        	$this->mapAdminRoutes();
        	$this->mapCommonRoutes();
        	$this->mapCustomerRoutes();
        	$this->mapStaffRoutes();
        	$this->mapApiRoutes();
            });
        });


    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(){
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function mapApiRoutes(){
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapWebRoutes(){
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/web.php'));
    }

    protected function mapSuperAdminRoutes(){
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/superAdmin.php'));
    }

    protected function mapAdminRoutes(){
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/admin.php'));
    }

    protected function mapCustomerRoutes(){
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/customer.php'));
    }

    protected function mapCommonRoutes(){
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/common.php'));
    }

    protected function mapStaffRoutes(){
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/staff.php'));
    }


    // public function map()
    // {
    //     $this->mapWebRoutes();

    //     // Other route mappings...

    //     // Dynamically map routes based on session values
    //     if(session()->has('user_currency')) {
    //         $currency = session()->get('user_currency')->name;
    //         if($currency == 'bd') {
    //             $this->mapBdRoutes();
    //         } else {
    //             $this->mapOtherCurrencyRoutes($currency);
    //         }
    //     }
    // }

    // protected function mapBdRoutes()
    // {
    //     Route::middleware('web')
    //         ->namespace($this->namespace)
    //         ->group(base_path('routes/bd.php'));
    // }

    // protected function mapOtherCurrencyRoutes($currency)
    // {
    //     Route::prefix($currency)
    //         ->middleware('web')
    //         ->namespace($this->namespace)
    //         ->group(base_path('routes/other_currency.php'));
    // }




}
