<?php $currencies = \DB::table('countries')->where('status','1')->get();?>


<div class="offcanvas offcanvas-start d-flex d-lg-none" tabindex="-1" id="drawer-menu" style="background-color:rgb(0 0 0 / 64%)">
    <div class="offcanvas-wrapper">
        <div class="offcanvas-header border-btm-black">
            <h5 class="drawer-heading">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
            <nav class="site-navigation">
                <ul class="main-menu list-unstyled" style="overflow-y:scroll;height:100vh">
                    <li class="menu-list-item nav-item has-dropdown active">
                        <div class="mega-menu-header"> <a class="nav-link active" href="<?php echo e(route('home')); ?>"> Home </a></div>
                    </li>
                    <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="menu-list-item nav-item has-megamenu">
                            <div class="mega-menu-header">
                                <a class="nav-link" href="<?php echo e(route('group',[app()->getLocale(),$cat->slug])); ?>"><?php echo e($cat->title); ?></a>
                                <span class="open-submenu text-white">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div class="submenu-transform submenu-transform-desktop">
                                <div class="container">
                                    <div class="offcanvas-header border-btm-black">
                                        <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                            <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                <polyline points="15 18 9 12 15 6"></polyline>
                                            </svg>
                                            <span class="menu-back-text"><?php echo e($cat->title); ?></span>
                                        </h5>
                                    </div>
                                    <ul class="submenu megamenu-container list-unstyled" style="overflow-y:auto;height:90vh;">
                                        <li class="menu-list-item nav-item-sub">
                                            <div class="mega-menu-header">
                                                <a class="nav-link-sub nav-text-sub megamenu-heading" href="<?php echo e(route('group',[app()->getLocale(),$cat->slug])); ?>">All of <b><?php echo e($cat->title); ?></b></a>
                                            </div>
                                        </li>
                                        <?php $__currentLoopData = $cat->inner_groups()->where('status','1')->orderBy('sort_by')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="menu-list-item nav-item-sub">
                                            <div class="mega-menu-header">
                                                <a class="nav-link-sub nav-text-sub megamenu-heading" href="<?php echo e(route('group-in',[app()->getLocale(),$sub->slug])); ?>"><?php echo e($sub->title); ?></a>
                                                <span class="open-submenu">
                                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="submenu-transform">
                                                <div class="offcanvas-header border-btm-black">
                                                    <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                                        <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" 
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"  stroke-linecap="round" stroke-linejoin="round" >
                                                            <polyline points="15 18 9 12 15 6"></polyline>
                                                        </svg>
                                                        <span class="menu-back-text"><?php echo e($sub->title); ?></span>
                                                    </h5>
                                                </div>
                                                <ul class="megamenu list-unstyled megamenu-container">
                                                    <li class="menu-list-item nav-item-sub">
                                                        <a class="nav-link-sub nav-text-sub" href="<?php echo e(route('group-in',[app()->getLocale(),$sub->slug])); ?>">All of <b><?php echo e($sub->title); ?></b></a>
                                                    </li>
                                                    <?php $__currentLoopData = $sub->child_groups()->where('status','1')->orderBy('sort_by')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="menu-list-item nav-item-sub">
                                                        <a class="nav-link-sub nav-text-sub" href="<?php echo e(route('child-in',[app()->getLocale(), $child->slug])); ?>"><?php echo e($child->title); ?></a>
                                                    </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <li></li>

                    <?php $__currentLoopData = $seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $season): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $groups = \App\Models\Group_season::where(['season_id'=>$season->id, 'status'=>'1'])->get(); ?>
                        <li class="menu-list-item nav-item has-megamenu">
                            <div class="mega-menu-header">
                                <a class="nav-link" href="<?php echo e(route('season-products',[app()->getLocale(),$season->slug])); ?>">  <?php echo e($season->title); ?>  </a>
                                <span class="open-submenu text-white">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div class="submenu-transform submenu-transform-desktop">
                                <div class="container">
                                    <div class="offcanvas-header border-btm-black">
                                        <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                            <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="15 18 9 12 15 6"></polyline>
                                            </svg> <span class="menu-back-text"><?php echo e($season->title); ?></span>
                                        </h5>
                                    </div>
                                    <ul class="submenu megamenu-container list-unstyled" style="overflow-y:auto;height:90vh;">
                                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $innerGroups = \App\Models\Inner_group_season::where(['group_id'=>$item->group->id,'season_id'=>$season->id,'status'=>'1'])->get();?>
                                        <li class="menu-list-item nav-item-sub">
                                            <div class="mega-menu-header">
                                                <a class="nav-link-sub nav-text-sub megamenu-heading" href="<?php echo e(route('season-group', [app()->getLocale(), $season->slug,$item->group->slug])); ?>">
                                                    <?php echo e($item->group->title); ?>

                                                </a>
                                                <span class="open-submenu">
                                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="submenu-transform">
                                                <div class="offcanvas-header border-btm-black">
                                                    <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                                        <svg class="icon icon-menu-back"xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"stroke="currentColor" stroke-width="2" >
                                                            <polyline points="15 18 9 12 15 6"></polyline>
                                                        </svg>
                                                        <span class="menu-back-text"><?php echo e($item->group->title); ?></span>
                                                    </h5>
                                                </div>
                                                <ul class="megamenu list-unstyled megamenu-container">
                                                    <?php $__currentLoopData = $innerGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="menu-list-item nav-item-sub">
                                                        <a class="nav-link-sub nav-text-sub" href="<?php echo e(route('season-group-in',[app()->getLocale(), $season->slug, $sub->inner_group->slug])); ?>"><?php echo e($sub->inner_group->title); ?></a>
                                                        <ul class="megamenu list-unstyled megamenu-container">
                                                            <?php $childGroups = \App\Models\Child_group_season::where(['inner_group_id'=>$sub->inner_group->id,'season_id'=>$season->id, 'status'=>'1'])->select('inner_group_id','child_group_id')->get();?>
                                                            <?php $__currentLoopData = $childGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li class="menu-list-item nav-item-sub">
                                                                <a class="nav-link-sub nav-text-sub" href="<?php echo e(route('season-child-in',[app()->getLocale(), $season->slug, $child->child_group->slug])); ?>"><?php echo e($child->child_group->title); ?></a>
                                                            </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <li></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <li></li>

                    <?php if($promotions->count() > 0): ?>

                    <li class="menu-list-item nav-item has-dropdown">
                        <div class="mega-menu-header">
                            <a class="nav-link active" href="<?php echo e(route('promotions',app()->getLocale())); ?>">SALE </a>
                            <span class="open-submenu text-white">
                                <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </span>
                        </div>
                        <div class="submenu-transform submenu-transform-desktop">
                            <div class="offcanvas-header border-btm-black">
                                <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                    <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"  stroke-linejoin="round"> <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>  <span class="menu-back-text"> SALE</span>
                                </h5>
                            </div>
                            <ul class="submenu list-unstyled">
                                <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="menu-list-item nav-item-sub">
                                    <a class="nav-link-sub nav-text-sub" href="<?php echo e(route('promo-items',[app()->getLocale(), $promo->slug])); ?>"><?php echo e($promo->title); ?></a>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="menu-list-item nav-item">
                        <a class="nav-link" href="<?php echo e(url('page').'/'.$type->slug); ?>"><?php echo e($type->title); ?></a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li></li>
                    <li class="menu-list-item nav-item"> <a class="nav-link" href="/blog">Blog</a> </li>

                    <li class="menu-list-item nav-item">
                        <a class="announcement-text text-white" href="tel:<?php echo e(request()->get('system_phone')); ?>">
                            <span class="fa fa-phone-alt"></span>
                            Call: <?php echo e(request()->get('system_phone')); ?>

                        </a>
                    </li>
                    
                    <li class="menu-list-item nav-item">
                        <a class="header-action-item header-wishlist text-white" href="/wishlist">
                            <i class="fas fa-heart"></i>
                            <span>My wishlist</span>
                        </a>
                    </li>

                    <li class="menu-list-item nav-item ">
                        <button type="button" class="currency-btn btn-reset" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="flag" src="<?php echo e(url(Session::get('user_currency')->flag)); ?>" alt="<?php echo e(Session::get('user_currency')->short_name); ?>">
                            <span class="text-uppercas text-white"><?php echo e(Session::get('user_currency')->short_name); ?></span>
                            <span class="utilty-icon-wrapper">
                                <svg class="icon icon-dropdown bg-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </span>
                        </button>
                      
                        <ul class="currency-list dropdown-menu dropdown-menu-end px-2">
                            <?php $__currentLoopData = get_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="currency-list-item ">
                                    <a class="currency-list-option" href="<?php echo e(route('change-currency', [$item->short_name])); ?>" data-value="<?php echo e($item->short_name); ?>">
                                        <img class="flag" src="<?php echo e($item->flag); ?>" alt="<?php echo e($item->short_name); ?>">
                                        <span class="text-uppercas"><?php echo e($item->short_name); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                        </ul>
                    </li>
                    <?php if(!auth()->check()): ?>
                        <li class="menu-list-item nav-item">
                            <a class="announcement-login announcement-text  text-white" href="/login">
                                <i class="fas fa-sign-in-alt"></i> <span> Login</span>
                            </a>
                        </li>
                        <?php else: ?> 
                        <li class="menu-list-item nav-item">
                            <a class="announcement-login announcement-text text-white" href="/dashboard">
                                <i class="fas fa-tachometer-alt"></i> <span> My Panel</span>
                            </a>
                        </li>
                        <li class="menu-list-item nav-item">
                            <a href="#" onclick="$('#logout-formH').submit();" class="announcement-login announcement-text text-danger"><i class="fas fa-sign-out-alt text-danger"></i> Logout</a>
                            <form id="logout-formH" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?> </form>
                        </li>
                     <?php endif; ?>

                </ul>
            </nav>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/mobile_nav.blade.php ENDPATH**/ ?>