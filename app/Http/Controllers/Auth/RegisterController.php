<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(){ $this->middleware('guest'); }

    function register(){
        $data = $this->validator();
        if(request()->user_type_id) $data['user_type_id'] = 2; //echo 'Vendor';
        else $data['user_type_id'] = 4; //echo 'customer';

        $user = User::create([
            'user_type_id'=>'4',
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        // dd($user->id);
        Customer::create([
            'user_id'=>$user->id,
            'first_name'=> $data['first_name'],
            'last_name'=> $data['last_name'],
            'address'=> $data['address'],
        ]);

        if (Auth::attempt(array('phone'=>$data['phone'], 'password'=>$data['password']))) {
            return redirect()->intended('dashboard')->withSuccess('Signed in');
        }

        return redirect("login")->withSuccess('Login details are not valid');
    }


    private function validator(){
        return request()->validate( [
            'first_name'=>'required', 'last_name'=>'required',
            'address'=>'required',
            'phone'=>'required|digits:11|unique:users,phone',
            'email'=>'sometimes|nullable|email|unique:users,email',
            'password' => ['required', 'string', 'min:8'],
        ]);

    }

}
