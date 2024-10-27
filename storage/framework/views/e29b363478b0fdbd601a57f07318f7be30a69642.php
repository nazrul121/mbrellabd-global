<?php $subTotal = $discount = array(); $deliveryCost = 0;
    $payments  = \DB::table('payment_gateways')->get();
    $listViewVariationId = DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
?>

<?php if($order_items->count()>0): ?>
    <?php $__currentLoopData = $order_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            if($order_item->variation_option_id !=null){
                $thumb = \DB::table('variation_option_photos')->where(['variation_id'=>$listViewVariationId,'product_id'=>$order_item->product_id,'variation_option_id'=>$order_item->variation_option_id])->pluck('thumbs')->first();
                if($thumb==null){
                    $thumbs = $order_item->product->thumbs;
                }else $thumbs = $thumb;
            }else $thumbs = url('storage/'.$order_item->product->thumbs);
        ?>
        <tr>
            <td> <img src="<?php echo e($thumbs); ?>" style="height:100px"> </td>  <td>
                <p class="p-1"><?php echo e($order_item->product->title); ?> <hr>

                <?php if($order_item->product_combination_id !=null): ?>
                    <?php $__currentLoopData = explode('~',$order_item->product_combination->combination_string); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <b class="mr-2 p-2 bg-info text-white"><?php echo e($comb); ?></b>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?> </p>
            </td>
            <td>
                Regular Price: <span style="text-decoration:line-through"><?php echo e($order_item->sale_price); ?></span> <br>
                Discount Price:<?php echo e($order_item->discount_price); ?>

            </td>
            <td> <?php echo e($order_item->qty); ?> </td>
            <td> <button class="btn btn-sm btn-danger float-right mr-0 mt-2 removeBtn" type="button" data-id="<?php echo e($order_item->id); ?>">Remove</button> </td>
        </tr>
        <?php
            $subTotal[] = $order_item->discount_price * $order_item->qty;
            $discount[] = ( $order_item->sale_price - $order_item->discount_price) * $order_item->qty;
        ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <tr class="text-right"> <td colspan="4">Sub-total:</td> <td><?php echo e(array_sum($subTotal)); ?></td> </tr>
    <tr class="text-right"> <td colspan="4">Shipping Charge: </td>
        <td style="width:30%">
            <?php if(Session::get('address')): ?>
                <?php if(array_key_exists('same', Session::get('address'))) $city_id = Session::get('address')['city'];
                    else $city_id = Session::get('address')['shipping_city'];

                    $zone = \App\Models\City_zone::where('city_id',$city_id)->first();

                    if($zone !=null) $deliveryCost =  $zone->zone->delivery_cost;
                ?>
                <?php if($zone==null): ?> <span class="text-warning">No zone selected for the customer address</span> <?php else: ?>
                    <?php echo e($zone->zone->name); ?>, cost: <?php echo e($deliveryCost); ?>

                    <input type="hidden" name="zone" value="<?php echo e($zone->zone_id); ?>">
                <?php endif; ?>
            <?php else: ?>
                <p class="alert alert-danger">Please select customer shipping and billing details</p>
            <?php endif; ?>

        </td>
    </tr>

    <tr class="text-right"> <td colspan="4">Shipping method:</td>
        <td>
            <select name="payment_method" class="form-control">
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($payment->id); ?>"><?php echo e($payment->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
    </tr>

   

    <tr class="text-right"> <td colspan="4">
        <input type="text" name="discount" value="<?php echo e(array_sum($discount)); ?>" readonly>
        <input type="text" name="total_cost" value="<?php echo e(array_sum($subTotal)); ?>" readonly>
        <input type="text" name="shippingCost" value="<?php echo e($deliveryCost); ?>" readonly>

        Grand-total:</td> <td><?php echo e(array_sum($subTotal) + $deliveryCost); ?></td> </tr>
    <tr class="text-right">
        <td colspan="4" style="vertical-align: middle;">Order Referrence <br> Order Date</td> <td>
            <select name="ref" class="form-control">
                <option value="fb">Other</option>
                <option value="fb">Facebook</option>
                <option value="website">Website</option>
                <option value="cell">Over cell phone</option>
            </select>
            <input type="date" name="order_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
        </td>
    </tr>
<?php else: ?>
    <tr class="text-center"> 
        <td colspan="5">
            <p class="p-5 alert-info text-danger">Please  <?php if(!session()->has('address')): ?> <b>Add order Shipping details</b> first. <br> Then <?php endif; ?> select products to continue</p>
        </td> 
    </tr>
    
<?php endif; ?>
<?php /**PATH /var/www/laravelapp/resources/views/common/order/create/products.blade.php ENDPATH**/ ?>