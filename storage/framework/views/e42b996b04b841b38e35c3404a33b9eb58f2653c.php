


<?php $__env->startSection('title','SSLCOMMERZ orders'); ?>

<?php
    $color = '';
    $succesItems = 0;
    $succesAmount = $succesShippingCost = [];
?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header row">
                    <div class="col-md-6">
                        <h5>Online <b>Payments</b></h5>
                        Total of <b><?php echo e($orderPayments->count()); ?></b> records
                    </div>
                    <div class="col-md-6 text-md-right">
                        <select name="status" style="padding:9px;" class="border border-info">
                            <option value="">All status</option>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->status); ?>"<?php if(request()->get('status')==$item->status): ?>selected <?php endif; ?> ><?php echo e($item->status); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <a href="<?php echo e(route('common.sslcommerz-excel')); ?>?status=<?php echo e(request()->get('status')); ?>" target="_blank" class="btn btn-info excelBtn"><b class="fas fa-file-excel"></b> Excel</a>
                    </div>
                   
                   
                </div>
        
                <div class="card-body">
                    <div class="row">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Trans. ID</th>
                                        <th scope="col">Total items</th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col" class="text-center">Amount</th>
                                        <th scope="col" class="text-center">Del. cost</th>
                                        <th class="text-md-center">Free del.</th>
                                        <th class="text-md-right">Total</th>
                                        <th scope="col" class="text-center">Date</th>
                                        <th scope="col" class="text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orderPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php 
                                            if (strtolower($item->status)=='cancelled' || strtolower($item->status)=='failed' || strtolower($item->status)=='rejected'){
                                                $color = 'text-danger';
                                            }
                                            
                                            if (strtolower($item->status)=='valid' || strtolower($item->status)=='success' || strtolower($item->status)=='accepted'){
                                                $color = 'text-success';
                                                
                                                if($item->order !=null){
                                                    $succesAmount[] = $item->amount;
                                                    $succesShippingCost[] = $item->order->shipping_cost;
                                                    $succesItems += 1;
                                                }
                                                
                                            }
                                            $payerInfo = json_decode($item->payer_info, true); 
                                            if ($payerInfo && isset($payerInfo['card_type'])) {
                                                $bankType =  $payerInfo['card_type'];
                                            } else {
                                                $bankType = '';
                                            }
                                            // dd($payerInfo['card_type']);

                                            // if($item->order->order_status_id) dd($item->order);

                                        ?>


                                        <?php if($item->order !=null && $item->order->order_status_id !==10): ?>
                                            <tr>
                                                <th scope="row"> <?php echo e($key+1); ?></th>
                                                <td><?php echo e($item->order->transaction_id); ?> </td>
                                                <td><b><?php echo e($item->order->order_items()->count()); ?></b></td>
                                                <td><?php echo e($item->payment_type->title); ?> - <?php echo e($bankType); ?> </td>
                                                <td class="text-center"><?php echo e($item->order->total_cost); ?></td>                                      
                                                <td ><?php echo e($item->order->shipping_cost); ?></td>
                                                <td class="text-center"> <?php if($item->order->invoice_discount!=0.00): ?> Free delivery <?php endif; ?></td>
                                        
                                                <td class="text-right"><?php echo e($item->amount); ?></td>
                                                <td class="text-center"> <?php echo e(date('d M, Y: h:ia',strtotime($item->created_at))); ?> </td>
                                                <td class="text-right <?php echo e($color); ?>"> <?php echo e($item->status); ?> </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-md-right">
                                        <td colspan="4">Total successful order Items: <b><?php echo e($succesItems); ?></b></td>
                                        <td colspan="4">Total Success transaction Amount: <b><?php echo e(array_sum($succesAmount) + array_sum($succesShippingCost)); ?></b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function () {
            $('[name=status]').on('change', function(){
                var status = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('status',status);
                window.location.href = url.href;
            })
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/order/sslcommerz-order.blade.php ENDPATH**/ ?>