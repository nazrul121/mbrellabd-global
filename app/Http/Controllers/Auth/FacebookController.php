<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use Exception;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class FacebookController extends Controller

{
    public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();
    }


    public function handleFacebookCallback(){
        try {
            $user = Socialite::driver('facebook')->fields(['name', 'first_name', 'last_name', 'email'])->user();
            $dbUser = User::where('email', $user->email)->where('email','!=',null)->first();

            dd( $user );
            if($dbUser)  {
                // dd($dbUser);
                Auth::login($dbUser);
            }
            else{
                $finduser = $this->create_customer($user);
                // dd($finduser);
                Auth::login($finduser);
            }
            return redirect()->route('dashboard');
        } catch (Exception $e) { var_dump($e->getMessage()); }

    }

    private function create_customer($user){

        $finduser = User::where('email', $user->email)->where('email','!=',null)->first();
        if($finduser){
            $newUser = $finduser;
        }else{
            $newUser = User::create([
                'user_type_id' => 4, 'email' => $user->email,
                'facebook_id'=> $user->id, 'password' => Hash::make('mbrella123')
            ]);
        }

        $check = Customer::where('user_id',$newUser->id);
        if($check->count() <1 ){

            Customer::create([
                'user_id'=>$newUser->id,'first_name'=>$user['first_name'],
                'last_name'=>$user['last_name'],'photo'=>$user->avatar_original
            ]);
        }

        return $newUser;
    }

}
