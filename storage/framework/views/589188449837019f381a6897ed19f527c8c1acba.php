<header class="sticky-header border-btm-black header-1">
    <div class="header-bottom">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-2 col-6">
                    <div class="header-logo">
                        <a href="<?php echo e(route('home')); ?>" class="logo-main">
                            <img class="logoHeight" src="<?php echo e(url('storage').'/'.request()->get('header_logo')); ?>" loading="<?php echo e(request()->get('system_domain')); ?>">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 d-lg-block d-none">
                    <nav class="site-navigation">
                        <ul class="main-menu list-unstyled justify-content-center">
                            <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $countrySubCats = \App\Models\Country_inner_group::where('country_id',session('user_currency')->id)->select('inner_group_id')->distinct()->get()->toArray();?>
                            <li class="menu-list-item nav-item has-megamenu">
                                <div class="mega-menu-header">
                                    <a class="nav-link" href="<?php echo e(route('group',[app()->getLocale(),$cat->slug])); ?>">  <?php echo e($cat->title); ?>  </a>
                                    <span class="open-submenu secondary-color">
                                        <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                                <div class="submenu-transform submenu-transform-desktop container">
                                    <div class="bg-transparent-black">
                                        <ul class="submenu megamenu-container list-unstyled grid">
                                            <?php $__currentLoopData = $cat->inner_groups()->whereIn('id',$countrySubCats)->where('status','1')->orderBy('sort_by')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $countryChildCats = \App\Models\Child_group_country::where('country_id',session('user_currency')->id)->select('child_group_id')->distinct()->get()->toArray();?>
                                            <li class="menu-list-item nav-item-sub grid-item">
                                                <div class="mega-menu-header">
                                                    <a class="nav-link-sub nav-text-sub megamenu-heading text-white"
                                                        href="<?php echo e(route('group-in',[app()->getLocale(),$sub->slug])); ?>"><?php echo e($sub->title); ?>

                                                    </a>
                                                </div>
                                                <div class="submenu-transform megamenu-transform">
                                                    <ul class="megamenu list-unstyled">
                                                        <?php $__currentLoopData = $sub->child_groups()->whereIn('id',$countryChildCats)->where('status','1')->orderBy('sort_by')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            
                            <?php $__currentLoopData = $seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $season): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $groups = \App\Models\Group_season::where(['season_id'=>$season->id, 'status'=>'1'])->get(); ?>
                                
                                <li class="menu-list-item nav-item has-megamenu">
                                    <div class="mega-menu-header">
                                        <a class="nav-link" href="<?php echo e(route('season-products',[app()->getLocale(),$season->slug])); ?>">  <?php echo e($season->title); ?>  </a>
                                        <span class="open-submenu secondary-color">
                                            <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"> <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="submenu-transform submenu-transform-desktop container">
                                        <div class="bg-transparent-black">
                                            <ul class="submenu megamenu-container list-unstyled">
                                                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $innerGroups = \App\Models\Inner_group_season::where(['group_id'=>$item->group->id,'season_id'=>$season->id,'status'=>'1'])->get();?>
                                                    <?php $__currentLoopData = $innerGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="menu-list-item nav-item-sub">
                                                        <div class="mega-menu-header">
                                                            <a class="nav-link-sub nav-text-sub megamenu-heading text-white" href="<?php echo e(route('season-group', [app()->getLocale(), $season->slug,$item->group->slug])); ?>"><?php echo e($item->group->title); ?></a>
                                                            &nbsp; <a class="nav-link-sub nav-text-sub megamenu-heading text-white" href="javaScript:;"><span class="text-white"> > </span></a> &nbsp;
                                                            <a class="nav-link-sub nav-text-sub megamenu-heading text-white"
                                                                href="<?php echo e(route('season-group-in',[app()->getLocale(), $season->slug, $sub->inner_group->slug])); ?>"><?php echo e($sub->inner_group->title); ?>

                                                            </a>
                                                        </div>
                                                        <div class="submenu-transform megamenu-transform">
                                                            <ul class="megamenu list-unstyled">
                                                                <?php $childGroups = \App\Models\Child_group_season::where(['inner_group_id'=>$sub->inner_group->id,'season_id'=>$season->id, 'status'=>'1'])->select('inner_group_id','child_group_id')->get();?>
                                                                <?php $__currentLoopData = $childGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li class="menu-list-item nav-item-sub">
                                                                    <a class="nav-link-sub nav-text-sub" href="<?php echo e(route('season-child-in',[app()->getLocale(), $season->slug, $child->child_group->slug])); ?>"> <?php echo e($child->child_group->title); ?></a>
                                                                </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($promotions->count() > 0): ?>
                                <li class="menu-list-item nav-item has-dropdown">
                                    <div class="mega-menu-header">
                                        <a class="nav-link" href="<?php echo e(route('promotions',app()->getLocale())); ?>"> SALE</a>
                                        <span class="open-submenu secondary-color">
                                            <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="submenu-transform submenu-transform-desktop container">
                                        <div class=" bg-transparent-black">
                                            <ul class="submenu list-unstyled">
                                                <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="menu-list-item nav-item-sub">
                                                    <a class="nav-link-sub nav-text-sub" href="<?php echo e(route('promo-items',[app()->getLocale(), $promo->slug])); ?>"><?php echo e($promo->title); ?></a>
                                                </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-2 col-md-2 col-md-10 col-6">
                    <div class="header-action d-flex align-items-center justify-content-end">
                       
                        <a class="header-action-item header-search" href="javascript:void(0)">
                            <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z" fill="#e29d1b" />
                            </svg>
                        </a>
                        <a class="header-action-item header-wishlist ms-4 d-none d-lg-block" href="<?php echo e(route('wishlist', app()->getLocale() )); ?>">
                            <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22"  fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" fill="#e29d1b" />
                            </svg>
                        </a>
                        <a class="header-action-item header-login ms-4 d-lg-none " href="<?php echo e(route('login',[app()->getLocale()])); ?>">
                            <svg class="icon icon-user" width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 0C3.07227 0 1.5 1.57227 1.5 3.5C1.5 4.70508 2.11523 5.77539 3.04688 6.40625C1.26367 7.17188 0 8.94141 0 11H1C1 8.78516 2.78516 7 5 7C7.21484 7 9 8.78516 9 11H10C10 8.94141 8.73633 7.17188 6.95312 6.40625C7.88477 5.77539 8.5 4.70508 8.5 3.5C8.5 1.57227 6.92773 0 5 0ZM5 1C6.38672 1 7.5 2.11328 7.5 3.5C7.5 4.88672 6.38672 6 5 6C3.61328 6 2.5 4.88672 2.5 3.5C2.5 2.11328 3.61328 1 5 1Z"fill="#e29d1b" />
                            </svg>
                        </a>
                        <a class="header-action-item header-cart ms-4" href="#drawer-cart" data-bs-toggle="offcanvas">

                            <p class="cardNumBadge addToCardNum"><?php if(Session::has('cart')): ?> <?php echo e(Session::get('cart')->where('country_id',session('user_currency')->id)->count()); ?> <?php endif; ?> </p> 
                            
                            <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z" fill="#e29d1b" />
                            </svg>
                        </a>
                        <a class="header-action-item header-hamburger ms-4 d-lg-none" href="#drawer-menu"
                            data-bs-toggle="offcanvas">
                            <svg class="icon icon-hamburger" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="#e29d1b" stroke="#e29d1b" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-wrapper ui-widget">
            <div class="container">
                <form class="search-form d-flex align-items-center" action="#">
                    <button type="button" class="search-submit bg-transparent pl-0 text-start">
                        <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z" fill="black" />
                        </svg>
                    </button>

                    <div class="search-input mr-4">
                        <input type="text" placeholder="Search your products..." id="searchProduct" autocomplete="off">
                    </div> 

                    <div class="search-close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-close">
                            <line x1="18" y1="6" x2="6" y2="18"></line> <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </div>  

                </form>
            </div>
        </div>
    </div>
</header>

<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/header.blade.php ENDPATH**/ ?>