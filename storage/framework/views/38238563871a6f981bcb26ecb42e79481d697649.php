<div class="row">
    <div class="col-md-12">
        <div class="form-group row text-end">
            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">First Name <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <input type="text" name="fname" class="form-control" id="exampleFormControlInput1">
            </div>

        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="form-group row text-end">
            <label for="exampleFormControlInput2" class="col-sm-4 col-form-label">Last Name <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <input type="text" name="lname" class="form-control" id="exampleFormControlInput2">
            </div>
        </div>
    </div>
</div>

<div class="form-group row mt-3 text-end">
    <label for="staticEmail" class="col-sm-4 col-form-label">Email - optional</label>
    <div class="col-sm-8">
    <input type="email" class="form-control" name="email">
    </div>
</div>

<div class="form-group row mt-3 text-end">
    <label for="staticEmail" class="col-sm-3 col-form-label">Phone <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="phone">
    </div>
</div>


<div class="form-group row mt-4 mb-2">
    <label for="staticEmail" class="col-sm-2 col-form-label text-end">Country <span class="text-danger">*</span></label>
    <div class="col-sm-4">
        <select name="country" class="form-control">
            <option value="<?php echo e(session('user_currency')->id); ?>"><?php echo e(session('user_currency')->name); ?></option>
        </select>
    </div>
    <label for="staticEmail" class="col-sm-2 col-form-label text-end"><?php if(session('user_currency')->id==2): ?>Divisioin <?php else: ?> Regions <?php endif; ?> <span class="text-danger">*</span></label>
    <div class="col-sm-4">
        <select name="division" class="form-control">
            <option value="">Choose</option>
            <?php $__currentLoopData = \DB::table('divisions')->where(['status'=>'1','country_id'=>session('user_currency')->id])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($div->id); ?>"><?php echo e($div->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="row">
            <label for="staticEmail" class="col-sm-4 col-form-label text-end"><?php if(session('user_currency')->id==2): ?>District <?php else: ?> States <?php endif; ?> <span class="text-danger">*</span></label>
            <div class="form-group col-sm-8">
                <select name="district" class="form-control district">
                    <option value="">Choose</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <label for="staticEmail" class="col-sm-4 col-form-label text-end">City <span class="text-danger">*</span></label>
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
        <label for="staticEmail" class="col-sm-2 col-form-label text-end">Address <span class="text-danger">*</span></label>
        <div class="form-group col-sm-10">
            <textarea name="address" class="form-control" rows="2"></textarea>
        </div>
    </div>
</div>

<div class="row mt-md-4">
    <div class="col-lg-6">
        <label for="staticEmail" class="col-form-label text-md-end">Postal Code</label>
    </div>
    <div class="col-lg-6">
        <input type="text" name="postCode" class="form-control"/>
        <span class="text-danger"></span>
    </div>
</div>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/customer/address/form.blade.php ENDPATH**/ ?>