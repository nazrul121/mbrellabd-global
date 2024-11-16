<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;use Validator;

class DashboardController extends Controller
{

    public function index($lang){ 
        return view('customer.includes.dashboard');
    }

    function save_customer(Request $request){
        $customer = Customer::where('user_id',Auth::user()->id)->first();

        if($customer ==null){
            Customer::create([
                'user_id'=>Auth::user()->id, 'country_id'=>session('user_currency')->id,
                'first_name'=>$request->first_name,'last_name'=>$request->last_name, 'phone'=>$request->phone, 
                'division_id'=>$request->division, 'district_id'=>$request->district, 'city_id'=>$request->city,
                'address'=>$request->address, 'postCode'=>$request->postCode
            ]);
        }else{
            $customer->update([
                'first_name'=>$request->first_name,'last_name'=>$request->last_name,
                'division_id'=>$request->division, 'district_id'=>$request->district, 'city_id'=>$request->city,
                'address'=>$request->address, 'postCode'=>$request->postCode, 'phone'=>$request->phone, 
            ]);
        }

        $checkUser = User::where(['id'=>Auth::user()->id]);

        $checkUser->update(['phone'=>$request->phone]);

        return back();
    }


    function my_info($lang){ 
        return view('customer.profile');
    }

    function update(Request $request,$lang){
        $validator = $this->fields(Auth::user()->customer->id);
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()->all()]);
        // }

        Customer::where('id',Auth::user()->customer->id)->update([
            'division_id'=>$request->division,	'district_id'=>$request->district,	'city_id'=>$request->city,
            'first_name'=>$request->fname,'last_name'=>$request->lname,
            'phone'=>$request->phone,'address'=>$request->address, 'postCode'=>$request->postCode
        ]);

        return back()->with('message','✓ Account information has been updated successfully!');
    }

    private function fields($id=null){
        return request()->validate([
            'division'=>'required', 'district'=>'required', 'city'=>'required',
            'fname'=>'required', 'lname'=>'required',
            'phone'=>'required|unique:customers,phone,'.$id,
            'address'=>'required',  'postCode'=>'required'
        ]);
        // return Validator::make(request()->all(), []);
    }
}
