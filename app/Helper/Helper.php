<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function check_access($permission_origin){
    if(Auth::user()->user_type_id ==3){
        $permission_id = DB::table('permissions')->where('origin',$permission_origin)->pluck('id')->first();
        if(request()->get('permissionType')=='role-base'){
            $isPermitted = DB::table('permission_user_type')->where(['user_type_id'=>Auth::user()->user_type_id,'permission_id'=>$permission_id]);
        }else{
            $isPermitted = DB::table('permission_user')->where(['user_id'=>Auth::user()->id,'permission_id'=>$permission_id]);
        }
        // dd($isPermitted->get());
        if($permission_id !=null && $isPermitted->count() >0) return true;
        return false;
    }else return true;
}

function is_label_has_nay_permissions($label_id){
    if(Auth::user()->user_type_id ==1 || Auth::user()->user_type_id ==2) return true;
    
    if(request()->get('permissionType')=='role-base'){
        $num =  DB::table('permission_user_type')->where('permission_label_id',$label_id)->count();
    }else{
        $num =  DB::table('permission_user')->where('permission_label_id',$label_id)->count();
    }
    if($num >0) return true; else return false;
}


function product_price($id,$price){
    $p = DB::table('product_promotion')->where(['product_id'=>$id, 'status'=>'1'])->first();
    $newPrice = 0;
    // dd(\Session::get('user_currency')->name);
    if(strtolower(session()->get('user_currency')->short_name)=='bgd'){
        if($p==null) return $price;
        else return $p->discount_price;
    }else{
        if($p==null) $newPrice = $price;
        else $newPrice += $p->discount_price;
        
        return $newPrice / session()->get('user_currency')->currencyValue;
    }
}

function old_price($id,$price){
    
    if(strtolower(session()->get('user_currency')->short_name)=='bgd'){
        return $price;
    }
    else{
        return $price / session()->get('user_currency')->currencyValue;
        // $nature = session()->get('user_currency')->nature;
        // if($nature=='multiply')return $price * session()->get('user_currency')->displayCurrency;
        // if($nature=='divide') return $price / session()->get('user_currency')->displayCurrency;
    }
}

function discount_percent($id,$price){
    $nowPrice = product_price($id, $price);
    $old_price = old_price($id, $price);
    return $old_price.' = '.$nowPrice;
    $percentChange = (1 - $old_price / $nowPrice) * 100;

    return $percentChange;
}

// max price of products table
function max_price($maxPrice){
    if(strtolower(session()->get('user_currency')->short_name)=='bgd'){
        return $maxPrice;
    }else{
        $nature = session()->get('user_currency')->nature;
        return $maxPrice / session()->get('user_currency')->currencyValue;
       
    }
}


function invoice_discount($invoiceDiscount, $subtotal){
    // return $invoiceDiscount;
    if($invoiceDiscount->discount_in=='percent'){
        $percentage = $invoiceDiscount->discount_value;
        $discount = ($percentage / 100) *$subtotal;
        $price = $discount; //$subtotal - ($subtotal * ($percentage/100));
    }else $price = $invoiceDiscount->discount_value;

    if(strtolower(session()->get('user_currency')->short_name)=='bgd'){
        return $price;
    }else{
        $nature = session()->get('user_currency')->nature;
        if($nature=='multiply') return $price * session()->get('user_currency')->displayCurrency;
        if($nature=='divide') return $price / session()->get('user_currency')->displayCurrency;
    }
}

function is_cartlist_belongsTo_promotion(){
    foreach(session()->get('cart') as $key=>$cart){
        $check = DB::table('product_promotion')->where(['product_id'=>$cart->product_id,'status'=>'1']);
        if($check->count()>0) return true;
    } return false;
}




// bundle price
function bundle_price($id,$field){
    $bundle = DB::table('bundle_promotions')->where(['id'=>$id])->first();
    $newPrice = '';
    // dd(\Session::get('user_currency')->name);
    if(strtolower(session()->get('user_currency')->short_name)=='bdg'){
        return $bundle->$field;
    }else{
        $newPrice = $bundle->$field;
        $nature = session()->get('user_currency')->nature;
        if($nature=='multiply') return $newPrice * session()->get('user_currency')->displayCurrency;
        if($nature=='divide') return $newPrice / session()->get('user_currency')->displayCurrency;
    }
}



function is_stock_out($product_id){
    $qty = DB::table('products')->where(['id'=>$product_id])->pluck('qty')->first();
    if($qty < 1) return true;
    else return false;
}

function outlet_customer_discount($outletCustomer){
    // return $outletCustomer->percent;
    foreach(session()->get('cart') as $cart){
        $dis_amount[] = 0;
        $promotion = DB::table('product_promotion')->where(['product_id'=>$cart->product_id,'status'=>'1'])->first();
        if($promotion ==null) {
            $dis_amount[]= ($outletCustomer->percent / 100) * product_price($cart->product_id, $cart->product->sale_price) * $cart->qty; 
        }
    }
    return array_sum($dis_amount);
}


function months(){
    $months = [
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    ]; return $months;
}

function years(){
    $currentYear = date("Y");
    $endYear = $currentYear;
    $years = [];
    for($year = 2022; $year <= $endYear; $year++){
        $years[] = $year;
    }
    return $years;
}

function get_currency(){
    return DB::table('countries')->select(['id','name','short_name','currency_code','flag','currencySymbol','currencyValue','zone'])->where('status','1')->get();
}

function deliveryCharge($delivery_cost){
    if(strtolower(session()->get('user_currency')->short_name)=='bgd'){
        return $delivery_cost;
    }else{
        return $delivery_cost / session()->get('user_currency')->paymentCurrency;
    }
}


function now_currency(){
    $sesValue = session()->get('user_currency');
    return $sesValue;
}

