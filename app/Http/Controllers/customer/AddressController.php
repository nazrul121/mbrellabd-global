<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Shipping_address;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class AddressController extends Controller
{

    public function index(Request $request){
        if($request->draw){
            return datatables()::of(Shipping_address::where('customer_id',Auth::user()->customer->id)->orderBy('id','DESC'))
            ->editColumn('modify', function ($cat) {
                return ' <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="delete text-danger bg-warning" id="'.$cat->id.'"><span class="fa fa-trash"></span></button> &nbsp; 
                    <button type="button" class="text-white edit bg-info" id="'.$cat->id.'"><span class="fa fa-edit"></span></button>
                </div> ';
            })
            ->editColumn('full_name', function ($row) {
                return $row->fname . ' ' . $row->lname;
            }, 'fname,lname')
            ->editColumn('area', function($row){
                return $row->division->name.' <i class="fa fa-arrow-right"></i> '.$row->district->name.' <i class="fa fa-arrow-right"></i> '.$row->city->name;
            })

            ->rawColumns(['full_name','area','modify'])->make(true);
        }
        return view('customer.address.index');
    }

    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'customer_id'=>Auth::user()->customer->id,'division_id'=>$request->division,'district_id'=>$request->district,'city_id'=>$request->city,
        'fname'=>$request->fname, 'lname'=>$request->lname,'email'=>$request->email,'phone'=>$request->phone,'address'=>$request->address, 'postCode'=>$request->postCode];

        $slider = Shipping_address::create($data);
        $slider->save();

        return response()->json(['success' => 'Shipping address has been created successfully!']);
    }

    public function show($lang, Shipping_address $shipping_address){ 
        return Shipping_address::find($shipping_address->id);
    }


    public function update($lang, Request $request,Shipping_address $shipping_address){
        $validator = $this->fields($shipping_address->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'customer_id'=>Auth::user()->customer->id,'division_id'=>$request->division,'district_id'=>$request->district,'city_id'=>$request->city,
        'fname'=>$request->fname, 'lname'=>$request->lname,'email'=>$request->email,'phone'=>$request->phone,'address'=>$request->address, 'postCode'=>$request->postCode];

        $shipping_address->update($data);
        return response()->json(['success' => 'The shipping address hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'fname'=>'required', 'lname'=>'required',
            'phone'=>'required|unique:shipping_addresses,phone,'.$id,
            'email'=>'sometimes|nullable|unique:shipping_addresses,email,'.$id,

            'division'=>'required', 'district'=>'required', 'city'=>'required',
            'address'=>'required',  'postCode'=>'required'
        ]); return $validator;
    }

    public function destroy(Shipping_address $shipping_address){
        try {
            $shipping_address->delete();
            return response()->json(['success' => 'The address hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

}
