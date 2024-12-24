
<div class="announcement-bar bg-brand">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-8 col-md-9 col-12 d-lg-block d-none" data-aos="fade-top" data-aos-duration="700">
                <div class="testimonial-container position-relative">
                    <div class="testimonial-slideshow common-slider" data-slick='{
                        "slidesToShow": 1, 
                        "slidesToScroll": 1, 
                        "dots": true,
                        "arrows": false,
                        "responsive": [
                            {
                                "breakpoint": 768,
                                "settings": {
                                    "arrows": false
                                }
                            }
                        ],
                        "autoplay": false,
                        "autoplaySpeed": 2000
                        }'>
                       
                        <div class="m-0 p-2">
                            <p class="p-0 m-0" style="font-family:'Poppins';"> For any querires or asking, please contact  
                                <a class="announcement-text text-primary" href="tel:<?php echo e(request()->get('system_helpline')); ?>"> <?php echo e(request()->get('system_helpline')); ?></a> &nbsp; &nbsp; 
                            </p>
                        </div>
                        <div class="m-0 p-2">
                            <p class=" p-0 m-0" style="font-family:'Poppins';"> You can email us anytime at 
                                <a class="announcement-text text-primary" href="mailto:<?php echo e(request()->get('system_email')); ?>"> <?php echo e(request()->get('system_email')); ?></a>
                            </p>
                        </div>
                    </div>
                    <div class="activate-arrows show-arrows-always article-arrows arrows-white bg-5"></div>
                </div>
            </div>

 
            <div class="col-lg-4 col-md-9">
                <div class="announcement-meta-wrapper d-flex align-items-center justify-content-md-end justify-content-center">
                    <div class="announcement-meta d-flex align-items-center">
                        <div class="currency-wrapper">
                            <button type="button" class="currency-btn btn-reset text-white " data-bs-toggle="dropdown" aria-expanded="true">
                                <i class="fas fa-user-astronaut"></i>  <span>Account</span>
                                <span>
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </button>
                            <?php if(Auth::check()): ?>
                                <ul class="currency-list dropdown-menu dropdown-menu-end px-2 " data-popper-placement="bottom-end">
                                    <li class="currency-list-item mb-2">
                                        <a class="currency-list-option" href="<?php echo e(route('dashboard',[app()->getLocale()])); ?>" > 
                                            <i class="fas fa-tachometer-alt"></i> <span>My Panel</span> &nbsp; </a>
                                    </li>
                                    <li class="currency-list-item mb-2">
                                        <a class="currency-list-option" href="<?php echo e(url('change-password')); ?>" > 
                                            <i class="fas fa-lock text-success"></i> <span class="text-success">Change Passowrd</span> &nbsp; </a>
                                    </li>
                          
                                    <li  class="currency-list-item "></li>
                                    <li class="currency-list-item mb-2">
                                        <a class="currency-list-option text-danger" onclick="$('#logout-formH').submit();" href="javaScript:;" >
                                             <i class="fas fa-sign-out-alt"></i> <span>Logout</span> &nbsp; </a>
                                        <form id="logout-formH" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?> </form>
                                    </li>
                                </ul>

                            <?php else: ?> 
                                <ul class="currency-list dropdown-menu dropdown-menu-end px-2 " data-popper-placement="bottom-end">
                                    <li class="currency-list-item mb-2">
                                        <a class="currency-list-option" href="<?php echo e(route('login')); ?>" > <i class="fas fa-sign-in-alt"></i> <span>Login</span> &nbsp; </a>
                                    </li>
                                    <li  class="currency-list-item "></li>
                                    <li class="currency-list-item mb-2">
                                        <a class="currency-list-option" href="<?php echo e(route('register')); ?>" > <i class="fas fa-user"></i> <span>Register</span> &nbsp; </a>
                                    </li>
                                </ul>
                                
                            <?php endif; ?>
                        </div>
                        
                        <span class="separator-login d-flex px-3">
                            <svg width="2" height="9" viewBox="0 0 2 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.4" d="M1 0.5V8.5" stroke="#FEFEFE" stroke-linecap="round"></path>
                            </svg>
                        </span>

                        <?php if(session('user_currency')->id=='2'): ?>
                            <div class="currency-wrapper">
                                <a href="<?php echo e(route('showrooms', app()->getLocale())); ?>" class="currency-btn btn-reset text-white"><i class="fas fa-store"></i> Outlets</a>
                            </div>

                            <span class="separator-login d-flex px-3">
                                <svg width="2" height="9" viewBox="0 0 2 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M1 0.5V8.5" stroke="#FEFEFE" stroke-linecap="round"></path>
                                </svg>
                            </span>
                        <?php endif; ?>
                        
                        <div class="currency-wrapper">
                            <button type="button" class="currency-btn btn-reset text-white" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-map-marker-alt"></i> &nbsp; 
                                
                                <span class="text-uppercase"><?php echo e(session()->get('user_currency')->short_name); ?></span>
                                <span>
                                     <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </button>

                            <ul class="currency-list dropdown-menu dropdown-menu-end px-2">
                                <?php $__currentLoopData = get_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="currency-list-item pb-1">
                                        <a class="currency-list-option" href="<?php echo e(route('change-currency', [strtolower($item->short_name)])); ?>" data-value="<?php echo e($item->short_name); ?>">
                                            <img class="flag" src="<?php echo e(url($item->flag)); ?>" alt="<?php echo e($item->short_name); ?>">
                                            <span class="text-uppercase"><?php echo e($item->short_name); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>                       
			</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/top-header.blade.php ENDPATH**/ ?>