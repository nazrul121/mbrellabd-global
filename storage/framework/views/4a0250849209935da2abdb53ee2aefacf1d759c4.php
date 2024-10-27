<div class="row">
    <div class="col-md-6">
        <label for="recipient-name" class="col-form-label">Payment Type</label>
        <?php $paymentTypes = \App\Models\Payment_type::where('status','1')->get();?>
        <select class="form-control" name="payment_type" required>
            <option value="">Choose Type</option>
            <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  <option value="<?php echo e($type->id); ?>"><?php echo e($type->title); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="recipient-name" class="col-form-label">Amount</label>
        <input type="text" class="form-control" name="amount">
    </div>
</div>

<?php /**PATH /var/www/laravelapp/resources/views/common/order/include/payment-form.blade.php ENDPATH**/ ?>