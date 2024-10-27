<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAreaController extends Controller
{
    function index(Request $request){
        return view('common.order.area.index');
    }

    function orders($ids){
        $orders = Order::whereIn('customer_id', explode(',',$ids))->get();
        return view('common.order.area.orders', compact('orders'));
    }

    function customers($ids){
        $customers = Customer::whereIn('id',explode(',',$ids))->get();
        return view('common.order.area.customers', compact('customers'));
    }

}
