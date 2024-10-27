<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;

use App\Models\Country;
use App\Models\Dollar_rate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Symfony\Component\String\UnicodeString;
use Illuminate\Support\Str;
use Auth;


class DollarConvController extends Controller
{
    
    function index(Request $request){
        $countries = Country::where('status','1')->get();
        return view('common.setting.dollar-rate.index', compact('countries'));
    }


    public function store(Request $request){
        // dd($request->all());
        foreach($request->country as $key=>$cntry){
            if($request->currency[$key]){
                $check = Dollar_rate::where('country_id',$cntry);
                if($check->count()>0){
                    $check->update(['value'=> $request->currency[$key], 'user_id'=>Auth::user()->id]);
                }else{
                    Dollar_rate::create(['country_id'=>$cntry, 'value'=> $request->currency[$key],'user_id'=>Auth::user()->id]);
                }
            }
            
        }
        return back()->with('success','Dollar rates has been updated succesfully!');
    }

}
