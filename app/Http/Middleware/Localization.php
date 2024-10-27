<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1); // Get the first segment of the URI (e.g., 'en' or 'fr')
  
        if(!session()->has('locale')){
            $locale = session()->put('locale', config('app.locale'));
        }else{
            $locale = session()->get('locale');
            // if (!in_array($locale, config('app.locales'))) {
            //     $locale = config('app.locale'); // Use default locale if segment is not a valid locale
            //     session()->put('locale', config('app.locale'));
            // }else{
            //     if(!session()->has('locale')){
            //         session()->put('locale', config('app.locale'));
            //     }
            //     $locale = session('locale', config('app.locale'));
            // }
        }
       

        
        // dd($locale);

        App::setLocale($locale);

        return $next($request);
    }
}