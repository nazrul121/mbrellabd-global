<?php $__env->startSection('title', 'My Panel | '.request()->get('system_title') ); ?>

<?php $__env->startSection('content'); ?>

<div class="trusted-section overflow-hidden">

    <?php if(Auth::user()->customer !=null): ?>
 
        <div class="trusted-section-inner">
            <div class="container">
                <div class="row justify-content-center trusted-row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="trusted-badge bg-trust-1 rounded">
                            <div class="trusted-icon">
                                <img class="icon-trusted" src="/assets/img/trusted/1.png" alt="icon-1">
                            </div>
                            <div class="trusted-content">
                                <h2 class="heading_18 trusted-heading">My Balance</h2>
                                <p class="text_16 trusted-subheading trusted-subheading-2"> <?php echo e(number_format(Auth::user()->customer->balance,2)); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="trusted-badge bg-trust-2 rounded">
                            <div class="trusted-icon">
                                <img class="icon-trusted" src="/assets/img/trusted/2.png" alt="icon-2">
                            </div>
                            <div class="trusted-content">
                                <h2 class="heading_18 trusted-heading">My <a href="<?php echo e(route('customer.orders',app()->getLocale())); ?>">orders </a> </h2>
                                <p class="text_16 trusted-subheading trusted-subheading-2"><?php echo e(Auth::user()->customer->orders()->count()); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <table class="cart-table table mt-5">
                <thead>
                <tr> <th class="cart-caption heading_18">Billing information</th> </tr>
                </thead>
    
                <tbody class="bg-5 col-12">                                                                    
                    <tr>
                    <td class="cart-item-details">
                        <h2 class="product-title mt-3">
                            <b>Name</b>: <?php echo e(Auth::user()->customer->first_name); ?> <?php echo e(Auth::user()->customer->last_name); ?> -
                            <b>Phone</b>: <?php echo e(Auth::user()->phone); ?> -
                            <?php if(Auth::user()->email !=null): ?>
                                <b>Email</b>: <?php echo e(Auth::user()->email); ?> <br>
                            <?php endif; ?>
    
                        </h2>
                        <p class="product-vendor text-dark mt-3">
                            <b>Area</b>: <?php if(Auth::user()->customer->division): ?> <?php echo e(Auth::user()->customer->division->name); ?> <i class="fa fa-arrow-right"></i> <?php endif; ?>
                            <?php if(Auth::user()->customer->district): ?> <?php echo e(Auth::user()->customer->district->name); ?> <i class="fa fa-arrow-right"></i>  <?php endif; ?>
                                <?php if(Auth::user()->customer->city): ?> <?php echo e(Auth::user()->customer->city->name); ?> <br> <?php endif; ?>
    
                            <b>Address</b>: <?php echo e(Auth::user()->customer->address); ?>

                            <a href="<?php echo e(route('customer.account-info',app()->getLocale())); ?>" class="position-relative review-submit-btn contact-submit-btn float-end"> Edit Billing</a>
                        </p>                                   
                    </td>                
                    </tr> 
                </tbody>
            </table>
        </div>
        
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('customer.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/customer/includes/dashboard.blade.php ENDPATH**/ ?>