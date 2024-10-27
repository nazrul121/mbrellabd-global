<?php if(auth()->check() && auth()->user()->customer !=null): ?>
    <div class="checkout-user-area overflow-hidden d-flex align-items-center mt-1">
        <div class="checkout-user-img me-4">
            <img src="<?php echo e(url('/storage/'. auth()->user()->customer->photo)); ?>" height="70">
        </div>
        <div class="checkout-user-details d-flex align-items-center justify-content-between w-100">
            <div class="checkout-user-info">
                <h2 class="checkout-user-name"><?php echo e(auth()->user()->customer->first_name); ?>  <?php echo e(auth()->user()->customer->last_name); ?> -  <?php echo e(auth()->user()->customer->phone); ?></h2>
                <p class="checkout-user-address mb-0">
                    <?php if(auth()->user()->customer->division): ?>
                        <?php echo e(auth()->user()->customer->division->name); ?> <i class="fas fa-long-arrow-alt-right text-info"></i> <?php endif; ?>

                    <?php if(auth()->user()->customer->district): ?>
                    <?php echo e(auth()->user()->customer->district->name); ?> <i class="fas fa-long-arrow-alt-right text-info"></i> <?php endif; ?>

                    <?php if(auth()->user()->customer->city): ?>
                    <?php echo e(auth()->user()->customer->city->name); ?> <i class="fas fa-long-arrow-alt-right text-info"></i> <?php endif; ?> 
                    <?php echo e(auth()->user()->customer->address); ?>

                </p>
            </div>
            <input type="hidden" name="phone" value="<?php echo e(auth()->user()->customer->phone); ?>"/>
            <a href="<?php echo e(route('customer.account-info', app()->getLocale())); ?>" target="_blank" class="edit-user btn-secondary">EDIT PROFILE</a>
        </div>
    </div>
<?php else: ?> 

    <div class="shipping-address-form common-form">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">First name <span class="text-danger">*</span></label>
                    <input type="text" name="fname" value="<?php echo e(old('fname')); ?>" required/>
                    <span class="text-danger"><?php echo e($errors->first('fname')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Last name <span class="text-danger">*</span></label>
                    <input type="text" name="lname" value="<?php echo e(old('lname')); ?>" required/>
                    <span class="text-danger"><?php echo e($errors->first('lname')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Email address</label>
                    <input type="email" class="checkBilling" name="email" data-field="email" value="<?php echo e(old('email')); ?>"/>
                    <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                </fieldset>
            </div>

            
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label>Phone No.</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><?php echo e(session('user_currency')->phone_code); ?></div>
                        <input type="hidden" value="<?php echo e(session('user_currency')->phone_code); ?>">
                      </div>
                      <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="PHone No">
                    </div>
                    <span class="text-danger"><?php echo e($errors->first('phone')); ?></span>
                </fieldset>

            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Country <span class="text-danger">*</span></label>
                    <select name="country" class="form-select" required>
                        <option value="<?php echo e(session('user_currency')->id); ?>"><?php echo e(session('user_currency')->name); ?></option>
                    </select>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> <?php if(session('user_currency')->id==2): ?>Divisioin <?php else: ?> Region <?php endif; ?>  
                        <span class="text-danger">*</span>
                    </label>                                                        
                    <select class="form-select" name="division">
                        <option value="">Choose <?php if(session('user_currency')->id==2): ?>Divisioin <?php else: ?> Region <?php endif; ?>  </option>
                        <?php $__currentLoopData = \DB::table('divisions')->where(['country_id'=>session('user_currency')->id, 'status'=>'1'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php echo e(old('division') == $div->id.'|'.$div->name ? 'selected' : ''); ?> value="<?php echo e($div->id.'|'.$div->name); ?>"><?php echo e($div->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="text-danger"><?php echo e($errors->first('division')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> <?php if(session('user_currency')->id==2): ?>District <?php else: ?> State <?php endif; ?>  
                        <span class="text-danger">*</span>
                    </label>                                                        
                    <select class="form-select" name="district"> </select>
                    <span class="text-danger"><?php echo e($errors->first('district')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"><?php if(session('user_currency')->id==2): ?>City <?php else: ?> Area <?php endif; ?>  
                        <span class="text-danger">*</span>
                    </label>                                                        
                    <select class="form-select" name="city"> </select>
                    <span class="text-danger"><?php echo e($errors->first('city')); ?></span>
                </fieldset>
            </div>

            <div class="col-lg-12 col-md-12 col-12">
                <fieldset>
                    <label class="label">Address 2</label>
                    <textarea name="address" class="form-control" required><?php echo e(old('address')); ?></textarea>
                    <span class="text-danger"><?php echo e($errors->first('address')); ?></span>
                </fieldset>
            </div>
        </div>

        <?php if(session('user_currency')->id != 2): ?>
            <div class="row mt-md-4">
                <div class="col-lg-6">
                    <label for="staticEmail" class="col-form-label text-md-end">Postal Code <span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-6">
                    <input type="text" name="postCode" class="form-control" value="<?php echo e(old('postCode')); ?>"/>
                    <span class="text-danger"><?php echo e($errors->first('postCode')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        
    </div>

    <div class="shipping-address-area">
        <div class="form-checkbox d-flex align-items-center mt-4">
            <input class="form-check-input mt-0" id="createAcc" name="createAccount" type="checkbox" <?php if(old('createAccount')): ?> checked <?php endif; ?>>
            <label class="form-check-label ms-2" for="createAcc">
                Create My Account
            </label>
        </div>
    </div>

<?php endif; ?><?php /**PATH /var/www/laravelapp/resources/views/includes/checkout/billing-form.blade.php ENDPATH**/ ?>