<?php $__env->startSection('title', 'My orders'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-left">
                <thead  class="bg-light">
                    <tr>
                        <th>#</th><th>Date</th><th>Transaction ID</th>
                        <th>Pricing</th>
                        <th class="text-right">More</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e(date('M d, y H;ia',strtotime($order->created_at))); ?></td>
                        <td><?php echo e($order->transaction_id); ?></td>
                        <td><?php echo e($order->country->currencySymbol); ?>

                            <?php if($order->shippingCostFrom=='zone'): ?>
                                <?php echo e($order->total_cost + $order->zone->delivery_cost); ?>

                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                                <div class="collection-sorting position-relative">
                                    <b class="float-end"> ....</b>
                                    <ul class="sorting-lists list-unstyled m-0">
                                        <li class="more" data-id="<?php echo e($order->id); ?>"><a href="javaScript:;"> <i class="fa fa-print text-info"></i> More</button></a></li>
                                        <li class="payment" data-id="<?php echo e($order->id); ?>"><a href="javaScript:;"><i class="fas fa-money-check-alt text-warning"></i> Payment info</a></li>

                                        <li><a href="/print-invoice/<?php echo e($order->transaction_id); ?>" target="_blank"> <i class="fa fa-print text-success"></i> Invoice</a></li>
                                        <li><a href="/truck?invoice=<?php echo e($order->transaction_id); ?>" target="_blank"> <i class="fa fa-truck text-secondary"></i> Track</a></li>
                                    </ul>
                                </div>
                            
                        </td>
                    </tr>
                    <tr class="details order<?php echo e($order->id); ?>" style="display:none;">
                        <td colspan="7">
                            <table class="table bg-5 table-bordered text-left">
                                <tr>
                                    <th>Product Info</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                                <?php $__currentLoopData = $order->order_items()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$orderItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($orderItem->product_variant_id !=null){
                                        $thumb = \DB::table('color_product')->where(['product_id'=>$orderItem->product_id,'color_id'=>$orderItem->product_variant->color_id])->pluck('thumbs')->first();
                                    }else $thumb = $orderItem->product->thumbs;?>

                                    <tr>
                                        <td><img src="<?php echo e($thumb); ?>" style="width:30px;float:left"> &nbsp;  <?php echo e($orderItem->product->title); ?> <br>
                                            <small><?php if($orderItem->product_variant_id !=null): ?>
                                                <?php  $pv = \App\Models\Product_variant::where('id',$orderItem->product_variant_id)->first()->toArray();
                                                unset($pv['id']);unset($pv['product_id']); unset($pv['barcode']);unset($pv['qty']); unset($pv['created_at']);unset($pv['updated_at']); ?>
                                                <?php $__currentLoopData = $pv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(!empty($value)): ?>
                                                        <?php $vt = \App\Models\Variant_table::where('fk_id',$key)->first();?>
                                                    &nbsp; <b class="text-capitalize"><?php echo e(str_replace('_id','',$key)); ?>: </b> <?php echo e($vt->model::find($value)->title); ?>,
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?></small>
                                        </td>
                                        <td><?php echo e($order->country->currencySymbol); ?> <?php echo e($orderItem->product->sale_price * $order->country->currencyValue); ?></td>
                                        <td> <?php echo e($orderItem->qty); ?></td>
                                        <th><?php echo e($orderItem->product->sale_price * $order->country->currencyValue * $orderItem->qty); ?></th>
                                        <?php $total[] = $orderItem->product->sale_price * $order->country->currencyValue * $orderItem->qty; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </td>
                    </tr>
                    <tr class="bg-5 payment<?php echo e($order->id); ?>" style="display:none;">
                        <td colspan="7">
                            <table class="table table-bordered text-left">
                                <tr>
                                    <th>Payment via</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                                <?php $__currentLoopData = $order->order_payments()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($payment->payment_type->title); ?></td>
                                        <td><?php echo e($payment->amount); ?></td>
                                        <td><?php echo e($payment->status); ?></td>
                                        <td><?php echo e(date('M d, y, H:ia',strtotime($payment->created_at))); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($order->order_payments()->get()->count() <1): ?>
                                    <tr class="text-center text-danger"><td colspan="4">No payment found</td></tr>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($orders->count()<1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-danger"> No match found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="text-center mt-4">
                    <?php echo e($orders->links()); ?>

                </ul>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(function(){
            $('.more').on('click',function(){
               let id = $(this).data('id');
               $( '.order'+id).toggle('slow');
            })

            $('.payment').on('click',function(){
               let id = $(this).data('id');
               $( '.payment'+id).toggle('slow');
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('customer.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/customer/orders.blade.php ENDPATH**/ ?>