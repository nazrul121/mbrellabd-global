<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;


class LoginController extends Controller
{

    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard'; //RouteServiceProvider::HOME;


    public function __construct(){
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {
   
        $validator = $this->validateLogin();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $credentials = $request->only($this->username(), 'password');
        $remember = $request->input('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            return response()->json(['success' => 'Login success. Please wait..','route'=> $request->previousRoute]);
        }else{
            return response()->json(['error' => 'Invalid credentials']);
        }
    }




    protected function validateLogin()
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required',
            'password' => 'required',
        ]); return $validator;
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    // public function logout(){
    //     Auth::logout();
    //     session()->flush();
    //     return redirect()->route('login');
    // }


    public function username(){
        $login = request()->input('username');

        if(is_numeric($login)){
            $field = 'phone';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'email';
        }
        request()->merge([$field => $login]);
        return $field;
    }
}
