<?php $__env->startSection('content'); ?>

<?php 
    $metas = \DB::table('metas')->where('pageFor','order-placed');
    $meta = \DB::table('metas')->where(['pageFor'=>'order-placed', 'type'=>'title']);

    $metaTitle = 'Mbrella | Order placed';
    if($meta->count() >0){
        $metaTitle = $metas->pluck('description')->first();
    }

    $validStatuses = ['ACCEPTED', 'VALID', 'SUCCESS'];

    // dd($payment->status, in_array($payment->status, $validStatuses));
?>

<?php $__env->startSection('title',$metaTitle); ?>

<?php $__env->startPush('meta'); ?>
    <?php $__currentLoopData = $metas->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <meta type="<?php echo e($meta->title); ?>" content="<?php echo e($meta->description); ?>">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopPush(); ?>


<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" />
                    </g>
                </svg>
            </li>
            <li><a href="<?php echo e(url('checkout')); ?>">Checkout</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"  xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>
            <li>Order Placed</li>
        </ul>
    </div>
</div>
<?php $subtotal = $vats = array();

    $totalInLocal = $order->total_cost;
    $shippingInLocal = $order->shipping_cost;
    $total = $totalInUSD = $shippingInUSD = 0;

    $orderInDollar = DB::table('dollar_rate_order')->where('order_id',$order->id)->first();
    if($orderInDollar !=null && $shippingInLocal >0 && $totalInLocal>0){
        $shippingInUSD = number_format( $shippingInLocal /$orderInDollar->value , 2);
        $totalInUSD = number_format($totalInLocal/$orderInDollar->value , 2);
    }

?>


<div class="wishlist-page mt-100">
    <div class="wishlist-page-inner">
        <div class="container">
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 aos-init aos-animate mb-5" data-aos="fade-up" data-aos-duration="700">
                  
                    <div class="bg-white mt-3 p-3" style="border:1px solid silver">
                        <?php if($payment==null): ?>
                            <p class="alert alert-warning  p-3"><i class="fa fa-info-circle fa-lg"></i> You don`t a successful payment <span class="text-info">but we took your order.</span></p>
                        <?php else: ?>
                            <?php if(in_array($payment->status, $validStatuses)==false): ?>
                              <p class="alert alert-warning  p-3"><i class="fa fa-info-circle fa-lg"></i> You don`t a successful payment <span class="text-info">but we took your order.</span></p>
                            <?php endif; ?>
                        <?php endif; ?>
                        <p class="alert alert-info p-3"> <i class="fa fa-check-square fa-lg"></i> Thank you. Your order has been placed successfully.</p>

                     
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order Number </th>
                                    <th>Order Date </th>
                                    <th>Order Total </th>
                                    <th>Payment method </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo e($order->transaction_id); ?> </td>
                                    <td><?php echo e(date('F j, Y g:ma',strtotime($order->created_at))); ?> </td>
                                    <td><?php echo e($order->country->currencySymbol); ?><?php echo e($order->total_cost + $order->shipping_cost); ?></td>
                                    <td><?php echo $order->payment_geteway->name; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4"> <?php echo $order->payment_geteway->description; ?> </td>
                                </tr>
                            </tbody>
                        </table>
                   

                        <table class="table table-info bg-5 mb-0">
                            <h3 class="pt-3" >Order details</h3>
                            <table class="table table-hover bg-white">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $order->order_items()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($order_item->vat_type=='excluding'){
                                            $vat = ($order_item->vat / 100) *  $order_item->discount_price * $order_item->qty;
                                        }else $vat = null; ?>
                                        <tr>
                                            <td>
                                                <img height="40" src="<?php echo e($order_item->product->thumbs); ?>" alt="img">
                                                <a href="<?php echo e(route('product',[app()->getLocale(), $order_item->product->slug])); ?>" target="_blank"><?php echo e($order_item->product->title); ?></a>
                                                <small>
                                                    <?php if($order_item->product_combination_id !=null): ?>
                                                        <?php $__currentLoopData = $order_item->product_combination()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pComb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php $__currentLoopData = explode('~',$pComb->combination_string); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $string): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                                                <b class="p-2"> <?php echo e($v->variation->title.': '.$v->title); ?> </b>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </small>
                                                <strong> <?php echo e($order->country->currencySymbol); ?> <?php echo e($order_item->discount_price); ?></strong>
                                                <strong >×&nbsp;<?php echo e($order_item->qty); ?></strong>
                                        	</td>
                                            <td class="text-end">
                                                <?php echo e($order->country->currencySymbol); ?></span> <?php echo e($order_item->discount_price * $order_item->qty); ?>

                                        	</td>
                                        </tr>
                                        <?php $subtotal[] = $order_item->discount_price * $order_item->qty;
                                            $vats[] = $vat * $order_item->qty;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>

                                <tfoot class="text-end">
                                    <tr>
                                        <th>Subtotal:</th>
                                        <td><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(array_sum($subtotal)); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Shipping:
                                            <?php if($order->country_id ==2): ?>
                                                <?php if($order->zone==null): ?> <span class="text-warning">not defined</span>
                                                <?php else: ?> <?php echo e($order->zone->title); ?>  <?php endif; ?>
                                            <?php else: ?>
                                                <?php echo e($order->shippingCostFrom); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($order->country->currencySymbol .' '. $order->shipping_cost); ?></td>
                                    </tr>
                                    <?php if($order->invoice_discount >0): ?>
                                    <tr>
                                        <td>Invoice Discount</td>
                                        <td><?php echo e($order->country->currencySymbol .' '. round($order->invoice_discount , 2)); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th scope="row">Total:</th>
                                        <td><?php echo e($order->country->currencySymbol.' '.round( (array_sum($subtotal) + $order->shipping_cost) - $order->invoice_discount, 2)); ?></td>
                                    </tr>
                                    <?php if($order->country_id !=2): ?>
                                        <tr style="font-weight: bold">
                                            <td> Total In USD: </td>
                                            <td> <span> $</span> <?php echo e($totalInUSD + $shippingInUSD); ?> </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if($order->note !=null): ?>
                                    <tr>
                                        <th>Note:</th>
                                        <td><?php echo e($order->note); ?></td>
                                    </tr> <?php endif; ?>
                                </tfoot>
                            </table>

                        </section>

                        <a href="<?php echo e(url('print-invoice').'/'.$order->transaction_id); ?>" class="mt-2 p-2 btn-warning float-end"><i class="fa fa-print"></i> &nbsp; Print invoice</a>
                        
                    </div>
                
                </div>
                
            </div>
        </div>
    </div>            
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/order-placed.blade.php ENDPATH**/ ?>