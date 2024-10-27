<?php $__env->startSection('title','Search Promotions | '.request()->get('system_title')); ?>

<?php $__env->startPush('meta'); ?>
    <meta property="og:url" content="<?php echo e(url()->full()); ?>" />
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo e(request()->get('system_title')); ?> : Promotion" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('includes.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if($promotions->count() >0): ?>
        <?php echo $__env->make('includes.promotions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php else: ?>
        <div class="cart-page mt-100">
            <div class="container">
                <div class="cart-page-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="cart-total-area">
                                <div class="cart-total-box mt-4">
                                    <p class="shipping_text text-center">No promotions available right now</p>
                                    <div class="d-flex justify-content-center mt-1">
                                        <a href="<?php echo e(route('products')); ?>" class="position-relative btn-primary text-uppercase">
                                            Continue Shopping with variants
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/promotions.blade.php ENDPATH**/ ?>