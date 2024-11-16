<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Product copy entry</h5>
                <div class="card-header-right">
                    <a href="<?php echo e(route('common.product')); ?>" class="btn btn-outline-primary"><i class="feather icon-list"></i> View products</a>
                </div>
            </div>
            <?php if(Session::has('message')): ?>
            <div class="card-body">
                <div class="alert <?php if(Session::has('success')): ?> alert-success <?php else: ?> alert-danger <?php endif; ?> alert-dismissible fade show" role="alert">
                    <h5> <strong> <?php if(Session::has('success')): ?>Success :  <?php echo e(Session::get('success')); ?>

                       &nbsp; &nbsp; &nbsp; You may add variants now
                       <a href="<?php echo e(route('common.product')); ?>?product=<?php echo e(Session::get('id')); ?>&title=<?php echo e(Session::get('title')); ?>">Click here</a>
                    <?php else: ?> Warning: <?php echo e(Session::get('alert')); ?>  <?php endif; ?>
                    </strong> </h5>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
            </div>  <?php endif; ?>
        </div>
    </div>
    <form id="addForm" class="col-12" style="position:relative;top:-25px" action="<?php echo e(route('common.product.store')); ?>" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?>
        <?php echo $__env->make('common.product.copy.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/copy/create.blade.php ENDPATH**/ ?>