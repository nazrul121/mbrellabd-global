<?php
    $metas = \DB::table('metas')->where('pageFor','faq');
    $meta = \DB::table('metas')->where(['pageFor'=>'faq', 'type'=>'title']);

    $metaTitle = 'FAQs | '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }
?>

<?php $__env->startSection('title',$metaTitle); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('meta'); ?>
    <meta property="og:url" content="<?php echo e(url()->full()); ?>" />
    <meta property="og:type" content="website">
    <?php $__currentLoopData = $metas->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <meta property="og:<?php echo e($meta->type); ?>" content="<?php echo e($meta->description); ?>" />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopPush(); ?>


<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>
            <li><a>FAQs</a></li>
        </ul>
    </div>
</div>


<div class="faq-section mt-100 overflow-hidden">
    <div class="faq-inner">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-heading">Frequently Asked Question</h2>
            </div>
            <div class="faq-container mb-5">
                <div class="row pb-4">
                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="faq-item rounded">
                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between" data-bs-toggle="collapse" data-bs-target="#faq<?php echo e($key); ?>">
                                <?php echo e($faq->question); ?>

                                <span class="faq-heading-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F76B6A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h2>
                            <div id="faq<?php echo e($key); ?>" class="accordion-collapse collapse">
                                <p class="faq-body text_14"> <?php echo e($faq->answer); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($posts->count()<1): ?>
                        <p class="text-center p-md-5 text-warning border border-warning">No <b>FAQ</b>s is activated now. Please check after some while!</p>
                    <?php endif; ?>
                    
                </div>
               
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/faqs.blade.php ENDPATH**/ ?>