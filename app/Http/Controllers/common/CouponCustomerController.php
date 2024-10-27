<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Coupon_customer;
use App\Models\Customer;
use Illuminate\Http\Request;

class CouponCustomerController extends Controller
{
    function index(Customer $customer, Request $request){
        if($request->draw){
            $cus_ids = Coupon_customer::where('customer_id',$customer->id)->select('customer_id')->distinct('customer_id')->get()->toArray();
            return datatables()::of(Customer::whereIn('id',$cus_ids)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                return '
                <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>
                    <button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-primary btn-sm assign" id="'.$row->id.'"><span class="feather icon-user-plus"></span></button>
                </div>
                ';
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
        return view('common.ad.coupon.customers');

    }
}
