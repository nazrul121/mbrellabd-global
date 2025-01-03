<div class="row">
    <div class="col-md-6">
        <div class="form-group row text-right">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label">First Name <span class="required">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="fname" class="form-control" id="exampleFormControlInput1" value="<?php echo e(Auth::user()->customer->first_name); ?>">
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row text-right">
            <label for="exampleFormControlInput2" class="col-sm-3 col-form-label">Last Name <span class="required">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="lname" class="form-control" id="exampleFormControlInput2" value="<?php echo e(Auth::user()->customer->last_name); ?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group row mt-3 text-right">
    <label for="staticEmail" class="col-sm-3 col-form-label">Email - optional</label>
    <div class="col-sm-9">
    <input type="email" class="form-control" name="email" value="<?php echo e(Auth::user()->email); ?>">
    </div>
</div>

<div class="form-group row mt-3 text-right">
    <label for="staticEmail" class="col-sm-2 col-form-label">Phone <span class="required">*</span></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="phone" value="<?php echo e(Auth::user()->customer->phone); ?>">
    </div>
</div>


<div class="form-group row mt-4 mb-2">
    <label for="staticEmail" class="col-sm-2 col-form-label text-right">Country <span class="required">*</span></label>
    <div class="col-sm-4">
        <select name="country" class="form-control">
            <option value="1">Bangladesh</option>
        </select>
    </div>
    <label for="staticEmail" class="col-sm-2 col-form-label text-right">Divisioin <span class="required">*</span></label>
    <div class="col-sm-4">
        <select name="division" class="form-control">
            <option value="">Choose</option>
            <?php $__currentLoopData = \DB::table('divisions')->where('status','1')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(Auth::user()->customer->division_id==$div->id): ?>selected <?php endif; ?> value="<?php echo e($div->id); ?>"><?php echo e($div->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="row">
            <label for="staticEmail" class="col-sm-4 col-form-label text-right">Choose District <span class="required">*</span></label>
            <div class="form-group col-sm-8">
                <select name="district" class="form-control district">
                    <option value="">Choose</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <label for="staticEmail" class="col-sm-4 col-form-label text-right">Choose City <span class="required">*</span></label>
            <div class="form-group col-sm-8">
                <select name="city" class="form-control">
                    <option value="">Choose</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-group mt-3">
    <div class="row">
        <label for="staticEmail" class="col-sm-2 col-form-label text-right">Address <span class="required">*</span></label>
        <div class="form-group col-sm-10">
            <textarea name="address" class="form-control" rows="2"><?php echo e(Auth::user()->customer->address); ?></textarea>
        </div>
    </div>
</div>

<div class="row mt-md-4">
    <div class="col-lg-6 text-md-end">
        <label for="staticEmail" class="col-form-label text-md-right">Postal Code <span class="required">*</span></label>
    </div>
    <div class="col-lg-6">
        <input type="text" name="postCode" class="form-control" value="<?php echo e(Auth::user()->customer->postCode); ?>"/>
        <span class="text-danger"><?php echo e($errors->first('postCode')); ?></span>
    </div>
</div><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/customer/includes/profile-form.blade.php ENDPATH**/ ?>