<table >
    <thead>
        <tr>
            <th>Date and Time</th>
            <th>Invoice No</th>
            <th>Trans. ID</th>
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
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if($row->order_id !=null): ?>
            <tr>
                <td><?php echo e(date('m/d/Y h:i', strtotime($row->created_at))); ?></td>
                <td><?php echo e($row->order->invoice_id); ?></td>
                <td><?php echo e($row->order->transaction_id); ?></td>
                <td><?php echo e($row->order->first_name.' '.$row->last_name); ?></td>
                <td> <?php echo e($row->order->phone); ?> </td>
                <td> 
                    <?php 
                        $data = 'Name: '.$row->order->first_name.' '.$row->order->last_name.', Phone:'.$row->order->phone.', ';
                        $data .=  ', Distict: '.$row->order->district;
                        $data .= ', City: '.$row->order->city;
                        $data .= ', Address:'.$row->order->address;
                    ?>
                    <?php echo e($data); ?>

                </td>
                <td>
                    <?php $product_title = \DB::table('products')->where('id',$row->product_id)->pluck('title')->first(); ?>
                    Name: <?php echo e($row->order->ship_first_name); ?> <?php echo e($row->order->ship_last_name); ?>

                    Phone: <?php echo e($row->order->ship_phone.', Email: '.$row->order->ship_email); ?>

        
                    Distict: <?php echo e($row->order->ship_district); ?>

                    City <?php echo e($row->order->ship_city); ?>

                    Address: <?php echo e($row->order->ship_address); ?>;
                </td>
                <td> <?php echo e($product_title); ?> </td>
                <td> <?php echo e($row->product->design_code); ?> </td>
                
                <td><?php echo e(\DB::table('product_combinations')->where('id',$row->product_combination_id)->pluck('barcode')->first()); ?></td>
    
                <td><?php echo e($row->qty); ?></td>
                <td><?php echo e(number_format($row->sale_price, 2)); ?></td>
                <td>
                    
                   <?php 
                        $disPercent = ((($row->sale_price - $row->discount_price) / $row->sale_price) * 100) 
                    ?> 

                    <?php echo e(number_format($disPercent, 2)); ?>

                </td>
                <td> 
                    <?php
                        if($row->promotion_id==null){
                            if($row->outlet_customer_id!=null){
                                $promoName = 'Outlet Discount';
                            }
                            $disPercent = ((($row->sale_price - $row->discount_price) / $row->sale_price) * 100);
                            if($disPercent>0){
                                $promoName = $row->product_id;
                            }
                        }else{
                            $pP = \App\Models\Product_promotion::where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();
                            $promoName = DB::table('promotions')->where('id',$row->promotion_id)->pluck('title')->first();
                            
                        }
                    ?>
                    <?php echo e($promoName); ?></td>
    
                <td>
                    <?php   
                        $disPercent = ((($row->sale_price - $row->discount_price) / $row->sale_price) * 100);
                        $discount_amount = $row->sale_price * ($disPercent / 100);

                    ?>
                    <?php echo e(number_format($discount_amount, 2)); ?>

                </td>
                
                <td><?php echo e($row->vat); ?></td>
            
                <td><?php echo e(($row->vat / 100) * $row->sale_price); ?></td>
                
                <td>
                    <?php if($row->order->zone_id !=null): ?>
                    <?php echo e($row->order->zone->delivery_cost); ?>

                    <?php endif; ?>
                </td>
                <td>
                    <?php echo e(number_format($row->sale_price - $discount_amount, 2)); ?>

                </td>
                <td><?php echo e($row->order->payment_geteway->name); ?></td>
                <td> <?php echo e($row->order->order_status->title); ?> </td>
                <td>
                    <?php 
                    $paymentSum = $row->order->order_payments()->sum('amount');
                    if($paymentSum >= $row->order->total_cost){
                        $data = 'Full paid';
                    }else $data = 'Pending';
                    ?> <?php echo e($data); ?>

                </td>
                <td>
                    <?php $courier_order_bundle_id = \DB::table('courier_company_orders')->where(['order_id'=>$row->order_id])->pluck('courier_order_bundle_id')->first();
                    if($courier_order_bundle_id !=null){
                        $courier_order = \App\Models\Courier_order_bundle::where('id',$courier_order_bundle_id)->first();
                        echo $courier_order->courier_company->name;
                    }?>
                </td>
                <td> </td>
                <td><?php echo e($row->order->customer_id); ?></td>
            </tr>
            <?php else: ?> 
                <tr> <td colspan="24"><?php echo e($row->id); ?> does not have order ID</td> </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/export/order.blade.php ENDPATH**/ ?>