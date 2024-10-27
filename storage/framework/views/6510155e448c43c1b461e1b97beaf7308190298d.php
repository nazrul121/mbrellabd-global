<P class="alert alert-info">Billing information</P>

<div class="form-group row mt-3">
    <label for="staticEmail" class="col-sm-2 col-form-label">Email - (optional)</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="email" value="<?php echo e(old('email')); ?>">
    </div>
    <div class="col-2">
        <button type="button" class="btn btn-success checkBilling" data-field="email">Check</button>
    </div>
</div>

<div class="form-group row mt-3">
    <label for="staticEmail" class="col-sm-2 col-form-label">Phone <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" value="<?php echo e(old('phone')); ?>" name="phone" required>
    </div>
    <div class="col-2">
        <button type="button" class="btn btn-success checkBilling" data-field="phone">Check</button>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-form-label col-md-4">First Name <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="fname" class="form-control" value="<?php echo e(old('fname')); ?>" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">

            <label class="col-form-label col-md-4">Last Name <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="lname" class="form-control" value="<?php echo e(old('lname')); ?>"  required>
            </div>
        </div>
    </div>
</div>



<div class="form-group row mt-4 mb-2">
    <label for="staticEmail" class="col-sm-2 col-form-label text-md-star text-md-end">Country <span class="required">*</span></label>
    <div class="col-sm-4">
        <select name="country" class="form-control" required>
            <option value="1">Bangladesh</option>
        </select>
    </div>
    <label for="staticEmail" class="col-sm-2 col-form-label text-md-star text-md-end">Divisioin <span class="required">*</span></label>
    <div class="col-sm-4">
        <select name="division" class="form-control" required>
            <option value="">Choose</option>
            <?php $__currentLoopData = \DB::table('divisions')->where(['status'=>'1', 'country_id'=>'2'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(old('division')==$div->id): ?>selected <?php endif; ?> value="<?php echo e($div->id.'|'.$div->name); ?>"><?php echo e($div->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
       <div class="row">
        <label for="staticEmail" class="col-sm-4 col-form-label text-md-star text-md-end">District <span class="text-danger">*</span></label>
        <div class="form-group col-sm-8">
            <select name="district" class="form-control" required>
                <option value="">Choose</option>

            </select>
        </div>
       </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <label for="staticEmail" class="col-sm-4 col-form-label text-md-star text-md-end">Cities <span class="text-danger">*</span></label>
            <div class="form-group col-sm-8">
                <select name="city" class="form-control" required>
                    <option value="">Choose</option>

                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-group mt-3">
    <div class="row">
        <label for="staticEmail" class="col-sm-2 col-form-label text-md-star text-md-end">Address <span class="text-danger">*</span></label>
        <div class="form-group col-sm-10">
            <textarea name="address" class="form-control" rows="2" required><?php echo e(old('address')); ?></textarea>
        </div>
    </div>
</div>

<label for="isSame">
    <input type="checkbox" name="same" id="isSame" checked> Is biling and shipping are same Address?
</label>
<?php /**PATH /var/www/laravelapp/resources/views/common/order/create/billing-address.blade.php ENDPATH**/ ?>