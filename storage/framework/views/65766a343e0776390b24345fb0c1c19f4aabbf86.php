<?php
    $page_types = \DB::table('page_post_types')->select('id','title','slug')->get();
    $policy_types = \DB::table('policy_types')->select('id','title','slug')->get();
    $order_types = \App\Models\Order_status::select('id','title')->orderBy('id')->get();
    $promotion_types = \DB::table('promotion_types')->select('id','title')->get();
?>

<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="<?php echo e(url(Auth::user()->user_type->title)); ?>/dashboard" class="b-brand"> <img alt="" src="<?php echo e(url('storage/images/header-logo.png')); ?>" height="40"/> </a>
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php if(Request::segment(2)=='dashboard'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="<?php echo e(url(Auth::user()->user_type->title)); ?>/dashboard" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                </li>

                <li class="nav-item pcoded-menu-caption"> <label>Sales</label></li>
                <?php if(is_label_has_nay_permissions(1)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='catalog'): ?>pcoded-trigger <?php endif; ?>">
                    <a href="javaScript:;" class="nav-link"><span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span><span class="pcoded-mtext">Product line</span></a>
                    <ul class="pcoded-submenu" style="display:<?php if(Request::segment(2)=='catalog'): ?> block <?php endif; ?>">
                        <li class="nav-item pcoded-hasmenu <?php if(Request::segment(3)=='category'): ?>pcoded-trigger active <?php endif; ?>">
                            <a href="javaScript:;" class="nav-link"><span class="pcoded-mtext">0. Item Setup</span></a>
                            <ul class="pcoded-submenu" style="display:<?php if(Request::segment(3)=='category'): ?> block <?php endif; ?>">
                                <?php if(check_access('view-main-category')): ?>
                                <li class="<?php if(Request::segment(4)=='main'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.category')); ?>" >1. Main Groups</a></li><?php endif; ?>

                                <?php if(check_access('view-sub-category')): ?>
                                <li class="<?php if(Request::segment(4)=='sub'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.sub-category')); ?>" >2. Sub Groups</a></li> <?php endif; ?>

                                <?php if(check_access('view-child-category')): ?>
                                <li class="<?php if(Request::segment(4)=='child'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.child-category')); ?>" >3. Child Groups</a></li><?php endif; ?>

                                <?php if(check_access('group-ordering')): ?>
                                <li class="<?php if(Request::segment(4)=='ordering'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.category-ordering')); ?>" >4. Group Ordering</a></li><?php endif; ?>

                                <?php if(check_access('view-size-chirt')): ?>
                                <li class="<?php if(Request::segment(4)=='size-chirt'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.size-chirt')); ?>" >5. Size Chirt</a></li><?php endif; ?>

                                <?php if(check_access('view-size-chirt-pdf')): ?>
                                <li class="<?php if(Request::segment(4)=='size-chirt-for-all'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.size-chirt-for-all')); ?>" >6. Size Chirt PDF</a></li><?php endif; ?>

                                <?php if(check_access('view-product-variation')): ?>
                                <li class="<?php if(Request::segment(4)=='variant'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.variant')); ?>" >7. Variations</a></li><?php endif; ?>
                            </ul>
                        </li>

                        <?php if(check_access('create-product')): ?>
                        <li class="<?php if(Request::segment(4)=='create'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.product.create')); ?>" >1. Upload Product</a></li> <?php endif; ?>

                        <?php if(check_access('view-product-list')): ?>
                        <li class="<?php if(Request::segment(3)=='product' && Request::segment(4)==''): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.product')); ?>" >2. Product list view</a></li> <?php endif; ?>

                        <?php if(check_access('view-product-highlight')): ?>
                        <li class="<?php if(Request::segment(4)=='highlight'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.highlight')); ?>" >3. Highlights</a></li> <?php endif; ?>

                        <?php if(check_access('view-season')): ?>
                        <li class="<?php if(Request::segment(3)=='season'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.season')); ?>" >4. Seasons</a></li><?php endif; ?>
                    </ul>
                </li> <?php endif; ?>

                <?php if(is_label_has_nay_permissions(2)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='order'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-bar-chart"></i></span><span class="pcoded-mtext">Orders </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='order'): ?>block <?php endif; ?>">
                        <?php if(check_access('create-order')): ?>
                        <li class="<?php if(Request::segment(3)=='create'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.order.create')); ?>" >0. Create an order</a></li> <?php endif; ?>

                        <?php $__currentLoopData = $order_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(check_access('view-order')): ?>
                            <li class="<?php if(Request::segment(3)==$type->id): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.orders',$type->id)); ?>" ><?php echo e($key+1); ?>. <?php echo e($type->title); ?>

                                <span class="pcoded-badge label label-info"><?php echo e($type->orders()->count()); ?></span> </a></li> <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(check_access('view-order')): ?>
                        <li class="<?php if(Request::segment(3)=='all-orders'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.all-orders')); ?>" ><?php echo e($order_types->count() +1); ?>. All orders</a></li> <?php endif; ?>

                    </ul>
                </li> <?php endif; ?>

                <?php if(is_label_has_nay_permissions(14)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='report'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-file-plus"></i></span><span class="pcoded-mtext">Reports </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='report'): ?>block <?php endif; ?>">
                        <?php if(check_access('view-report')): ?>
                            <li class="<?php if(strpos(Request::segment(3), 'order') !== false): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.order-export')); ?>" >1. Export Orders</a></li> 

                            <li class="<?php if(Request::segment(3)=='area-wise-order'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.area-wize-orders')); ?>" >2. Area wize orders</a></li>

                            <li class="<?php if(Request::segment(3)=='addToCart'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.reprt.add-to-cart')); ?>" >3. Add to Cart</a></li>
                            
                            <li class="<?php if(strpos(Request::segment(3),'customer') !== false): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.customer-order')); ?>" >4. Customer reports</a></li>
                            <li class="<?php if(Request::segment(3)=='order-durations'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.order-durations')); ?>" >5. Order Durations</a></li>
                        
                            <li class="<?php if(Request::segment(3)=='last-week-orders'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.last-week-orders')); ?>" >6. Order Progress Report </a></li>

                            <li class="<?php if(Request::segment(3)=='company-report'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.courier.company.report',0)); ?>" >7. Courier order Report </a></li>

                            <li class="<?php if(Request::segment(3)=='single-product-report'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.single-product-report')); ?>" >8. Single product Report </a></li>
                            
                            <li class="<?php if(Request::segment(3)=='sslcommerz'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.sslcommerz-orders')); ?>" >9. Online payment Report </a></li>
                        <?php endif; ?>
                    </ul>
                </li> <?php endif; ?>

                <?php if(is_label_has_nay_permissions(3)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='courier'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-truck"></i></span><span class="pcoded-mtext">Courier<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='courier'): ?>block <?php endif; ?>">
                        <?php if(check_access('ready-order-for-shipment')): ?>
                        <li class="<?php if(Request::segment(3)=='ready-to-ship'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.ready-to-ship')); ?>" >1. Read to Ship</a></li> <?php endif; ?>

                        <?php if(check_access('view-courier-company')): ?>
                        <li class="<?php if(Request::segment(3)=='companies'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.couriers')); ?>" >2. Courier Companies</a></li><?php endif; ?>

                        <?php if(check_access('view-courier-representative')): ?>
                        <li class="<?php if(Request::segment(3)=='company-man'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.couriers.man')); ?>" >3. Company Mans</a></li><?php endif; ?>

                        <?php if(check_access('view-courier-zone')): ?>
                        <li class="<?php if(Request::segment(3)=='zone'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.courier.zone')); ?>" >4. Courier Zones </a></li><?php endif; ?>

                        
                        <li class="<?php if(Request::segment(3)=='dhl-setup'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.dhl-setup')); ?>" >5. DHL Setup </a></li>
                        
                    </ul>
                </li> <?php endif; ?>


                <?php if(is_label_has_nay_permissions(4)): ?>
                <li class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='ad'): ?>pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fab fa-adversal"></i></span><span class="pcoded-mtext">Promotion Setup </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='ad'): ?> block <?php endif; ?>">
                        <?php if(check_access('view-coupons')): ?>
                        <li class="<?php if(Request::segment(3)=='coupon'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.coupon')); ?>">Coupons</a></li><?php endif; ?>

                        <?php if(check_access('view-invoice-discount')): ?>
                        <li class="<?php if(Request::segment(3)=='invoice-discount'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.invoice-discount')); ?>" >Invoice Discount</a></li><?php endif; ?>

                        <?php if(check_access('view-promotion')): ?>
                        <li class="pcoded-hasmenu <?php if(Request::segment(3)=='promotion'): ?>active <?php endif; ?>"><a href="#!" class="">Promotion<sub>s</sub></a>
                            <ul class="pcoded-submenu" style="display:<?php if(Request::segment(3)=='promotion'): ?> block <?php endif; ?>">
                                <?php $__currentLoopData = $promotion_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="<?php if($type->id==Request::segment(4)): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.promotion', $type->id)); ?>" ><?php echo e($key+1); ?>. <?php echo e($type->title); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li> <?php endif; ?>
                        <?php if(check_access('view-campaign')): ?>
                        <li class="<?php if(Request::segment(3)=='campaign'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.campaign')); ?>">Campaigns</a></li><?php endif; ?>

                        <?php if(check_access('view-banner')): ?>
                        <li class="<?php if(Request::segment(3)=='banner'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.banner')); ?>">Banners</a></li><?php endif; ?>
                    </ul>
                </li> <?php endif; ?>

                <?php if(is_label_has_nay_permissions(5)): ?>
                <li class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='page-post'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-paperclip"></i></span><span class="pcoded-mtext">Page Post</span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='page-post'): ?>block <?php endif; ?>">
                        <?php if(check_access('view-page-video')): ?>
                        <li class="<?php if(Request::segment(3)=='videos'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.videos')); ?>">1. Videos</a></li><?php endif; ?>

                        <?php if(check_access('view-home-slider')): ?>
                        <li class="<?php if(Request::segment(3)=='slider'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.slider')); ?>">2. Sliders</a></li><?php endif; ?>

                        <?php if(check_access('view-page-post')): ?>
                        <li class="pcoded-hasmenu <?php if(Request::segment(3)=='page'): ?>active <?php endif; ?>"><a href="javaScript:;">3. Page<sub>s</sub></a>
                            <ul class="pcoded-submenu" style="display:<?php if(Request::segment(3)=='page'): ?> block <?php endif; ?>">
                                <?php $__currentLoopData = $page_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="<?php if(Request::segment(4)==$type->id || Request::segment(5)==$type->id): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.page-post', $type->id)); ?>"><?php echo e($key+1); ?>. <?php echo e($type->title); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li> <?php endif; ?>

                        <?php if(check_access('view-policy')): ?>
                        <li class="pcoded-hasmenu <?php if(Request::segment(3)=='policy'): ?>active <?php endif; ?>"><a href="#!" class="">4. Policies</a>
                            <ul class="pcoded-submenu" style="display:<?php if(Request::segment(3)=='policy'): ?> block <?php endif; ?>">
                                <?php $__currentLoopData = $policy_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="<?php if(Request::segment(4)==$type->slug): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.policy', $type->slug)); ?>"><?php echo e($key+1); ?>. <?php echo e($type->title); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li> <?php endif; ?>
                        <?php if(check_access('view-blog')): ?>
                        <li class="pcoded-hasmenu <?php if(Request::segment(3)=='blog'): ?>active <?php endif; ?>"><a href="#!" class="">5. Blog<sub>s</sub></a>
                            <ul class="pcoded-submenu" style="display:<?php if(Request::segment(3)=='blog'): ?> block <?php endif; ?>">
                                <?php if(check_access('create-blog')): ?>
                                <li class="<?php if(Request::segment(4)=='create'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.blog.create')); ?>" >1. Create blog post</a></li> <?php endif; ?>
                                <li class="<?php if(Request::segment(4)==''): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.blogs')); ?>" >2. Blogs</a></li>
                                <li class="<?php if(Request::segment(4)=='category'): ?>active <?php endif; ?>"><a href="<?php echo e(route('common.blog-category')); ?>" >3. Blog Categories</a></li>
                            </ul>
                        </li> <?php endif; ?>

                        <?php if(check_access('view-faq')): ?>
                        <li class="<?php if(Request::segment(3)=='faq'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.faq')); ?>" >6. FAQs</a></li><?php endif; ?>
                    </ul>
                </li> <?php endif; ?>

                <li class="nav-item pcoded-menu-caption"> <label>Quick Area</label></li>
                <?php if(check_access('view-quick-service')): ?>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php if(Request::segment(2)=='quick-service'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="<?php echo e(route('common.quick-service')); ?>" class="nav-link"><span class="pcoded-micon"><i class="fa fa-tags"></i></span><span class="pcoded-mtext">Quick services</span></a>
                </li> <?php endif; ?>

                <?php if(check_access('view-testimonial')): ?>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php if(Request::segment(2)=='testimonial'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="<?php echo e(route('common.testimonial')); ?>" class="nav-link"><span class="pcoded-micon"><i class="fa fa-comments"></i></span><span class="pcoded-mtext">Testimonials</span></a>
                </li><?php endif; ?>

                <?php if(check_access('view-career')): ?>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php if(Request::segment(2)=='career'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="<?php echo e(route('common.career')); ?>" class="nav-link"><span class="pcoded-micon"><i class="fa fa-graduation-cap"></i></span><span class="pcoded-mtext">Career</span></a>
                </li><?php endif; ?>

                <li class="dashboard Default Ecommerce CRM Analytics Crypto Project">
                    <a class="nav-item" href="<?php echo e(route('common.meta')); ?>">
                        <span class="pcoded-micon"> <i class="fa fa-code menu-icon"></i> </span>
                        <span class="menu-title">Meta info (static)</span>
                    </a>
                </li>

                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item ">
                    <a href="<?php echo e(route('common.sitemap')); ?>" class="nav-link"><span class="pcoded-micon"><i class="fa fa-map"></i></span><span class="pcoded-mtext">Site Map</span></a>
                </li>


                <li class="nav-item pcoded-menu-caption"> <label>Management</label></li>
                <?php if(is_label_has_nay_permissions(8)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='user'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-users"></i></span><span class="pcoded-mtext">User<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='user'): ?>block <?php endif; ?>">
                        <?php if(check_access('view-customer')): ?>
                        <li class="<?php if(Request::segment(3)=='customer'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.customer')); ?>" >1. Customers</a></li> <?php endif; ?>

                        <li class="pcoded-hasmenu <?php if(Request::segment(3)=='employee'): ?>active <?php endif; ?>"><a href="#!" class="">2. Employees</a>
                            <ul class="pcoded-submenu" style="display: <?php if(Request::segment(3)=='employee'): ?> block; <?php else: ?> none <?php endif; ?>">
                                <?php if(check_access('view-staff')): ?>
                                <li class="<?php if(Request::segment(3)=='employee' && Request::segment(4)==''): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.employee')); ?>" >A. Employee data</a></li> <?php endif; ?>

                                <?php if(check_access('view-staff-dept')): ?>
                                <li class="<?php if(Request::segment(4)=='category'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.employee-category')); ?>" >B. Employee Departments</a></li> <?php endif; ?>
                            </ul>
                        </li>
                        <?php if(check_access('view-supplier')): ?>
                        <li class="<?php if(Request::segment(3)=='supplier'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.supplier')); ?>" >3. Supliers</a></li><?php endif; ?>

                        <?php if(Auth::user()->user_type_id==1 || Auth::user()->user_type_id==2): ?>
                            <li class="<?php if(Request::segment(3)=='admin'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.admin')); ?>" >4. Administrators</a></li>
                            <li class="<?php if(Request::segment(3)=='user-types'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.user-types')); ?>" >5. Access Labels</a></li>
                        <?php else: ?>
                            <?php if(check_access('view-access-label')): ?>
                            <li class="<?php if(Request::segment(3)=='user-types'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.user-types')); ?>" >4. Access Labels</a></li><?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </li> <?php endif; ?>

                <?php if(is_label_has_nay_permissions(9)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='settings'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Setting<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='settings'): ?>block <?php endif; ?>">
                        <?php if(check_access('system-settings')): ?>
                        <li class="<?php if(Request::segment(3)=='system-settings'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.system-settings')); ?>" >1. System Settings</a></li><?php endif; ?>

                        <?php if(check_access('quick-settings')): ?>
                        <li class="<?php if(Request::segment(3)=='quick-setting'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.quick-setting')); ?>" >2. Quick Settings</a></li> <?php endif; ?>

                        <?php if(check_access('view-social-media')): ?>
                        <li class="<?php if(Request::segment(3)=='social-settings'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.social-settings')); ?>" >3. Social Media</a></li><?php endif; ?>

                        <?php if(check_access('view-currency')): ?>
                        <li class="<?php if(Request::segment(3)=='currency' || Request::segment(3)=='dollar'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.currency')); ?>" >4. Country</a></li><?php endif; ?>

                        <?php if(check_access('order-setup')): ?>
                        <li class="<?php if(Request::segment(3)=='order-status'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.order-status')); ?>" >5. Order Setup</a></li> <?php endif; ?>

                        <?php if(check_access('mail-config')): ?>
                        <li class=""><a href="javaScript:;" >Mail Configuration</a></li> <?php endif; ?>
                    </ul>
                </li> <?php endif; ?>

                <?php if(is_label_has_nay_permissions(10)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='payment'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-dollar-sign"></i></span><span class="pcoded-mtext">Payments<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='payment'): ?>block <?php endif; ?>">
                        <?php if(check_access('view-payment-method')): ?>
                        <li class="<?php if(Request::segment(3)=='payment-method'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.payment-gateway')); ?>" >1. Payment Methods</a></li><?php endif; ?>

                        <?php if(check_access('view-payment-type')): ?>
                        <li class="<?php if(Request::segment(3)=='payment-type'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.payment-type')); ?>" >2. Payment Types</a></li> <?php endif; ?>
                    </ul>
                </li> <?php endif; ?>


                <?php if(is_label_has_nay_permissions(11)): ?>
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu <?php if(Request::segment(2)=='area'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-map-pin"></i></span><span class="pcoded-mtext">Area & Zone<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: <?php if(Request::segment(2)=='area'): ?>block <?php endif; ?>">
                        <?php if(check_access('view-area')): ?>
                        <li class="<?php if(Request::segment(3)=='' && Request::segment(2)=='area'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.area')); ?>?country=2" >1. Area Setup</a></li><?php endif; ?>

                        <?php if(check_access('view-zone')): ?>
                        <li class="<?php if(Request::segment(3)=='zone'): ?> active <?php endif; ?>"><a href="<?php echo e(route('common.area.zone')); ?>" >2. Zone Setup</a></li><?php endif; ?>
                    </ul>
                </li><?php endif; ?>

                <?php if(Auth::user()->user_type_id==1 || Auth::user()->user_type_id==2): ?>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php if(Request::segment(2)=='permissions'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="<?php echo e(route('common.permissions')); ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-crop"></i></span><span class="pcoded-mtext">Permissions</span></a>
                </li><?php endif; ?>

                <?php if(check_access('view-outlet')): ?>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php if(Request::segment(2)=='showroom'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="<?php echo e(route('common.showroom')); ?>" class="nav-link"><span class="pcoded-micon"><i class="fa fa-store"></i></span><span class="pcoded-mtext">Outlets</span></a>
                </li><?php endif; ?>

                <?php if(check_access('database-backup')): ?>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item <?php if(Request::segment(2)=='backup'): ?> pcoded-trigger <?php endif; ?>">
                    <a href="<?php echo e(route('common.get-backup')); ?>" class="nav-link"><span class="pcoded-micon"><i class="fa fa-database"></i></span><span class="pcoded-mtext">Database BackUp</span></a>
                </li> <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/includes/left_nav.blade.php ENDPATH**/ ?>