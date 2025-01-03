<div class="container">
    
    <?php if($promotions->count() ==1): ?>
    <div class="banner-section mt-3 overflow-hidden mb-3">
        <div class="container" style=" background: linear-gradient(to top, <?php echo e($promotions[0]->text_color); ?>,<?php echo e($promotions[0]->bg_color); ?>); ">
            <div class="collection-product-container">
                <center class="p-3">
                    <a href="<?php echo e(route('promo-items',[app()->getLocale(),$promotions[0]->slug])); ?>">
                    <img  src="<?php echo e(url('storage/'.$promotions[0]->photo)); ?>" class="article-card-img p-1" style="border: 4px solid transparent;
                    border-image: linear-gradient(to top, <?php echo e($promotions[0]->bg_color); ?>, <?php echo e($promotions[0]->text_color); ?>); border-image-slice: 1;"> 
                    <h4 class="text-center pt-3" style="color:<?php echo e($promotions[0]->text_color); ?>"><?php echo e($promotions[0]->title); ?></h4>
                    </a>
                </center>
            </div>
        </div>
    </div>           
    <?php elseif($promotions->count() > 1): ?>
    <div class="banner-section mt-5 overflow-hidden">
        <div class="banner-section-inner">
            <div class="container">
                <div class="row <?php if($promotions->count()==2): ?>justify-content-center <?php endif; ?>">
                    <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-6 col-md-6 col-12" data-aos="fade-right" data-aos-duration="1200">
                        <a class="banner-item position-relative rounded" href="<?php echo e(route('promo-items', [app()->getLocale(),$promotion->slug])); ?>">
                            <img class="banner-img" src="<?php echo e(url('storage').'/'.$promotion->photo); ?>" class="article-card-img">
                            <div class="content-absolute content-slide">
                                <div class="container height-inherit d-flex align-items-center">
                                    <div class="content-box banner-content p-4">
                                        
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/promotions.blade.php ENDPATH**/ ?>