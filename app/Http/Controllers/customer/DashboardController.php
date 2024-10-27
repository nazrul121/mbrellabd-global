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
                'user_id'=>Auth::user()->id,
                'first_name'=>$request->first_name,'last_name'=>$request->last_name,
                'division_id'=>$request->division, 'district_id'=>$request->district, 'city_id'=>$request->city,
                'address'=>$request->address, 'postCode'=>$request->postCode
            ]);
        }else{
            $customer->update([
                'first_name'=>$request->first_name,'last_name'=>$request->last_name,
                'division_id'=>$request->division, 'district_id'=>$request->district, 'city_id'=>$request->city,
                'address'=>$request->address, 'postCode'=>$request->postCode
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
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        Customer::where('id',Auth::user()->customer->id)->update([
            'division_id'=>$request->division,	'district_id'=>$request->district,	'city_id'=>$request->city,
            'first_name'=>$request->fname,'last_name'=>$request->lname,	'address'=>$request->address, 'postCode'=>$request->postCode
        ]);

        return back()->with('message','✓ Account information has been updated successfully!');
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'division'=>'required', 'district'=>'required', 'city'=>'required',
            'fname'=>'required', 'lname'=>'required',
            'phone'=>'required|unique:customers,phone,'.$id,
            'email'=>'sometimes|nullable|unique:shipping_addresses,email,'.$id,
            'address'=>'required',  'postCode'=>'required'
        ]); return $validator;
    }
}
