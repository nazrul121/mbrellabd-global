<?php

namespace App\Http\Middleware;

use App\Models\Cartlist;
use App\Models\General_info;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class SettingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
       

        $request->attributes->add([
            'system_title' =>  General_info::where('field','system_title')->pluck('value')->first(),
            'system_slogan' =>  General_info::where('field','system_slogan')->pluck('value')->first(),
            'system_email' =>  General_info::where('field','system_email')->pluck('value')->first(),
            'system_helpline' =>  General_info::where('field','system_helpline')->pluck('value')->first(),
            'system_phone' =>  General_info::where('field','system_phone')->pluck('value')->first(),
            'system_fax' =>  General_info::where('field','system_fax')->pluck('value')->first(),

            'office_address' =>  General_info::where('field','office_address')->pluck('value')->first(),
            'header_logo' =>  General_info::where('field','header_logo')->pluck('value')->first(),
            'footer_logo' =>  General_info::where('field','footer_logo')->pluck('value')->first(),
            'favicon' =>  General_info::where('field','favicon')->pluck('value')->first(),
            'currency' =>  General_info::where('field','system_currency')->pluck('value')->first(),
            'permissionType' => DB::table('settings')->where('type','staff-permission-type')->pluck('value')->first(),
            'addToCart' => DB::table('settings')->where('type','add-to-cart-status')->pluck('value')->first()
        ]);


        if (empty(session()->get('session_id'))) {
           $this->session_id();
        }

        // default currency
        if (!session()->has('user_currency') ) {
            $currency = \App\Models\Country::select(['id','name','short_name','short_code','currency_code','phone_code','flag','currencySymbol','currencyValue','zone'])->where('is_default','1')->first();
            session()->put('user_currency',$currency);
        }

        if (session()->has('cart')) {
            // dd(session()->get('cart'));
            session()->put('cartNum',session()->get('cart')->where('country_id',session('user_currency')->id)->count());
        }else {
            $cart = Cartlist::where(['country_id'=>session('user_currency')->id, 'session_id'=>session()->get('session_id'),'status'=>'1'])->get();
            session()->put('cart',$cart);
            session()->put('cartNum',session()->get('cart')->where('country_id',session('user_currency')->id)->count());
        }


        // // redirect to country base route 
        // if(strtolower(session()->get('user_currency')->name) !='bdt' && request()->path() !='change-currency'){
        //     $nowRoute = url('/'.strtolower(session()->get('user_currency')->name));
        //     return redirect($nowRoute);
        // }

        return $next($request);
    }

    function session_id(){
        // $ip = request()->ip();
        // $userAgent = request()->userAgent();
        // $session_id = $ip.'-'.md5($userAgent); 
        // session()->put('session_id', $session_id);
        $session_id = session()->get('session_id');
        if (empty($session_id)) {
            $session_id = session()->getId(); session()->put('session_id', $session_id);
        }
    }
}
