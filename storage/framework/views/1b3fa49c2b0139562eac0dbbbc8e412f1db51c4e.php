

<?php $__env->startSection('content'); ?>
    <div class="error-page mt-100">
        <div class="container mb-3">
            <div class="error-content text-center">
                <div class="error-img mx-auto">
                    <img src="https://blog.hubspot.com/hubfs/405-method-not-allowed.jpg" alt="error">
                </div>
                <p class="error-subtitle">Method Not Allowed</p>
                <p> <b>Oops!</b> The method you tried to use isn't allowed. Please check your request and try again.</p>
                <a href="<?php echo e(url('/')); ?>" class="btn-primary mt-4">BACK TO HOMEPAGE</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/errors/405.blade.php ENDPATH**/ ?>