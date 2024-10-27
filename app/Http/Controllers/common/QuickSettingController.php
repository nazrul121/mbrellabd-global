<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Add_to_cart_log;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickSettingController extends Controller
{
    
    function index(){
        return view('common.setting.quick.index');
    }

    function update_product(Request $request){
        $fields = $request->all();
        // dd($fields);
       unset($fields['_token']);

        foreach($fields as $key=>$field){
            $sf = str_replace('_','-',$key);
            //for product
            if(isset($request->product_watermark)) {
                Setting::where('type','product-watermark')->update(['value'=>'1']);
            }else { Setting::where('type','product-watermark')->update(['value'=>'0']); }

            Setting::where('type',$sf)->update(['value'=>$field]);
        }
        return redirect()->back()->with('message','Settings updated successfully!');
    }

    function update_blog(Request $request){

        if(isset($request->blog_watermark)) {
            $watermark = 1;
        }else{$watermark = 0; }

        Setting::where('type','blog-watermark')->update(['value'=>$watermark]);
        Setting::where('type','blog-weight')->update(['value'=>$request->blog_weight]);
        Setting::where('type','blog-height')->update(['value'=>$request->blog_height]);
        // dd($watermark);
        return redirect()->back()->with('message','Settings updated successfully!');
    }

    function update_delivery(Request $request){
        Setting::where('type','deliveryCost_from')->update(['value'=>$request->delivery_cost]);
        return redirect()->back()->with('message','Delivery costing policy updated successfully!');
    }

    function catView(Request $request){
        Setting::where('type','cat-view')->update(['value'=>$request->cat_view]);
        return redirect()->back()->with('message','Category view option updated successfully!');
    }

    function colorView(Request $request){
        Setting::where('type','color-view')->update(['value'=>$request->color_view]);
        return redirect()->back()->with('message','Variant Volor view option updated successfully!');
    }


    function addToCart_status(Request $request){
        Setting::where('type','add-to-cart-status')->update(['value'=>$request->addToCartStatus]);
        Add_to_cart_log::create([
            'status'=>$request->addToCartStatus,
            'user_id'=>Auth::user()->id
        ]);
        return redirect()->back()->with('message','<b>Add to Cart status</b> has been updated successfully!');
    }

    function addToCart_logs(Request $request){
        if($request->draw){
            return datatables()::of(Add_to_cart_log::orderBy('id','DESC'))
            ->addIndexColumn()
            ->editColumn('user', function ($row) {
                $staff = Staff::where('user_id',$row->user_id)->first();
                if($staff==null){
                    $admin = Admin::where('user_id',$row->user_id)->first();
                    return  $admin->first_name.' <sub class="text-warning">'.$row->user->user_type->title.'</sub>';
                }
                return $staff->first_name;
            })
            ->editColumn('status', function ($row) {
               if($row->status=='0') $status = '<span class="text-danger">Inactive</span>';
               else $status = '<span class="text-success">Active</span>';
               return $status;
            })
            ->editColumn('created_at', function ($row) {
                
                return date('F m, Y g:i:sA',strtotime($row->created_at));
             })
            
            ->rawColumns(['status','created_at','user'])->make(true);
        }
        return view('common.setting.quick.addToCartLogs');
    }

}
