<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Coupon_type;
use Illuminate\Http\Request;
use Validator;

class CouponController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            if($request->type){
                $datatable = datatables()::of(Coupon::orderBy('id', 'DESC'));
                return $this->coupons($datatable);
            }else{
                $datatable = datatables()::of(Coupon::orderBy('id', 'DESC'));
                return $this->coupons($datatable);
            }
        }
        $coupon_types = Coupon_type::orderBy('title')->get();
        return view('common.ad.coupon.index', compact('coupon_types'));
    }

    private function coupons($datatable){
        return $datatable
        ->addIndexColumn()
        ->editColumn('modify', function ($row) {
            $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
            if(check_access('delete-coupons')){
                $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
            }
            if(check_access('edit-coupons')){
                $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
            }
            if(check_access('coupon-customer')){
                $data .= ' <button type="button" class="btn btn-primary btn-sm assign" id="'.$row->id.'"><span class="feather icon-user-plus"></span></button>';
            }
            $data .= '</div>';  return $data;
        })
        ->editColumn('assignedTo', function($row){
            return $row->customers()->count().' customers';
        })
        ->editColumn('expiry_date', function($row){
            return date('M d, Y',strtotime($row->expiry_date));
        })
        ->editColumn('status', function($row){
            if($row->is_validate=='1') return '<span class="badge badge-success">Active</span>';
            else return  '<span class="badge badge-danger">Inactive</span>';
        })

        ->rawColumns(['assignedTo','expiry_date','status','modify'])->make(true);
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'coupon_type_id'=>$request->type, 'title'=>$request->title,
            'coupon_code'=>$request->coupon_code, 'expiry_date'=>date('Y-m-d',strtotime($request->expiry_date)),
            'cost'=>$request->cost,'description'=>$request->description,'is_validate'=>$request->status
        ];
        $coupon = Coupon::create($data);
        $coupon->save();
        return response()->json(['success' => 'Coupon has been created successfully!']);
    }

    public function show(Coupon $coupon){ return Coupon::find($coupon->id);}


    public function update(Request $request,Coupon $coupon){
        $validator = $this->fields($coupon->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'coupon_type_id'=>$request->type, 'title'=>$request->title,
            'coupon_code'=>$request->coupon_code, 'expiry_date'=>date('Y-m-d',strtotime($request->expiry_date)),
            'cost'=>$request->cost,'description'=>$request->description,'is_validate'=>$request->status
        ];
        // dd($data);
        $coupon->update($data);
        return response()->json(['success' => 'The coupon hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'type'=>'required',
            'title'=>'required|unique:coupons,title,'.$id,
            'coupon_code'=>'required','cost'=>'required',
            'expiry_date'=>'required','status'=>'required',
        ]); return $validator;
    }



    public function destroy(Coupon $coupon){
        try {
            $coupon->delete();
            return response()->json(['success' => 'Coupon info hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
