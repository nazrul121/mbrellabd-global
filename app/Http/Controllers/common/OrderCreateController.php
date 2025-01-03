<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Order_status_change;
use App\Models\Product;
use App\Models\Product_combination;
use App\Models\Product_variation_option;
use App\Models\Setting;
use App\Models\Shipping_address;
use App\Models\User;
use App\Models\Variation_option;
use App\Models\Invoice_discount;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Services\GPSMS;


class OrderCreateController extends Controller
{


    function index(Request $request){
        $session_id = session()->get('session_id');
        if (empty($session_id)) { $session_id = session()->getId(); session()->put('session_id', $session_id);}

        if($request->product_id !=null){
            // $combiString = ''; $option_id = array();
            // $variatioOptionType =  Setting::where('type','variation-at-product-list')->pluck('value')->first();

            // foreach($request->variation_options as $vo){
            //     $combiString .= Variation_option::where('id',$vo)->pluck('origin')->first();
            // }

            // $product_v_o = Product_variation_option::where([
            //     'product_id'=>$request->product_id,
            //     'variation_id'=>$variatioOptionType,
            //     'variation_option_id'=>$vo,  'status'=>'1'
            // ])->pluck('id')->first();

            // $stringParts = str_split($combiString);
            // sort($stringParts);

            // $comb = Product_combination::where('unique_string', implode($stringParts))->first();
            // dd(implode($stringParts));
            // dd(implode($stringParts), $comb->unique_string);
            $comb = Product_combination::where('id', $request->combination)->first();
            $listViewVariationId = DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();

            if($comb->qty < 1 || $request->qty > $comb->qty){
                return response()->json(['failed'=>'Product Quantity is not enough!!']);
            }

            $variation_option_id = null;
            foreach(explode('~',$comb->combination_string) as $str){
                $check = Variation_option::where(['variation_id'=>$listViewVariationId, 'origin'=>$str]);
                if($check->count()>0){
                    // echo $check->pluck('title')->first().'<br/>';
                    $variation_option_id = $check->pluck('id')->first();
                    break;
                }
            }

            // return 'product_id: '.$request->product_id.', variation id:'.$listViewVariationId.', option_id: '.$variation_option_id;
            // dd(session()->get('address'));

            $user_id = User::where('phone',session()->get('address')['phone'])->pluck('id')->first();
            // dd($user_id);
            $product = Product::where('id',$request->product_id)->first();
            $p = DB::table('product_promotion')->where(['product_id'=>$product->id, 'status'=>'1'])->first();

            $newPrice = '';
            if($p==null) $newPrice = $product->sale_price;
            else $newPrice = $p->discount_price;

            $checkOrderItem = Order_item::where([ 'user_id'=>$user_id, 'product_id'=>$request->product_id,
            'product_combination_id'=>$request->combination, 'status'=>'creating', 'created_by'=>Auth::user()->id]);

            if($checkOrderItem->count() < 1){
                Order_item::create([
                    'product_id'=>$request->product_id,
                    'product_combination_id'=>$comb->id,
                    'variation_option_id'=>$variation_option_id,
                    'qty'=>$request->qty,
                    'net_price'=>$product->net_price,
                    'sale_price'=>$product->sale_price,
                    'discount_price'=>$newPrice,
                    'vat'=>$product->vat,
                    'vat_type'=>$product->vat_type,
                    // 'Note'=> 'The order is backend generated by '.Auth::user()->phone,
                    'status'=>'creating',
                    'user_id'=>$user_id,
                    'created_by'=>Auth::user()->id,
                ]);
                return response()->json(['success'=>'The item has been added into to order successfully']);
            }else return response()->json(['failed'=>'The item already exist into the list!!']);
        }

        return view('common.order.create.index');
    }

    function save_address(Request $request){
        // dd($request->all());
        session()->put('address',$request->all() );
        // dd( session()->forget('address') );
        return back();
    }

    function order_items(Request $request){
        if(session()->has('address')){
            $user_id = User::where('phone',session()->get('address')['phone'])->pluck('id')->first();

            $session_id = session()->get('session_id');
            if (empty($session_id)) { $session_id = session()->getId(); session()->put('session_id', $session_id);}
            $order_items = Order_item::where(['user_id'=>$user_id, 'created_by'=>Auth::user()->id, 'status'=>'creating'])->get();
            // dd($user_id);
        }else{
            // $order_items = new Order_item();
            $order_items = Order_item::where('user_id','!=', null)->where(['status'=>'creating', 'created_by'=>Auth::user()->id])->get();
        }
        return view('common.order.create.products', compact('order_items'));
    }

    function variations(Product $product){
        $variations = Product_variation_option::where('product_id',$product->id)->distinct('variation_id')->select('variation_id')->get();
        foreach($variations as $pvo){
            echo '<div class="col-6">'.$pvo->variation->title;

            $newVariations = Product_variation_option::where(['product_id'=>$product->id, 'variation_id'=>$pvo->variation_id] )->get();
            echo '<select class="form-control" name="variation_options[]">';
            foreach($newVariations as $Npvo){
                echo '<option value="'.$Npvo->variation_option_id.'">'.$Npvo->variation_option->title.'</option>';
            }
            echo '</select></div>';
        }
    }


    function remove_item(Order_item $order_item){
        $order_item->delete();
    }

    function make_order(Request $request){

        $invoiceDiscount = Invoice_discount::where('status','1')->first();
        $invoice_discount = 0;
        $totalCost = intval($request->total_cost);

        if($invoiceDiscount && $totalCost >= $invoiceDiscount->min_order_amount){
            if($invoiceDiscount!=null && $invoiceDiscount->type=='general'){

                if($invoiceDiscount->discount_in=='percent'){
                    $percentage = $invoiceDiscount->discount_value;
                    $discount = ($percentage / 100) * $totalCost;
                    $invoice_discount = $discount; //$subtotal - ($subtotal * ($percentage/100));
                }else $invoice_discount = $invoiceDiscount->discount_value;

            }
            elseif($invoiceDiscount!=null && $invoiceDiscount->type=='free-delivery'){
                $invoice_discount = intval($request->shippingCost);
            }
        }



        // dd(  $invoice_discount );
        // dd(session()->get('address'));

        $vats = [];
        if(!session()->get('address')){
            return back()->with('error','Please create customer billing & shipping details first !');
        }

        if (session()->has('address.same')) {
            $billData =[
                'division'=>explode('|',session()->get('address')['division'])[1],
                'district'=>explode('|',session()->get('address')['district'])[1],
                'city'=>explode('|',session()->get('address')['city'])[1],
                'first_name'=> session()->get('address')['fname'], 
                'last_name'=> session()->get('address')['lname'],
                'phone'=> session()->get('address')['phone'],  
                'email'=> session()->get('address')['email'],
                'address'=> session()->get('address')['address']
            ];

            $shipData =[
                'ship_division'=>explode('|',session()->get('address')['division'])[1],
                'ship_district'=>explode('|',session()->get('address')['district'])[1],
                'ship_city'=>explode('|',session()->get('address')['city'])[1],
                'ship_first_name'=> session()->get('address')['fname'], 
                'ship_last_name'=> session()->get('address')['lname'],
                'ship_phone'=> session()->get('address')['phone'],  
                'ship_email'=> session()->get('address')['email'],
                'ship_address'=> session()->get('address')['address']
            ];
        }
        else{
            // dd('no customer and billing shipping same');
            $billData =[
                'division'=>explode('|',session()->get('address')['division'])[1],
                'district'=>explode('|',session()->get('address')['district'])[1],
                'city'=>explode('|',session()->get('address')['city'])[1],
                'first_name'=> session()->get('address')['fname'], 
                'last_name'=> session()->get('address')['lname'],
                'phone'=> session()->get('address')['phone'],  
                'email'=> session()->get('address')['email'],
                'address'=> session()->get('address')['address']
            ];

            $shipData =[
                'ship_division'=>explode('|',session()->get('address')['shipping_division'])[1],
                'ship_district'=>explode('|',session()->get('address')['shipping_district'])[1],
                'ship_city'=>explode('|',session()->get('address')['shipping_city'])[1],
                'ship_first_name'=> session()->get('address')['shipping_fname'], 
                'ship_last_name'=> session()->get('address')['shipping_lname'],
                'ship_phone'=> session()->get('address')['shipping_phone'],  
                'ship_email'=> session()->get('address')['shipping_email'],
                'ship_address'=> session()->get('address')['shipping_address']
            ];
        }

        // dd($billData, $shipData);

        $order_items = Order_item::where(['created_by'=>Auth::user()->id,'status'=>'creating'])->get();

        if($order_items->count() <1){
            return back()->with('error','Order items are not found! Please contact to developer with error details!');
        }

        foreach($order_items as $key=>$cart){
            $p = DB::table('product_promotion')->where(['product_id'=>$cart->product->id, 'status'=>'1'])->first();

            $newPrice = '';
            if($p==null) $newPrice = $cart->product->sale_price;
            else $newPrice = $p->discount_price;

            if($cart->vat_type=='excluding'){
                $vat = ($cart->vat / 100) * $newPrice;
            }else $vat = $cart->vat;

            $subtotal[] = $newPrice * $cart->qty;
            $vats[] = $vat * $cart->qty;
        }

        $orderCount = DB::table('orders')->orderBy('id','desc')->take(1)->pluck('id')->first();
        $transaction_id = date('Ym-'). $orderCount + 100;

        if($request->zone) $shipping_type = 'zone';
        else $shipping_type = 'district';

        // dd($shippingAddress->id);
        // dd($user);

        $orderData = [ 'invoice_id'=>$orderCount  + 10000,
            'country_id'=>2,
            'currency_value'=>1.0,
            'payment_geteway_id'=>$request->payment_method,
            'zone_id'=>$request->zone,
            'shipping_address_id'=>null,
            'shippingCostFrom'=>$shipping_type,
            'total_items'=>$order_items->count(),'total_cost'=>$request->total_cost,
            'invoice_discount'=>$invoice_discount, 'transaction_id'=>$transaction_id,
            'shipping_cost'=>$request->shippingCost, 'vat'=>array_sum($vats),'note'=>'Menual order creaed by '.Auth::user()->phone,
            'order_date'=>date('Y-m-d',strtotime($request->order_date)),
            'created_by'=>Auth::user()->id,
            'ref'=>$request->ref
        ];

   
        $orderData = array_merge($orderData, $billData, $shipData);
        $order = Order::create($orderData);


        if($invoiceDiscount){
            \App\Models\Invoice_discount_order::create([
                'invoice_discount_id'=>$invoiceDiscount->id,'order_id'=>$order->id
            ]);
        }

        foreach($order_items as $key=>$cart){
            $cart->update([ 'order_id'=>$order->id, 'status'=>'placed' ]);
            // reduce qty
            $cart->product->update(['qty'=> $cart->product->qty - $cart->qty]);
            if($cart->product_combination_id !=null){
                $exQty = $cart->product_combination->qty;
                $cart->product_combination->update(['qty'=> $exQty - $cart->qty]);
                $cart->update(['order_id'=> $order->id, 'status'=>'placed']);
            }
        }

        Order_status_change::create(['order_id'=>$order->id, 'order_status_id'=>'1', 'user_id'=>Auth::user()->id,
        'note'=>'Menual order placed','created_at'=>date('Y-m-d h:m:s',strtotime($request->order_date))]);

        session()->forget('address');
        return back()->with('success','The order has been created successfully');
    }



    function clear_order(){
        if(session()->has('address')){
            $user_id = User::where('phone',session()->get('address')['phone'])->pluck('id')->first();
            $session_id = session()->get('session_id');
            if (empty($session_id)) { $session_id = session()->getId(); session()->put('session_id', $session_id);}
            Order_item::where(['user_id'=>$user_id, 'created_by'=>Auth::user()->id, 'status'=>'creating'])->delete();
            session()->forget('address');
        }
        return back();
    }



    private function shipping_address($customer){
        // dd(session()->get('address'));

        if(array_key_exists('same', session()->get('address'))){
            // $check = Shipping_address::where([ 'phone'=>session()->get('address')['phone'] ]);
            // if($check->count()>0){ return $check->first(); }

            $shipping=  [
                'division_id'=>session()->get('address')['division'], 'district_id'=>session()->get('address')['district'],
                'city_id'=>session()->get('address')['city'], 'fname'=>session()->get('address')['fname'],
                'lname'=>session()->get('address')['lname'],'email'=>session()->get('address')['email'],'phone'=>session()->get('address')['phone'],
                'address'=>session()->get('address')['address']
            ];
        }else{

            $check = Shipping_address::where([ 'phone'=>session()->get('address')['shipping_phone'] ]);
            if($check->count()>0){

                $check->update([
                    'division_id'=>session()->get('address')['shipping_division'], 'district_id'=>session()->get('address')['shipping_district'],
                    'city_id'=>session()->get('address')['shipping_city'], 'fname'=>session()->get('address')['shipping_fname'],
                    'lname'=>session()->get('address')['shipping_lname'],'email'=>session()->get('address')['shipping_email'],'phone'=>session()->get('address')['shipping_phone'],
                    'address'=>session()->get('address')['shipping_address']
                ]);
                $shipping = $check->first();
            }else{
                // dd('here');
                $shipping=  [
                    'division_id'=>session()->get('address')['shipping_division'], 'district_id'=>session()->get('address')['shipping_district'],
                    'city_id'=>session()->get('address')['shipping_city'], 'fname'=>session()->get('address')['shipping_fname'],
                    'lname'=>session()->get('address')['shipping_lname'],'email'=>session()->get('address')['shipping_email'],'phone'=>session()->get('address')['shipping_phone'],
                    'address'=>session()->get('address')['shipping_address']
                ];
            }
        }
        return $shipping;
    }


    function check_billing_address($field, $field_value){
        $user= User::where($field, $field_value)->first();
        if($user !=null){
            $customer = Customer::where('user_id',$user->id)->orderBy('id','DESC')->first();
            $customer->phone = $user->phone;
            $customer->email = $user->email;
            return $customer;
        }else return null;
    }

    function check_shipping_address($field, $field_value){
        $shipping_address= Shipping_address::where($field, $field_value)->first();
        if($shipping_address !=null) return $shipping_address;
    }



    function search_product(Request $request){

        $data = Product::where('title','LIKE',$request->name.'%')
            ->orWhere('design_code',$request->name)->get()->take(20);
        $output = '';
        if ($data->count() >0) {
            $output = '<ul class="list-group ml-4" style="display:block;margin-bottom:3em;border-bottom:1px solid silver;box-shadow:0px 1px 15px;">';
            foreach ($data as $row) {
                $output .= '<li data-id="'.$row->id.'" class="list-group-item" style="cursor:pointer"><img src="'.$row->thumbs.'" height="20"> &nbsp; '.$row->title.'</li>';
            }
            $output .= '</ul>';
            return $output;
        }else {
            return '<p class="text-danger text-center mb-3" style="border-bottom:1px solid;padding-bottom:1.3em;">'.'No Data Found'.'</p>';
        }
    }


    function sendSMS(){
        $sms = new GPSMS();
        $messageForCustomer = 'Hello world, this a test text';
     
       $sms->send_sms(
            reason: 'order-placed', 
            message: $messageForCustomer,
            numbers: ['01749015457'],
            countrycode: '+880', 
            orderId: 12694
        );

        // return back()->with('message','An SMS sent successfully!');
    }






}
