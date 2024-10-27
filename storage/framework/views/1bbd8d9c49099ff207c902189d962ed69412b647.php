<?php $__env->startSection('content'); ?>
    <div class="breadcrumb">
        <div class="container">
            <ul class="list-unstyled d-flex align-items-center m-0">
                <li><a href="/">Home</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4">
                            <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                        </g>
                    </svg>
                </li>
                <li>Login</li>
            </ul>
        </div>
    </div>

    <div class="login-page mt-100" id="formArea">
        <div class="container">
            <?php if(request()->get('r')=='no-access'): ?>
                <div class="alert alert-dismissible fade show" role="alert">
                    <span class="required"> <b>Warning:</b>  You have expired logoin duration</span>
                </div>
            <?php endif; ?>

            <form class="login-form common-form mx-auto mb-5 border border-info" id="loginForm" action="<?php echo e(route('login')); ?>" method="post"><?php echo csrf_field(); ?>
                <?php if(session('status')): ?>
                    <div class="alert alert-info">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php elseif($errors->has('email')): ?>
                    <div class="alert alert-danger">
                        <?php echo e($errors->first('email')); ?>

                    </div>
                <?php endif; ?>
                <div class="section-header mb-3">
                    <h2 class="section-heading text-center">Login</h2>
                </div>
                <div class="row">
                    <div class="send_alert"></div>
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Phone No. or email address</label>
                            <input type="text" class="" name="username" value="<?php echo e(old('username')); ?>"/>
                            <span class="text-danger error errorUsername"></span>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Password</label>
                            <input type="password"  class="" name="password" />
                            <span class="text-danger error errorPassword"></span>
                        </fieldset>
                    </div>

                    <div class="col-12">
                        <fieldset>
                            <div class="form-checkbox d-flex">
                                <input id="remeberMe" style="width:28px;height:20px;" type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> >
                                <label for="remeberMe" class="form-check-label ms-2">  <?php echo e(__('Remember Me')); ?> </label>
                            </div>
                        </fieldset>
                    </div>

                    <?php
                        $previousUrl = url()->previous();
                        $path = parse_url($previousUrl, PHP_URL_PATH);
                        $parts = explode('/', $path);
                        $lastPart = end($parts);
                    ?> 

                    <input type="hidden" name="previousRoute" value="<?php echo e($lastPart); ?>">

                    <div class="col-12">
                        <button type="submit" class="btn-primary d-block mt-2 btn-signin">SIGN IN</button>
                        <a href="<?php echo e(route('password.request')); ?>" class="text_14 d-block mt-4 text-end">Forgot your password?</a>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="col-md-12">
                            <a href="<?php echo e(url('auth/google')); ?>" class="btn-secondary mt-2 btn-signin">
                                <strong><i class="fab fa-google"></i> Login With Google</strong>
                            </a>
                            <a class="btn-secondary mt-2 btn-signin" href="<?php echo e(url('auth/facebook')); ?>" id="btn-fblogin">
                                <strong><i class="fab fa-facebook"></i> Login With Facebook</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> 
<?php $__env->stopSection(); ?>



<?php $__env->startPush('scripts'); ?>
    <script>
       $(function() {
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                $("[type='submit']").html(' Loading...');$('.send_alert').html('');
                $("[type='submit']").prop('disabled',true);
                var form = $(this);var url = form.attr('action');
                var html = '';
                $.ajax({
                    url:url, method:"post", data: new FormData(this),
                    contentType: false,cache:false, processData: false,
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        if(data.errors) {
                            html = '<div class="alert alert-warning fade show mb-0" role="alert"><strong class="text-danger">Warning: </strong>';
                            for(var count = 0; count < data.errors.length; count++)
                            { html += data.errors[count];break;}
                            html += '</div>';
                        }

                        if(data.success){
                            var url = $("#url").val();
                            html = '<div class="alert alert-success fade show mb-0" role="alert"><strong class="text-info">Success: </strong> ' + data.success +'</div>';
                            if(data.route !='' && data.route!='login' && data.route!=null){
                                window.location.href = url +'/'+ data.route;
                            }else window.location.href = url +'/dashboard';
                        }
                        if(data.error){
                            html = '<div class="alert alert-danger fade show mb-0" role="alert"><strong class="text-info">Error: </strong> ' + data.error +'</div>';
                        }
                        $('.send_alert').html(html);
                    },
                    error: function(xhr, status, error) {
                        html = '<div class="alert alert-danger fade show mb-0" role="alert"><strong class="text-info">Error: </strong> ' + error +'</div>';
                        $('.send_alert').html(html);
                    }
                });
                $("[type='submit']").text('SIGN IN');
                $("[type='submit']").prop('disabled',false);
            });
        });

    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/auth/login.blade.php ENDPATH**/ ?>