<?php

namespace App\Http\Controllers;

use App\Models\Campaign_product;
use App\Models\Customer;
use App\Models\Invoice_discount;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Order_payment;
use App\Models\Order_status_change;
use App\Models\Product_promotion;
use App\Models\Shipping_address;
use App\Models\User;
use App\Models\Dollar_rate_order;
use App\Models\Dollar_rate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Services\GPSMS;
use App\Services\DHL;

class CheckoutController extends Controller
{

    function checkout(){
        if (session('user_currency')->id !='2' ){
            // $dhl_method = new DHL();
            // $dhl_rates = json_decode( $dhl_method->get_rates() );
          
            return view('checkout-abroad');
        } 
        else return view('checkout');
    }

    function index(Request $request){

        // dd($request->all());
        if(!$request->customer_id){
            // dd('no customer and billing shipping different');
            $this->billingFields();
        }

        if(!$request->customer_id && !$request->billing_shipping_same){
            // dd('shipping address needed');
            $this->shippingFields();  
        }


        if($request->customer_id && $request->billing_shipping_same){
            // dd('customer and billing shipping same');
            $customer = Customer::where('id',$request->customer_id)->first();
          
            $billData =[
                'division'=>$customer->division->name,
                'district'=>$customer->district->name,
                'city'=>$customer->city->name,
                'first_name'=>$customer->first_name, 
                'last_name'=>$customer->last_name,
                'phone'=>$customer->phone, 
                'email'=>$request->email,
                'address'=>$customer->address,
            ];
            $shipData =[
                'ship_division'=>$customer->division->name,
                'ship_district'=>$customer->district->name,
                'ship_city'=>$customer->city->name,
                'ship_first_name'=>$customer->first_name, 
                'ship_last_name'=>$customer->last_name,
                'ship_phone'=>$customer->phone, 
                'ship_email'=>$customer->email,
                'ship_address'=>$customer->address,
            ];
            $shipiingCityId = $customer->city_id;
        }
        else if($request->customer_id && !$request->billing_shipping_same){
            // dd('customer and billing shipping different');
            $customer = Customer::where('id',$request->customer_id)->first();
            $billData =[
                'division'=>$customer->division->name,
                'district'=>$customer->district->name,
                'city'=>$customer->city->name,
                'first_name'=>$customer->first_name, 
                'last_name'=>$customer->last_name,
                'phone'=>$customer->phone, 
                'email'=>$request->email,
                'address'=>$customer->address,
            ];

            $shipping = Shipping_address::where('id',$request->shipping_address_id)->first();
            $shipData =[
                'ship_division'=>$shipping->division->name,
                'ship_district'=>$shipping->district->name,
                'ship_city'=>$shipping->city->name,
                'ship_first_name'=>$shipping->first_name, 
                'ship_last_name'=>$shipping->last_name,
                'ship_phone'=>$shipping->phone, 
                'ship_email'=>$shipping->email,
                'ship_address'=>$shipping->address,
            ];

            $shipiingCityId = $shipping->city_id;

        }
        else if(!$request->customer_id && $request->billing_shipping_same){
            // dd('no customer and billing shipping same');
            $billData =[
                'division'=>explode('|',$request->division)[1],
                'district'=>explode('|',$request->district)[1],
                'city'=>explode('|',$request->city)[1],
                'first_name'=>$request->fname, 'last_name'=>$request->lname,
                'phone'=>$request->phone,  'email'=>$request->email,
                'address'=>$request->address,
            ];

            $shipData =[
                'ship_division'=>explode('|',$request->division)[1],
                'ship_district'=>explode('|',$request->district)[1],
                'ship_city'=>explode('|',$request->city)[1],
                'ship_first_name'=>$request->fname, 'ship_last_name'=>$request->lname,
                'ship_phone'=>$request->phone, 'ship_email'=>$request->email,
                'ship_address'=>$request->address,
            ];
            $shipiingCityId = explode('|',$request->city)[0];
        }
        else if(!$request->customer_id && !$request->billing_shipping_same){
            // dd('no customer and billing shipping different');
            $billData =[
                'division'=>explode('|',$request->division)[1],
                'district'=>explode('|',$request->district)[1],
                'city'=>explode('|',$request->city)[1],
                'first_name'=>$request->fname, 'last_name'=>$request->lname,
                'phone'=>$request->phone,  'email'=>$request->email,
                'address'=>$request->address,
            ];

            $shipData =[
                'ship_division'=>explode('|',$request->shipping_division)[1],
                'ship_district'=>explode('|',$request->shipping_district)[1],
                'ship_city'=>explode('|',$request->shipping_city)[1],
                'ship_first_name'=>$request->shipping_fname, 'ship_last_name'=>$request->shipping_lname,
                'ship_phone'=>$request->shipping_phone, 'ship_email'=>$request->shipping_email,
                'ship_address'=>$request->shipping_address,
            ];
            $shipiingCityId = explode('|',$request->shipping_city)[0];
        }

    
        if ($request->has('createAccount')) {
            // dd($request->all());
            $user = $this->create_user($request);
            if(!$request->customer_id){
                // dd('create customer');
                $customer = $this->create_customer($request,$user);
            }else{
                $customer = Customer::where('id',$request->customer_id)->first();
            }
            $customerId = $customer->id;

            $shippingAddress = $this->shipping_address($request, $customer);
            $shippingId = $shippingAddress->id;
            $createdBy = $customer->user_id;
            $userId = $user->user_id;

        }else{
            $customerId = $shippingId = $createdBy = $userId = null;
        }


        $subtotal =  $vats = [];
    
        foreach(session()->get('cart') as $key=>$cart){
            if($cart->product->vat_type=='excluding'){
                $vat = ($cart->product->vat / 100) * product_price($cart->product_id, $cart->product->sale_price);
            }else $vat = $cart->product->vat;

            $subtotal[] = product_price($cart->product_id, $cart->product->sale_price) * $cart->qty;
            $vats[] = $vat * $cart->qty;
        }

        $order_items = session()->get('cart')->count();
        $total_cost = array_sum($subtotal);

        // dd(session()->get('user_currency'));
        if($total_cost <1){
            return back()->with('alert','An unexpected error occurred! Please Continue...');
        }
        $shippingType= \App\Models\Setting::where('type','deliveryCost_from')->pluck('value')->first();
        if($request->shipping_type !=$shippingType){
           return back()->with('alert','Wrong selection is not allowed');
        }

        if($request->shipping_type=='district'){
            $delivery_cost = DB::table('districts')->where('id',$request->district)->pluck('delivery_cost')->first();
            $shipping_cost = deliveryCharge($delivery_cost);

        }else{
            $zone_id = DB::table('city_zone')->where('city_id',$shipiingCityId)->pluck('zone_id')->first();

            $delivery_cost = DB::table('zones')->where('id',$zone_id)->pluck('delivery_cost')->first();
            if($delivery_cost==null) $delivery_cost = 0;
            $shipping_cost = deliveryCharge($delivery_cost);
        }

        // dd($request->all(), $shipping_cost);
        // dd($shipping_cost);

        $orderCount = DB::table('orders')->orderBy('id','desc')->take(1)->pluck('id')->first();
        $transaction_id = date('Ym-'). $orderCount + 100;

        $invoiceDiscount = Invoice_discount::find($request->invoice_discount_id);
        if($invoiceDiscount !=null && $invoiceDiscount->type=='free-delivery'){
            $invoice_discount = $shipping_cost;
        }else $invoice_discount = $request->invoice_discount;

        $orderData = [
            'invoice_id'=>$orderCount  + 10000, 'customer_id'=>$customerId,
            'country_id'=>session()->get('user_currency')->id, 
            'currency_value'=>session()->get('user_currency')->currencyValue,
            'payment_geteway_id'=>$request->payment_gateway,'zone_id'=>$request->zone,
            'shipping_address_id'=>$shippingId,
            'shippingCostFrom'=>$request->shipping_type, 
            'total_items'=>$order_items,
            'total_cost'=>$total_cost,
            'invoice_discount'=>$invoice_discount,
            'transaction_id'=>$transaction_id,
            'shipping_cost'=>$shipping_cost,
            'vat'=>array_sum($vats),
	        'order_date'=>date('Y-m-d'),
            'note'=>$request->note,
            'created_by'=>$createdBy
        ];

        $orderData = array_merge($orderData, $billData, $shipData);
        // dd($orderData);
        $order = Order::create($orderData);


        if($request->invoice_discount_id){
            \App\Models\Invoice_discount_order::create([
                'invoice_discount_id'=>$request->invoice_discount_id,'order_id'=>$order->id
            ]);
        }

        Order_status_change::create(['order_id'=>$order->id, 'order_status_id'=>'1', 'user_id'=>$userId, 'note'=>'Order just placed']);
        
        foreach(session()->get('cart') as $key=>$cart){

            $campaign_id = Campaign_product::where('product_id',$cart->product->id)->where('status','1')->pluck('campaign_id')->first();
            $productPromotion = Product_promotion::where(['product_id'=>$cart->product_id,'status'=>'1'])->orderBy('discount_price','DESC')->first();
            
            if($productPromotion==null && session()->has('outlet_customer')){
                $outlet_customer_id=session()->get('outlet_customer')->customer_id;
                $outlet_percent=session()->get('outlet_customer')->percent;
                $outlet_percent_amt= (session()->get('outlet_customer')->percent / 100) * product_price($cart->product_id, $cart->product->sale_price);
                $outlet_category=session()->get('outlet_customer')->category;
            }else{
                $outlet_customer_id = $outlet_percent= $outlet_percent_amt=  $outlet_category = null;
            }

            if($productPromotion==null) $promotion_id = null;
            else $promotion_id = $productPromotion->promotion_id;

            $salePrice = $cart->product->sale_price  * session()->get('user_currency')->currencyValue;
            $disPrice = product_price($cart->product->id,$cart->product->sale_price);
            $disPercent = ((($salePrice - $disPrice) / $salePrice) * 100);

            if($promotion_id==null && $disPercent>0){
                dd('Unable to create order! no promo setup but discount is available ', $cart);
            }
           

            Order_item::create([
                'order_id'=>$order->id,	'campaign_id'=>$campaign_id, 
                'promotion_id'=>$promotion_id,	
                'product_id'=>$cart->product_id,
                'variation_option_id'=>$cart->variation_option_id,
                'product_combination_id'=>$cart->product_combination_id,
                'qty'=>$cart->qty, 
                'outlet_customer_id'=>$outlet_customer_id, 
                'outlet_percent'=>$outlet_percent, 
                'outlet_percent_amt'=> $outlet_percent_amt , 
                'outlet_category'=>$outlet_category,

                
                'net_price'=>$cart->product->net_price * session()->get('user_currency')->currencyValue,
                'sale_price'=>$salePrice,
                'discount_price'=> $disPrice, 
                'vat'=>$cart->product->vat,
                'vat_type'=>$cart->product->vat_type
            ]);
            $cart->update(['status'=>'0']);

            $cart->product->update(['qty'=> $cart->product->qty - $cart->qty]);
            if($cart->product_combination_id !=null){
                $exQty = $cart->product_combination->qty;
                $cart->product_combination->update(['qty'=> $exQty - $cart->qty]);
            }
        }

        session()->forget('cart'); session()->forget('cartNum');session()->forget('outlet_customer');
        
	    //dd('working..');

        $sms = new GPSMS();
        $messageForCustomer = 'Hello '.ucfirst($order->first_name.' '.$order->last_name).'! Your order has been placed successfully. Please visite '.route('order-info',$order->transaction_id);
     
       
        $sms->send_sms(
            reason: 'order-placed', 
            message: $messageForCustomer,
            numbers: [$order->phone],
            countrycode: $order->country->phone_code, 
            orderId: $order->id
        );

	
        if($request->payment_gateway !=1){

            $gatway = DB::table('payment_gateways')->where('id',$request->payment_gateway)->pluck('name_origin')->first();
		
            //if($gatway=='sslcommerz') 
            //   return redirect()->route('sslcommerce.place-order',$order->id);

            if($gatway=='portPos')
             return redirect()->route('portPost-payment', ['order' => $order->id]);            
        }
        else {
            return redirect()->route('order-info',$transaction_id);
        }
       
    }

    function index_abroad(Request $request){
  
        if(!$request->customer_id){
            // dd('no customer and billing shipping different');
            $this->billingFields();
        }

        if(!$request->customer_id && $request->billing_shipping_same=='0'){
            $this->shippingFields();  
        }


        if($request->customer_id && $request->billing_shipping_same){
            // dd('customer and billing shipping same');
            $customer = Customer::where('id',$request->customer_id)->first();
            $billData =[
                'division'=>$customer->division->name,
                'district'=>$customer->district->name,
                'city'=>$customer->city->name,
                'first_name'=>$customer->first_name, 
                'last_name'=>$customer->last_name,
                'phone'=>$customer->phone, 
                'email'=>$request->email,
                'address'=>$customer->address,'postCode'=>$customer->postCode,
            ];
            $shipData =[
                'ship_division'=>$customer->division->name,
                'ship_district'=>$customer->district->name,
                'ship_city'=>$customer->city->name,
                'ship_first_name'=>$customer->first_name, 
                'ship_last_name'=>$customer->last_name,
                'ship_phone'=>$customer->phone, 
                'ship_email'=>$customer->email,
                'ship_address'=>$customer->address,'ship_postCode'=>$customer->postCode,
            ];
            
        }
        else if($request->customer_id && !$request->billing_shipping_same){
            // dd('customer and billing shipping different');
            $customer = Customer::where('id',$request->customer_id)->first();
            $billData =[
                'division'=>$customer->division->name,
                'district'=>$customer->district->name,
                'city'=>$customer->city->name,
                'first_name'=>$customer->first_name, 
                'last_name'=>$customer->last_name,
                'phone'=>$customer->phone, 
                'email'=>$request->email,
                'address'=>$customer->address,'postCode'=>$request->postCode,
            ];

            $shipping = Shipping_address::where('id',$request->shipping_address_id)->first();
            $shipData =[
                'ship_division'=>$shipping->division->name,
                'ship_district'=>$shipping->district->name,
                'ship_city'=>$shipping->city->name,
                'ship_first_name'=>$shipping->first_name, 
                'ship_last_name'=>$shipping->last_name,
                'ship_phone'=>$shipping->phone, 
                'ship_email'=>$shipping->email,
                'ship_address'=>$shipping->address,'ship_postCode'=>$request->postCode,
            ];

        }
        else if(!$request->customer_id && $request->billing_shipping_same){
            // dd('no customer and billing shipping same');
            $billData =[
                'division'=>explode('|',$request->division)[1],
                'district'=>explode('|',$request->district)[1],
                'city'=>explode('|',$request->city)[1],
                'first_name'=>$request->fname, 'last_name'=>$request->lname,
                'phone'=>$request->phone,  'email'=>$request->email,
                'address'=>$request->address,'postCode'=>$request->postCode,
            ];

            $shipData =[
                'ship_division'=>explode('|',$request->division)[1],
                'ship_district'=>explode('|',$request->district)[1],
                'ship_city'=>explode('|',$request->city)[1],
                'ship_first_name'=>$request->fname, 'ship_last_name'=>$request->lname,
                'ship_phone'=>$request->phone, 'ship_email'=>$request->email,
                'ship_address'=>$request->address,'ship_postCode'=>$request->postCode,
            ];
           
        }
        else if(!$request->customer_id && !$request->billing_shipping_same){
            // dd('no customer and billing shipping different');
            $billData =[
                'division'=>explode('|',$request->division)[1],
                'district'=>explode('|',$request->district)[1],
                'city'=>explode('|',$request->city)[1],
                'first_name'=>$request->fname, 'last_name'=>$request->lname,
                'phone'=>$request->phone,  'email'=>$request->email,
                'address'=>$request->address,'postCode'=>$request->postCode,
            ];

            $shipData =[
                'ship_division'=>explode('|',$request->shipping_division)[1],
                'ship_district'=>explode('|',$request->shipping_district)[1],
                'ship_city'=>explode('|',$request->shipping_city)[1],
                'ship_first_name'=>$request->shipping_fname, 'ship_last_name'=>$request->shipping_lname,
                'ship_phone'=>$request->shipping_phone, 'ship_email'=>$request->shipping_email,
                'ship_address'=>$request->shipping_address,'ship_postCode'=>$request->shipping_postCode,
            ];
           
        }

        
    
        if ($request->has('createAccount')) {
            $user = $this->create_user($request);
            if(!$request->customer_id){
                // dd('create customer');
                $customer = $this->create_customer($request,$user);
            }else{
                $customer = Customer::where('id',$request->customer_id)->first();
            }
            $customerId = $customer->id;

            $shippingAddress = $this->shipping_address($request, $customer);
            $shippingId = $shippingAddress->id;
            $createdBy = $customer->user_id;
            $userId = $user->user_id;

        }else{
            $customerId = $shippingId = $createdBy = $userId = null;
        }


        $subtotal =  $vats = [];
    
        foreach(session()->get('cart') as $key=>$cart){
            if($cart->product->vat_type=='excluding'){
                $vat = ($cart->product->vat / 100) * product_price($cart->product_id, $cart->product->sale_price);
            }else $vat = $cart->product->vat;

            $subtotal[] = product_price($cart->product_id, $cart->product->sale_price) * $cart->qty;
            $vats[] = $vat * $cart->qty;
        }

        $order_items = session()->get('cart')->count();
        $total_cost = array_sum($subtotal);
        if($total_cost <1){
            return back()->with('alert','An unexpected error occurred! Please Continue...');
        }

    
        if($request->shipping_charge){
            $shipping_cost = number_format($request->shipping_charge, 2);
        }else{
            return back()->with('alert','Shipping charge is not allocated');
        }
        // dd($request->all());
        // dd($shipping_cost);

        $orderCount = DB::table('orders')->orderBy('id','desc')->take(1)->pluck('id')->first();
        $transaction_id = date('Ym-'). $orderCount + 100;

        $invoiceDiscount = Invoice_discount::find($request->invoice_discount_id);
        if($invoiceDiscount !=null && $invoiceDiscount->type=='free-delivery'){
            $invoice_discount = $shipping_cost;
        }else $invoice_discount = $request->invoice_discount;

        $orderData = [
            'invoice_id'=>$orderCount  + 10000, 'customer_id'=>$customerId,
            'country_id'=>session()->get('user_currency')->id, 
            'currency_value'=>session()->get('user_currency')->currencyValue,
            'payment_geteway_id'=>$request->payment_gateway,'zone_id'=>$request->zone,
            'shipping_address_id'=>$shippingId,
            'shippingCostFrom'=>'dhl', 
            'total_items'=>$order_items,
            'total_cost'=>$total_cost,
            'invoice_discount'=>$invoice_discount,
            'transaction_id'=>$transaction_id,
            'shipping_cost'=>$shipping_cost,
            'vat'=>array_sum($vats),
            'note'=>$request->note,
            'order_date'=>date('Y-m-d'),
            'created_by'=>$createdBy
        ];

        $orderData = array_merge($orderData, $billData, $shipData);
       
        $order = Order::create($orderData);


        if($request->invoice_discount_id){
            \App\Models\Invoice_discount_order::create([
                'invoice_discount_id'=>$request->invoice_discount_id,'order_id'=>$order->id
            ]);
        }

        Order_status_change::create(['order_id'=>$order->id, 'order_status_id'=>'1', 'user_id'=>$userId, 'note'=>'Order just placed']);
        
        
        foreach(session()->get('cart') as $key=>$cart){

            $campaign_id = Campaign_product::where('product_id',$cart->product->id)->where('status','1')->pluck('campaign_id')->first();
            $productPromotion = Product_promotion::where(['product_id'=>$cart->product_id,'status'=>'1'])->orderBy('discount_price','DESC')->first();
            
            if($productPromotion==null && session()->has('outlet_customer')){
                $outlet_customer_id=session()->get('outlet_customer')->customer_id;
                $outlet_percent=session()->get('outlet_customer')->percent;
                $outlet_percent_amt= (session()->get('outlet_customer')->percent / 100) * product_price($cart->product_id, $cart->product->sale_price);
                $outlet_category=session()->get('outlet_customer')->category;
            }else{
                $outlet_customer_id = $outlet_percent= $outlet_percent_amt=  $outlet_category = null;
            }

            if($productPromotion==null) $promotion_id = null;
            else $promotion_id = $productPromotion->promotion_id;

            if($cart->product->product_weight !=null){
                $gross_weight = $cart->product->product_weight->gross_weight;
            }else $gross_weight = null;
    
            
            if($cart->product->product_weight !=null){
                $vol_weight = $cart->product->product_weight->vol_weight;
            }else $vol_weight = null;

       

            Order_item::create([
                'order_id'=>$order->id,	'campaign_id'=>$campaign_id, 
                'promotion_id'=>$promotion_id,	
                'product_id'=>$cart->product_id,
                'variation_option_id'=>$cart->variation_option_id,
                'product_combination_id'=>$cart->product_combination_id,
                'qty'=>$cart->qty, 
            
                'net_price'=>$cart->product->net_price / session()->get('user_currency')->currencyValue,
                'sale_price'=>$cart->product->sale_price  / session()->get('user_currency')->currencyValue,
                'discount_price'=> product_price($cart->product->id,$cart->product->sale_price), 
                'gross_weight'=>$gross_weight, 
                'net_weight'=>$vol_weight, 
                'vat'=>$cart->product->vat,
                'vat_type'=>$cart->product->vat_type
            ]);
            $cart->update(['status'=>'0']);

            $cart->product->update(['qty'=> $cart->product->qty - $cart->qty]);
            if($cart->product_combination_id !=null){
                $exQty = $cart->product_combination->qty;
                $cart->product_combination->update(['qty'=> $exQty - $cart->qty]);
            }
        }



        $dollarRate = Dollar_rate::where('country_id',session('user_currency')->id)->first();
        if($dollarRate !=null){
            $orderRateOrder = Dollar_rate_order::create([
                'dollar_rate_id'=>$dollarRate->id,
                'order_id'=>$order->id,
                'country_id'=>$dollarRate->country_id,
                'value'=>$dollarRate->value,
            ]);
        }

        session()->forget('cart'); session()->forget('cartNum');session()->forget('outlet_customer');
        
        //send for HDL shipment
        $dhl_method = new DHL();
        $dhl_shipment =  json_encode($dhl_method->create_shipment($order));

        if($request->payment_gateway !=1){
          
            $gatway = DB::table('payment_gateways')->where('id',$request->payment_gateway)->pluck('name_origin')->first();
            if($gatway=='sslcommerz') 
                return redirect()->route('sslcommerce.place-order',$order->id);

            if($gatway=='portPos')
                return redirect()->route('portPost-payment',$order);
        }
        else {
            return redirect()->route('order-info',$transaction_id);
        }

    }
    
    


    function order_info($transaction_id){
        $order = Order::where('transaction_id',$transaction_id)->first();
        if($order){
            $payment = Order_payment::where('order_id',$order->id)->first();
            return view('order-placed',compact('order','payment'));
        }else return view('errors.408');
    }

    function print_invoice($transaction_id){
        $order = Order::where('transaction_id',$transaction_id)->orWhere('invoice_id',$transaction_id)->first();
        return view('invoice',compact('order'));
    }

    private function billingFields($id=null){
        $fields = [
            'fname'=>'required', 
            'lname'=>'required',
            'division'=>'required',
            'district'=>'required',
            'city'=>'required',
            'address'=>'required',
        ];
        $phoneCountry = strtoupper(session('user_currency')->short_code);
        if (session('user_currency')->id != 2){
            $fields['postCode'] = 'required';
            $fields['email'] = 'required|email';
            $fields['phone'] = 'required';
        }else {
            $fields['phone'] = 'required|digits:11';
        }
        
        return request()->validate($fields);
    }

    private function shippingFields($id=null){
        $fields = [
            'shipping_fname'=>'required',
            'shipping_lname'=>'required',
            'shipping_division'=>'required',
            'shipping_district'=>'required', 
            'shipping_city'=>'required', 
            'shipping_address'=>'required',
        ];

        if (session('user_currency')->id != 2){
            $fields['shipping_postCode'] = 'required';
            $fields['shipping_email'] = 'required|email';
            $fields['shipping_phone'] = 'required';
        }else{
            $fields['shipping_phone'] = 'required|digits:11';
        }

        return request()->validate($fields);
    }

    //create user
    private function create_user($request){
        $check = User::where('phone',$request->phone)->whereNotNull('phone');
        $check2 = User::where('email',$request->email)->whereNotNull('email');
      
        if($check->count()>0){
            return $check->first();
        }else{
            return User::create([ 'user_type_id'=>'4','phone'=>$request->phone,'email'=>$request->email,'password'=>Hash::make($request->phone)]);
        }
    }

    //create customer record
    private function create_customer($request, $user){
        $check = Customer::where('user_id',$user->id);
        if($check->count() >0) return $check->first();

        $check2 = Customer::where('phone',$request->phone);
        if($check2->count() >0) return $check2->first();


        $customer = Customer::create([
            'user_id'=>$user->id,
            'country_id'=>session('user_currency')->id,
            'division_id'=>explode('|',$request->division)[0],	'district_id'=>explode('|',$request->district)[0],	'city_id'=>explode('|',$request->city)[0],
            'first_name'=>$request->fname,'last_name'=>$request->lname,	'phone'=>$request->phone, 'address'=>$request->address,'postCode'=>$request->postCode
        ]);

        return $customer;
    }

    private function shipping_address($request, $customer){
        if($request->billing_shipping_same){

            $check = Shipping_address::where('phone',$request->phone);
  
            if($check->count()<1){
                return Shipping_address::create([
                    'customer_id'=>$customer->id,'country_id'=>session('user_currency')->id,'division_id'=>$request->division,	'district_id'=>$request->district,'city_id'=>$request->city,
                    'fname'=>$request->fname, 'lname'=>$request->lname,'email'=>$request->email,'phone'=>$request->phone,	'address'=>$request->address,'postCode'=>$request->postCode
                ]);
            }
            else {
                // $check->first()->update([
                //     'division_id'=>$customer->division_id, 'district_id'=>$customer->district_id,
                //     'city_id'=>$customer->city_id, 'fname'=>$customer->first_name,
                //     'lname'=>$customer->last_name, 'address'=>$customer->address,
                // ]);
                return $check->first();
            }
        }
        else{
            if($request->shipping_address_id){
                return Shipping_address::find($request->shipping_address_id);
            }else{
                $check = Shipping_address::where('phone',$request->shipping_phone)->orWhere('email',$request->shipping_email)->where('email','!=',null);
           
                if($check->count()<1){
                    return Shipping_address::create([
                        'customer_id'=>$customer->id, 'country_id'=>session('user_currency')->id, 'division_id'=>$request->shipping_division,	'district_id'=>$request->shipping_district,'city_id'=>$request->shipping_city,
                        'fname'=>$request->shipping_fname, 'lname'=>$request->shipping_lname, 'email'=>$request->shipping_email,'phone'=>$request->shipping_phone,'address'=>$request->shipping_address, 'postCode'=>$request->shipping_postCode
                    ]);
                }else{
                    // $check->first()->update([
                    //     'division_id'=>$request->shipping_division,	'district_id'=>$request->shipping_district,'city_id'=>$request->shipping_city,
                    //     'fname'=>$request->shipping_fname, 'lname'=>$request->shipping_lname,'email'=>$request->shipping_email,'phone'=>$request->shipping_phone,'address'=>$request->shipping_address
                    // ]);
                    return $check->first();
                }
            }
        }
    }


    function check_billing_address($field, $field_value){
        // if(strpos($field_value, "+88") !== false){
        //     $field_value = str_replace("+88", "", $field_value);
        // } 

        if($field=='email'){
            $user= User::where($field, $field_value)->first();
            $customer = Customer::where('user_id',$user->id)->orderBy('id','DESC')->first();
            $customer->phone = $user->phone;
            $customer->email = $user->email;
            return $customer;
        }else{
            $customer = Customer::where('phone',$field_value)->orderBy('id','DESC')->first();
            return $customer;
        }
    }

    function check_shipping_address($field, $field_value){
        // dd($field, $field_value);
        if(strpos($field_value, "+88") !== false){
            $field_value = str_replace("+88", "", $field_value);
        } 
        $shipping_address= Shipping_address::where($field, $field_value)->first();
        if($shipping_address !=null) return $shipping_address;
    }


    function order_trucking(Request $request){
        $order = New Order();
        if($request->invoice){
            $order = Order::where('transaction_id',$request->invoice)->orWhere('invoice_id',$request->invoice)->first();
        }
        return view('trucking',compact('order'));
    }




    function dhl_rates(){
        
    }





}
