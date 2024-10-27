<?php $subTotal = $discount = array(); $deliveryCost = 0;
    $payments  = \DB::table('payment_gateways')->get();
    $listViewVariationId = DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
?>

@if($order_items->count()>0)
    @foreach ($order_items as $order_item)
        <?php
            if($order_item->variation_option_id !=null){
                $thumb = \DB::table('variation_option_photos')->where(['variation_id'=>$listViewVariationId,'product_id'=>$order_item->product_id,'variation_option_id'=>$order_item->variation_option_id])->pluck('thumbs')->first();
                if($thumb==null){
                    $thumbs = $order_item->product->thumbs;
                }else $thumbs = $thumb;
            }else $thumbs = url('storage/'.$order_item->product->thumbs);
        ?>
        <tr>
            <td> <img src="{{ $thumbs }}" style="height:100px"> </td>  <td>
                <p class="p-1">{{$order_item->product->title}} <hr>

                @if($order_item->product_combination_id !=null)
                    @foreach( explode('~',$order_item->product_combination->combination_string) as $comb)
                        <b class="mr-2 p-2 bg-info text-white">{{ $comb }}</b>
                    @endforeach
                @endif </p>
            </td>
            <td>
                Regular Price: <span style="text-decoration:line-through">{{ $order_item->sale_price }}</span> <br>
                Discount Price:{{ $order_item->discount_price }}
            </td>
            <td> {{$order_item->qty}} </td>
            <td> <button class="btn btn-sm btn-danger float-right mr-0 mt-2 removeBtn" type="button" data-id="{{ $order_item->id }}">Remove</button> </td>
        </tr>
        <?php
            $subTotal[] = $order_item->discount_price * $order_item->qty;
            $discount[] = ( $order_item->sale_price - $order_item->discount_price) * $order_item->qty;
        ?>
    @endforeach

    <tr class="text-right"> <td colspan="4">Sub-total:</td> <td>{{ array_sum($subTotal) }}</td> </tr>
    <tr class="text-right"> <td colspan="4">Shipping Charge: </td>
        <td style="width:30%">
            @if(Session::get('address'))
                <?php if(array_key_exists('same', Session::get('address'))) $city_id = Session::get('address')['city'];
                    else $city_id = Session::get('address')['shipping_city'];

                    $zone = \App\Models\City_zone::where('city_id',$city_id)->first();

                    if($zone !=null) $deliveryCost =  $zone->zone->delivery_cost;
                ?>
                @if($zone==null) <span class="text-warning">No zone selected for the customer address</span> @else
                    {{ $zone->zone->name }}, cost: {{$deliveryCost}}
                    <input type="hidden" name="zone" value="{{ $zone->zone_id }}">
                @endif
            @else
                <p class="alert alert-danger">Please select customer shipping and billing details</p>
            @endif

        </td>
    </tr>

    <tr class="text-right"> <td colspan="4">Shipping method:</td>
        <td>
            <select name="payment_method" class="form-control">
                @foreach ($payments as $payment)
                    <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                @endforeach
            </select>
        </td>
    </tr>

   

    <tr class="text-right"> <td colspan="4">
        <input type="text" name="discount" value="{{ array_sum($discount) }}" readonly>
        <input type="text" name="total_cost" value="{{ array_sum($subTotal) }}" readonly>
        <input type="text" name="shippingCost" value="{{ $deliveryCost }}" readonly>

        Grand-total:</td> <td>{{ array_sum($subTotal) + $deliveryCost }}</td> </tr>
    <tr class="text-right">
        <td colspan="4" style="vertical-align: middle;">Order Referrence <br> Order Date</td> <td>
            <select name="ref" class="form-control">
                <option value="fb">Other</option>
                <option value="fb">Facebook</option>
                <option value="website">Website</option>
                <option value="cell">Over cell phone</option>
            </select>
            <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}">
        </td>
    </tr>
@else
    <tr class="text-center"> 
        <td colspan="5">
            <p class="p-5 alert-info text-danger">Please  @if(!session()->has('address')) <b>Add order Shipping details</b> first. <br> Then @endif select products to continue</p>
        </td> 
    </tr>
    
@endif
