

<?php $__env->startSection('title', $promotion->title); ?>

<?php $__env->startSection('content'); ?>
    <table class="table table-hover bg-white productTbl mt-5" style="width:100%">
        <thead>
            <tr><th>Image</th> <th> Product title</th><th> Design code</th>
            <th>Sale price</th> <th>discount Price</th><th>Qty</th> <th>Actions</th></tr>
        </thead>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/ad/promotion/bundle/form2.blade.php ENDPATH**/ ?>