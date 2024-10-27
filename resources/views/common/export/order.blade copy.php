<table >
    <thead>
        <tr>
            <th>Date and Time</th>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Billing info</th>
            <th>Shipping info</th>
            <th>Category</th>
            <th>Design code</th>
            <th>Barcode</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Disc</th>
            <th>Disc Name</th>
            <th>Disc Amt</th>
            <th>Tax %</th>
            <th>Tax Amt</th>
            <th>Del charge</th>
            <th>Net amount</th>
            <th>Payment Method</th>
            <th>Order Status</th>
            <th>Payment Status</th>
            <th>Courier Name</th>
            <th>Delivery Date</th>
            <th>Cust. ID</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $key=>$row)
            
            @if($row->order_id !=null)
            <?php $customer = \App\Models\Customer::where('id',$row->order->customer_id)->select('id','user_id','first_name','last_name','district_id','city_id')->first();?>
            <tr>
                <td>{{date('m/d/Y h:i', strtotime($row->created_at))}}</td>
                <td>{{$row->order->invoice_id}}</td>
                <td>{{$customer->first_name.' '.$customer->last_name}}</td>
                <td>{{$customer->user->phone}}</td>
                <td> 
                <!--billing info    -->
                    <?php 
                    $data = 'Name: '.$customer->first_name.' '.$customer->last_name.', Phone:'.$customer->user->phone.', ';
                    
                    if($customer->district){ 
                        $data .=  ', Distict: ';
                        $data .=  \DB::table('districts')->where('id',$customer->district_id)->pluck('name')->first();
                    }
                    if($customer->city){ 
                        $data .= ', City:';
                        $data .=  \DB::table('cities')->where('id',$customer->district_id)->pluck('name')->first(); 
                    }
        
                    $data .= ', Address:'.$customer->address;?>
                    {{$data}}
                </td>
                <td>
                    <!--shipping info -->
                    <?php 
                    $product_title = \DB::table('products')->where('id',$row->product_id)->pluck('title')->first();
                    $shipping = $row->order->shipping_address;
                    
                    $data = 'Name: '.$shipping->fname.' '.$shipping->lname;
                    $data .= ', Phone: '.$shipping->phone.', Email: '.$shipping->email;
        
                    if($shipping->district){ $data .=  ', Distict: '.$shipping->district->name;}
                    if($shipping->city){ $data .= ', City: '.$shipping->city->name; }
                    $data .= ', Address:'.str_replace('&',', ',$shipping->address);
                    ?> {{$data}}
                </td>
                <td> {{ $product_title }} </td>
                <td> {{ $row->product->design_code }} </td>
                <!--barcode-->
                <td>{{\DB::table('product_combinations')->where('id',$row->product_combination_id)->pluck('barcode')->first()}}</td>
    
    
                <td>{{$row->qty}}</td>
                <td>{{$row->sale_price}}</td>
                <td>
                   <?php 
                    
                    // if($row->promotion_id==null){
                    //     if($row->outlet_percent!=null) {
                    //         $discountPercent = $row->outlet_percent;
                    //         $promoName = 'outlet Discount';
                    //     }else{
                    //         $discountPercent =  0;
                    //         $promoName = '';
                    //     }
                    // }else{
                    //    $pP = \App\Models\Product_promotion::where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();

                    //    if($pP->discount_in=='percent') $discountPercent = $pP->discount_value;
                    //    else $discountPercent = (($row->sale_price - $row->discount_price)*100) /$row->sale_price;
                    //    $promoName = \DB::table('promotions')->where('id',$row->promotion_id)->pluck('title')->first();
                    // } 

                    $promoName = '';
                    $discountPercent =0;
                    ?> 

                    {{$discountPercent}}
                </td>
                <td>{{$promoName}}</td>
    
                <td>
                    <!--discount amount-->
                    <?php   
                    if($row->promotion_id==null){
                        if($row->outlet_customer_id!=null){
                            $discountPercent = $row->outlet_percent;
                        }else $discountPercent = 0;
                    }
                    else{
                       $pP = \App\Models\Product_promotion::where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();
                       if($pP->discount_in=='percent') $discountPercent = $pP->discount_value;
                       else $discountPercent = ($pP->discount_value / 100) * $row->sale_price;
                    } 
                    $amount = ($row->sale_price/100)*$discountPercent;
                    echo  number_format($amount, 2);
                    
                    ?>
                </td>
                
                <td>{{ $row->vat }}</td>
                <!--vat aount-->
                <td>{{ ($row->vat / 100) * $row->sale_price }}</td>
                
                <td>
                    @if($row->order->zone_id !=null)
                    {{ $row->order->zone->delivery_cost}}
                    @endif
                </td>
                <td>{{$row->net_price}}</td>
                <td>{{ $row->order->payment_geteway->name }}</td>
                <td> {{ $row->order->order_status->title }} </td>
                <td>
                    <?php 
                    $paymentSum = $row->order->order_payments()->sum('amount');
                    if($paymentSum >= $row->order->total_cost){
                        $data = 'Full paid';
                    }else $data = 'Pending';
                    ?> {{$data}}
                </td>
                <td>
                    <?php $courier_order_bundle_id = \DB::table('courier_company_orders')->where(['order_id'=>$row->order_id])->pluck('courier_order_bundle_id')->first();
                    if($courier_order_bundle_id !=null){
                        $courier_order = \App\Models\Courier_order_bundle::where('id',$courier_order_bundle_id)->first();
                        echo $courier_order->courier_company->name;
                    }?>
                </td>
                <td></td>
                <td>{{ $customer->id }}</td>
            </tr>
            @else 
                <tr> <td>{{$row->id}} does not have order ID</td> </tr>
            @endif
        @endforeach
    </tbody>
</table>

<style>
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}
</style>

