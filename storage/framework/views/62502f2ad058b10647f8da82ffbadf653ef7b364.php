<?php $__env->startSection('title',' Dashboard | Report board'); ?>
<?php $__env->startSection('content'); ?>
<?php

    $totalOrder = \App\Models\Order::count();
    $todayOrders = App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->orderBy('created_at','desc')->get();
    $orderStatuses = App\Models\Order_status::withCount('orders')
        ->orderBy('orders_count', 'desc')->get();

    // $weeklyOrders = App\Models\Order::thisWeek()->get();
    
    $dailyAverage = \App\Models\Order::selectRaw('COUNT(*) / COUNT(DISTINCT order_date) as average_orders_per_day')
        ->value('average_orders_per_day');

?>
<div class="row">
    <?php $__currentLoopData = $orderStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            if($status->orders()->count() !=0 ){
                $statusOrder = $status->orders()->count() / $totalOrder;
            }else $statusOrder = 0;

            $retioNum = round($statusOrder * 100, 2);
        ?>
        
        <div class="col-md-4 col-xl-3">
            <div class="card Online-Order">
                <div class="card-block">
                    <a href="<?php echo e(route('common.orders',$status->id)); ?>" >
                        <h5><?php echo e($status->title); ?></h5>
                        
                        <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">Order ratio<span class="float-right f-18 
                            <?php if(strpos(strtolower($status->title), 'deliver') !== false): ?> text-success <?php endif; ?>
                            <?php if(strpos(strtolower($status->title), 'cancel') !== false || strpos(strtolower($status->title), 'return') !== false): ?> text-danger <?php endif; ?>
                            <?php if(strpos(strtolower($status->title), 'refund') !== false): ?> text-warning <?php endif; ?>
                            "><?php echo e($status->orders()->count()); ?> <b class="text-dark">of</b> <?php echo e($totalOrder); ?></span></h6>
                        <div class="progress mt-3">
                            <div class="progress-bar <?php if(strpos(strtolower($status->title), 'deliver') !== false): ?> progress-c-theme <?php endif; ?>
                                <?php if(strpos(strtolower($status->title), 'cancel') !== false ||  strpos(strtolower($status->title), 'return') !== false): ?> progress-c-red <?php endif; ?>
                                <?php if(strpos(strtolower($status->title), 'refund') !== false): ?> progress-c-yellow <?php endif; ?>
                            " role="progressbar" style="width:<?php echo e($retioNum); ?>%;height:6px;" aria-valuenow="<?php echo e($retioNum); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    
                        <span class="text-muted mt-2 d-block"> <b><?php echo e($retioNum); ?></b> % <?php echo e(str_replace('order', ' ',strtolower($status->title))); ?></span>
                    </a>
                </div>
            </div>
        </div>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="row">
    <div class="col-xl-12 col-md-12">
        <div class="card Recent-Users">
            <div class="card-header">
                <h5>Today Orders</h5>
                <div class="card-header-right">
                    <div class="btn-group card-option">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="feather icon-more-horizontal"></i> </button>

                        <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                            <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                            <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                            <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-block px-0 py-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>#</th> <th>IDs</th> <th>Product info</th> <th>Customer info</th> <th>Payment</th></tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $todayOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr> <th><?php echo e($key+1); ?></th>
                                <td>
                                    Transaction ID: <?php echo e($order->transaction_id); ?> <br>
                                    Invoice ID: <?php echo e($order->invoice_id); ?> <br>
                                    Order Status: <?php echo e($order->order_status->title); ?> <br>
                                    <a class="btn btn-sm btn-secondary pb-0 pt-0"  href="<?php echo e(route('common.order.invoice',$order->id)); ?>" target="_blank"><i class="fa fa-print"></i> View Invoice</a> <br>

                                </td>
                                <td class="text-capitalize">
                                    <?php $__currentLoopData = $order->order_items()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->product !=null): ?>
                                            <?php echo e($item->product->title); ?> <br>

                                            <?php if($item->product_variant_id !=null): ?>
                                                <?php $pv = \App\Models\Product_variant::where('id',$item->product_variant_id)->first()->toArray();
                                                unset($pv['id']);unset($pv['product_id']); unset($pv['barcode']);unset($pv['qty']); unset($pv['created_at']);unset($pv['updated_at']); ?>
                                                <ul class="ml-2">
                                                    <?php $__currentLoopData = $pv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(!empty($value)): ?>
                                                        <?php $vt = \App\Models\Variant_table::where('fk_id',$key)->first(); ?>
                                                        <li><?php echo e(str_replace('_id','',$key).': '.$vt->model::find($value)->title); ?></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php else: ?> 
                                            <p class="text-center text-danger">Order Item ID: <b><?php echo e($item->id); ?></b> relate the <b>Product ID</b> that does not exist!</p>
                                        <?php endif; ?> 
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td><?php echo e($order->first_name.' '.$order->last_name); ?> <br>
                                    Phone: <?php echo e($order->phone); ?> <?php if($order->email !=null): ?> <br> Email: <?php echo e($order->email); ?> <?php endif; ?> <br>
                                    Area: <?php echo e($order->division); ?> <i class="fa fa-arrow-right"></i>
                                     <?php echo e($order->district); ?> <i class="fa fa-arrow-right"></i> 
                                     <?php echo e($order->city); ?> <br> 
                                    Address: <?php echo e($order->address); ?>

                                </td>
                                <td>
                                    Date: <?php echo e(date('d F Y',strtotime($order->created_at))); ?> <br>
                                    <?php echo e($order->payment_geteway->name); ?> <br>
                                    Order in: <?php echo e($order->country->name); ?> <br>
                                    Order value: <?php echo e($order->country->currencySymbol); ?> <?php echo e($order->total_cost); ?> <br>
                                    Shipping charge: <?php echo e($order->country->currencySymbol); ?> <?php echo e($order->shipping_cost); ?> <br>
                                    Total amount:  <?php echo e($order->total_cost + $order->shipping_cost); ?> <br>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($todayOrders->count() <1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-danger">No <b>order</b> found <b>Today</b></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
	<!--begin::Page Scripts -->
    <script src="<?php echo e(asset('back2')); ?>/js/pages/dashboard-custom.js"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('superAdmin.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/common/includes/dashboard.blade.php ENDPATH**/ ?>