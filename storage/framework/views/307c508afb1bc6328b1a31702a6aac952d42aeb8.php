
Name: <?php echo e($order->ship_first_name.' '.$order->ship_last_name); ?> <br>

Phone: <?php echo e($order->ship_phone); ?> <br>
<?php if($order->ship_email !=null): ?>
    Email: <?php echo e($order->ship_email); ?> <br>
<?php endif; ?>

Area: <?php echo e($order->division); ?> <i class="fa fa-arrow-right"></i> <?php echo e($order->district); ?> <i class="fa fa-arrow-right"></i>
 <?php echo e($order->city); ?> <br>

Address: <?php echo e($order->address); ?>

<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/order/include/shipping-address.blade.php ENDPATH**/ ?>