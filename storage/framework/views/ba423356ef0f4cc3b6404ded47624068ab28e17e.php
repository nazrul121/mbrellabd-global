<?php $__env->startSection('title','404 - Page Not Found'); ?>

<?php $__env->startSection('content'); ?>

<div class="error-page">
    <div class="container">
        <div class="error-content text-center pb-5">
            <h1 class="error-code">404</h1>
            <div class="error-img mx-auto">
                <img style="max-width:100%" src="/assets/img/error/404.png" alt="page not found">
            </div>

            <h2 class="error-message">Oops! Page Not Found.</h2>
            
            <p class="mt-4"><a href="<?php echo e(url('/')); ?>" class="btn btn-success mt-4">Return to Homepage</a></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/errors/404.blade.php ENDPATH**/ ?>