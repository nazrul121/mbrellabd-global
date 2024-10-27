<?php if(auth()->check() && auth()->user()->customer !=null): ?>
    <?php
        $addresses = \App\Models\Shipping_address::where('customer_id',auth()->user()->customer->id)->get();
    ?>
    <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <style>
            /* Hide the default radio button */
                .custom-checkbox input[type="radio"] {
                position: absolute;
                opacity: 0;
                cursor: pointer;
                }

                /* Create a custom checkbox appearance */
                .custom-checkbox {
                display: flex;
                align-items: center;
                cursor: pointer;
                font-size: 16px;
                }

                /* Style the checkmark box */
                .custom-checkbox .checkmark {
                height: 20px;
                width: 20px;
                background-color: white;
                border: 2px solid #ccc;
                border-radius: 4px;
                display: inline-block;
                margin-right: 10px;
                position: relative;
                }

                /* Add a checked effect */
                .custom-checkbox input[type="radio"]:checked + .checkmark {
                background-color: #2196f3;
                border-color: #2196f3;
                }

                /* Create the checkmark (hidden by default) */
                .custom-checkbox .checkmark::after {
                content: "";
                position: absolute;
                display: none;
                }

                /* Show the checkmark when checked */
                .custom-checkbox input[type="radio"]:checked + .checkmark::after {
                display: block;
                }

                /* Style the checkmark */
                .custom-checkbox .checkmark::after {
                left: 7px;
                top: 3px;
                width: 5px;
                height: 10px;
                border: solid white;
                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
                }

        </style>
        <label class="custom-checkbox container-fluid alert alert-light bg-5" for="shippingAddress<?php echo e($key); ?>">
            <input type="radio" name="shipping_address_id" id="shippingAddress<?php echo e($key); ?>" data-city="<?php echo e($item->city_id); ?>" value="<?php echo e($item->id); ?>" <?php if($key==0): ?>checked <?php endif; ?> />
            <span class="checkmark"></span> 

            <p class="mb-0">
                <?php echo e($item->fname.' '.$item->lname); ?> - <?php echo e($item->phone); ?> <br>

                <?php if($item->district): ?>
                <?php echo e($item->district->name); ?>  <i class="fas fa-long-arrow-alt-right text-info"></i> <?php endif; ?>

                <?php if($item->city): ?>
                <?php echo e($item->city->name); ?>  <i class="fas fa-long-arrow-alt-right text-info"></i> <?php endif; ?> 
                <?php echo e($item->address); ?>

            </p>
        </label>

        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <div class="shipping-address-form common-form checkout-summary-area">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">First name <span class="text-danger">*</span></label>
                    <input type="text" name="shipping_fname" value="<?php echo e(old('shipping_fname')); ?>"/>
                    <span class="text-danger"><?php echo e($errors->first('shipping_fname')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Last name <span class="text-danger">*</span></label>
                    <input type="text" name="shipping_lname" value="<?php echo e(old('shipping_lname')); ?>"/>
                    <span class="text-danger"><?php echo e($errors->first('shipping_lname')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label">Email address</label>
                    <input type="email" class="checkShipping" data-field="email" name="shipping_email" value="<?php echo e(old('shipping_email')); ?>"/>
                    <span class="text-danger"><?php echo e($errors->first('shipping_email')); ?></span>
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
                      <input type="text" class="form-control" name="shipping_phone" value="<?php echo e(old('shipping_phone','01')); ?>" placeholder="PHone No">
                    </div>
                    <span class="text-danger"><?php echo e($errors->first('shipping_phone')); ?></span>
                </fieldset>

                
            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <fieldset> 
                    <label class="label">Country <span class="text-danger">*</span></label>
                    <select name="shipping_country" class="form-select">
                        <option value="<?php echo e(session('user_currency')->id); ?>"><?php echo e(session('user_currency')->name); ?></option>
                    </select>
                    <span class="text-danger"><?php echo e($errors->first('shipping_country')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> <?php if(session('user_currency')->id==2): ?>Divisioin <?php else: ?> Region <?php endif; ?>  
                        <span class="text-danger">*</span>
                    </label>                                     
                    <select class="form-select" name="shipping_division">
                        <option value="">Choose Region</option>
                        <?php $__currentLoopData = \DB::table('divisions')->where(['country_id'=>session('user_currency')->id, 'status'=>'1'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($div->id.'|'.$div->name); ?>"><?php echo e($div->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="text-danger"><?php echo e($errors->first('shipping_division')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"> <?php if(session('user_currency')->id==2): ?>District <?php else: ?> State <?php endif; ?>  
                        <span class="text-danger">*</span>
                    </label>                                            
                    <select class="form-select" name="shipping_district"> </select>
                    <span class="text-danger"><?php echo e($errors->first('shipping_district')); ?></span>
                </fieldset>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <fieldset>
                    <label class="label"><?php if(session('user_currency')->id==2): ?>City <?php else: ?> Area <?php endif; ?>  
                        <span class="text-danger">*</span>
                    </label>                                  
                    <select class="form-select" name="shipping_city"> </select>
                    <span class="text-danger"><?php echo e($errors->first('shipping_city')); ?></span>
                </fieldset>
            </div>

            <div class="col-lg-12 col-md-12 col-12">
                <fieldset>
                    <label class="label">Address 2</label>
                    <textarea name="shipping_address" class="form-control"><?php echo e(old('shipping_address')); ?></textarea>
                    <span class="text-danger"><?php echo e($errors->first('shipping_address')); ?></span>
                </fieldset>
            </div>
        </div>
        <?php if(session('user_currency')->id != 2): ?>
            <div class="row mt-md-4">
                <div class="col-lg-6">
                    <label for="staticEmail" class="col-form-label text-md-end">Postal Code <span class="text-danger">*</span></label>
                </div>
                <div class="col-lg-6">
                    <input type="text" name="shipping_postCode" class="form-control" value="<?php echo e(old('shipping_postCode')); ?>"/>
                    <span class="text-danger"><?php echo e($errors->first('shipping_postCode')); ?></span>
                </div>
            </div>
        <?php endif; ?> 
    </div>
<?php endif; ?>

<?php /**PATH /var/www/laravelapp/resources/views/includes/checkout/shipping-form.blade.php ENDPATH**/ ?>