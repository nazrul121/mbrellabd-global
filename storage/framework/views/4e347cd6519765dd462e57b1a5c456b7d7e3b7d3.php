<?php
    $role = Auth::user()->user_type->title;
    if($role=='superAdmin') $role = 'admin';
?>

<header class="navbar pcoded-header navbar-expand-lg navbar-light">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
        <a href="#" class="b-brand">
            <img src="/storage/<?php echo e(request()->get('header_logo')); ?>" alt="">
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="#!"><i class="feather icon-more-horizontal"></i></a>

    <div class="collapse navbar-collapse bg-white">
        <ul class="navbar-nav mr-auto">
            <li><a href="#!" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">Dropdown</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo e(route('common.product.create')); ?>">Product entry point</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('common.product')); ?>">Product list</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('common.orders',1)); ?>">Pending orders</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('common.send-sms')); ?>">Send SMS</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <form class="main-search" action="<?php echo e(route('common.customer-order')); ?>"><?php echo csrf_field(); ?>
                    <div class="input-group">
                        <input type="text" class="form-control" name="customer" placeholder="Customer Name/ phone" value="<?php echo e(request()->get('customer')); ?>">
                        <a href="#!" class="input-group-append search-close">
                            <i class="feather icon-x input-group-text"></i>
                        </a>
                        <span class="input-group-append search-btn btn btn-primary">
                            <i class="feather icon-search input-group-text"></i>
                        </span>
                    </div>
                </form>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li><a href="<?php echo e(url('/')); ?>" target="_blank"><i class="icon feather icon-home"></i></a></li>
            <li>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                        <div class="dropdown-menu dropdown-menu-right notification">
                        <div class="noti-head">
                        <h6 class="d-inline-block m-b-0">Notifications</h6>
                        <div class="float-right">
                        <a href="#!" class="m-r-10">mark as read</a>
                        <a href="#!">clear all</a>
                        </div>
                        </div>
                        <ul class="noti-body">
                            <li class="n-title">  <p class="m-b-0">NEW</p> </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="/storage/<?php echo e(Auth::user()->$role->photo); ?>">
                                    <div class="media-body">
                                        <p><strong><?php echo e(Auth::user()->user_type->title); ?></strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                                        <p>New ticket Added</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="noti-footer"> <a href="#!">show all</a>  </div>
                    </div>
                </div>
            </li>

            <li><a href="#!" class="displayChatbox"><i class="icon feather icon-mail"></i></a></li>
            <li>
                <div class="dropdown drp-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon feather icon-settings"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            <img src="/storage/<?php echo e(Auth::user()->$role->photo); ?>" class="img-radius" >
                            <span><?php echo e(Auth::user()->$role->first_name); ?> - <small class="text-warning"><?php echo e($role); ?></small></span>

                        </div>
                        <ul class="pro-body">
                            <li><a href="<?php echo e(route('common.system-settings')); ?>" class="dropdown-item"><i class="feather icon-settings"></i> Settings</a></li>
                            <li><a href="<?php echo e(route('common.brand-info')); ?>" class="dropdown-item"><i class="feather icon-umbrella"></i> Brand info</a></li>
                            <li><a href="<?php echo e(route(Auth::user()->user_type->title.'.profile')); ?>" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                            <li><a href="<?php echo e(route('change-password')); ?>" class="dropdown-item"><i class="feather icon-lock"></i> Change Passowrd</a></li>

                            <li>
                                <a href="#" onclick="$('#logout-formH').submit();" class="dropdown-item text-danger"><i class="feather icon-log-out"></i> Logout</a>
                                <form id="logout-formH" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?> </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
<?php /**PATH /var/www/laravelapp/resources/views/common/includes/header.blade.php ENDPATH**/ ?>