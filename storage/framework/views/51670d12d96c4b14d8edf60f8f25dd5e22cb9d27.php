<footer class="overflow-hidden">
    <div class="footer-top pb-0" style="background: #0f3c3f;">
        <div class="container-fluid " >
            <div class="footer-widget-wrapper">
                <div class="row justify-content-between">

                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Social Media</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">  <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="footer-menu-item"><a class="text-white" target="_blank" href="<?php echo e($media->media_link); ?>"><i class="<?php echo e($media->media_icon); ?>"></i> <?php echo e($media->media_name); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <li>  <br>
                                    <div class="fb-like" data-href="https://www.facebook.com/MbrellaByMondol" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Categories</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">  <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="footer-menu-item"><a class="text-white" href="<?php echo e(route('group', [app()->getLocale(),$group->slug])); ?>"><?php echo e($group->title); ?> (<?php echo e($group->products()->count()); ?>)</a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <li class="footer-menu-item"><a class="text-white" href="<?php echo e(route('size-guide', app()->getLocale())); ?>">Product size guide</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>About Us</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                <li class="footer-menu-item"><a class="text-white" href="<?php echo e(route('faqs',[app()->getLocale()])); ?>">FAQs</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="<?php echo e(route('showrooms', app()->getLocale())); ?>">Store Locations</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="<?php echo e(route('career',app()->getLocale())); ?>">Career</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="<?php echo e(route('blog',app()->getLocale())); ?>">Blog / news feed</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="<?php echo e(url('register')); ?>">Register</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Policies</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                <?php $__currentLoopData = $policies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="footer-menu-item"><a class="text-white" href="<?php echo e(url('about/policy').'/'.$type->slug); ?>"><?php echo e($type->title); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Contacts</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                <li class="footer-menu-item"><a class="text-white"href="javaScript:;"><?php echo e(request()->get('system_title')); ?></a></li>
                                <li class="footer-menu-item"><a class="text-white"href="mailto:<?php echo e(request()->get('system_email')); ?>"><?php echo e(request()->get('system_email')); ?></a></li>
                                <li class="footer-menu-item"><a class="text-white" href="tel:<?php echo e(request()->get('system_helpline')); ?>">Call us: <?php echo e(request()->get('system_helpline')); ?></a></li>
                                <li class="footer-menu-item"><a class="text-white" target="_blank" href="https://www.google.com/maps/search/<?php echo e(str_replace('#','',str_replace(' ','+',request()->get('office_address')))); ?>/@23.8611459,90.3857328,13.75z"><?php echo e(request()->get('office_address')); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
        <div class="row pb-4">
            <a href="<?php echo e(url('storage/images/portPost_footer.png')); ?>" target="_blank">
                <img class="d-lg-block d-none" src="<?php echo e(url('storage/images/portPost_footer.png')); ?>" style="width:100%">
                <img class="d-none d-sm-block" src="<?php echo e(url('storage/images/portPos.webp')); ?>" style="width:100%">
            </a>
        </div>
    </div>
    <div class="footer-bottom bg-brand">
        <div class="container mb-1">
            <div class="footer-bottom-inner d-flex flex-wrap justify-content-md-between justify-content-center align-items-center">
                <ul class="footer-bottom-menu list-unstyled d-flex flex-wrap align-items-center mb-0">
                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="footer-menu-item"><a href="<?php echo e(url('page').'/'.$type->slug); ?>"><?php echo e($type->title); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li class="footer-menu-item"><a href="/truck">Track order</a></li>
                    
                </ul>
                <p class="copyright footer-text"> &copy; <span class="current-year"></span> Mbrella ltd. By <b>IT-Station @ Mbrella</b></p>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH /var/www/laravelapp/resources/views/includes/footer.blade.php ENDPATH**/ ?>