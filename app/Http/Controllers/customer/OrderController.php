<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Order_payment;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class OrderController extends Controller
{

    public function index($lang){
        $orders = Order::where('customer_id',Auth::user()->customer->id)->paginate(12);
        return view('customer.orders', compact('orders'));
    }

}
