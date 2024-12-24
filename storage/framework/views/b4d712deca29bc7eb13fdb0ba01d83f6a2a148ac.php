
<div class="container">
    <div class="row">
        <?php $__currentLoopData = array_chunk($categories->toArray(), 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>   
            <div class="col-lg-6 col-md-6 col-6 aos-init aos-animate" data-aos="fade-up" data-aos-duration="700">
                <div class="article-card bg-transparent p-0 shadow-none">
                    
                    <a class="article-card-img-wrapper" href="<?php echo e(route('group',[app()->getLocale(),$cat['slug']])); ?>">
                        <img style="width:100%" src="<?php echo e(url('storage').'/'.$cat['photo']); ?>" alt="<?php echo e($cat['title']); ?>" class="article-card-img rounded">
                        <span class="article-tag-absolute rounded p-3"><?php echo e($cat['title']); ?></span>
                    </a>  
                    
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/category.blade.php ENDPATH**/ ?>