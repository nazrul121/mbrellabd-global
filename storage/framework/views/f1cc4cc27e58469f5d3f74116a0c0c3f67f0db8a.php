<?php $__env->startSection('content'); ?>

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
            <li>Page</li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" />
                    </g>
                </svg>
            </li>
            <li><?php if($type->title==null): ?> <?php echo e(request()->get('system_title').' policies'); ?> <?php else: ?><?php echo e($type->title); ?> <?php endif; ?> </li>
        </ul>
    </div>
</div>


<div class="faq-section pt-100 overflow-hidden">
    <div class="faq-inner">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-heading"><?php if($type->title==null): ?> <?php echo e(request()->get('system_title').' policies'); ?> <?php else: ?><?php echo e($type->title); ?> <?php endif; ?> </h2>
            </div>
            <div class="faq-container mb-5">
                <div class="row">
                    <?php $__currentLoopData = $policies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="faq-item rounded">
                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between" data-bs-toggle="collapse" data-bs-target="#question<?php echo e($key); ?>">
                                <?php echo e($policy->title); ?>

                                <span class="faq-heading-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F76B6A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h2>
                            <div id="question<?php echo e($key); ?>" class="accordion-collapse collapse <?php if($key==0): ?>show <?php endif; ?>">
                                <p class="faq-body text_14"><?php echo $policy->description; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/policy.blade.php ENDPATH**/ ?>