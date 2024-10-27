


<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Customers orders</h5>
                <div class="card-header-right">
                    <form class="form-inline"><?php echo csrf_field(); ?>
                        <div class="form-group mb-2">
                            <label for="staticEmail2" class="sr-only">Customer Name / Phone</label>
                            <input type="text" readonly="" class="form-control-plaintext" id="staticEmail2" value="Customer Name / Phone">
                        </div>
                         <div class="form-group mx-sm-3 mb-2">
                        <label class="sr-only">info</label>
                        <input type="text" class="form-control" name="customer" placeholder="phone / Name" value="<?php echo e(request()->get('customer')); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Search</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <?php if($customer !=null): ?>
                    <div class="row">
                        <div class="col-md-6 col-xl-4">
                            <div class="card project-task">
                                <div class="card-block">
                                    <div class="row align-items-center justify-content-center">
                                        <img src="<?php echo e(url('storage/'.$customer->photo)); ?>" alt="<?php echo e($customer->first_name); ?>">
                                    </div>
                                    <div class="card-header">
                                        <h5><?php echo e($customer->first_name.' '.$customer->last_name); ?> ( <?php echo e($customer->phone); ?> )</h5>
                                        <div class="card-header-right">
                                            <div class="btn-group card-option show"> Balance: <?php echo e($customer->balance); ?> </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="mt-3 mb-0 text-center text-muted">The customer has <b><?php echo e($customer->orders()->count()); ?></b> <small>Orders</small></h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-8">
                            <div class="card project-task">
                                <div class="card-block">
                                    <?php if($customer->orders()->count() <1): ?>
                                        <p class="text-center text-danger bg-light p-5">The customer has <b>No</b> Orders yet.</p>
                                    <?php else: ?> 
                                        <div class="responsive-table" style="height:80vh;overflow-y:overlay;">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr class="bg-light">
                                                        <td>#</td>
                                                        <td>Date</td>
                                                        <td>Invoice No</td>
                                                        <td>item Qty</td>
                                                        <td>Price</td>
                                                        <td>Status</td>
                                                        <td>More</td>
                                                    </tr>
                                                    <?php $__currentLoopData = $customer->orders()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($key+1); ?></td>
                                                            <td><?php echo e(date('d F, Y',strtotime($order->order_date))); ?></td>
                                                            <td><?php echo e($order->transaction_id); ?></td>
                                                            <td><?php echo e($order->total_items); ?></td>
                                                            <td><?php echo e($order->total_cost); ?></td>
                                                            <td><?php echo e($order->order_status->title); ?></td>
                                                            <td>
                                                                <a href="<?php echo e(route('common.order.invoice',$order->id)); ?>" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Print</a>
                                                                <a href="<?php echo e(route('common.edit-order',$order->id)); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </thead>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if(request()->get('customer') && $customer==null): ?>
                    <p class="text-center text-danger bg-light p-5">No Data found with given <b>phone</b> No.</p>
                <?php endif; ?>     
                
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/common/user/customer/orders.blade.php ENDPATH**/ ?>