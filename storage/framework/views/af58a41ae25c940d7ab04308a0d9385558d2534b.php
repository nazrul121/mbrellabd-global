    <?php
        $ids = array();
        $doneStep = 0;
        $lastStatus = null;
        $orderStatus = \App\Models\Order_status::where('action','continue')->get();
        
        $metas = \DB::table('metas')->where('pageFor','track');
        $meta = \DB::table('metas')->where(['pageFor'=>'track', 'type'=>'title']);
        
        $metaTitle = 'Mbrella | Track your ourder';
        if($meta->count() >0){
            $metaTitle = $meta->pluck('description')->first();
        }
    ?>

    <?php $__env->startPush('meta'); ?>
        <meta property="og:url" content="<?php echo e(url()->full()); ?>" />
        <meta property="og:type" content="website">
        <?php $__currentLoopData = $metas->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <meta property="og:<?php echo e($meta->type); ?>" content="<?php echo e($meta->description); ?>" />
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__env->stopPush(); ?>


<?php $__env->startSection('title',$metaTitle); ?>

<?php $__env->startSection('content'); ?>
    <div class="breadcrumb">
        <div class="container">
            <ul class="list-unstyled d-flex align-items-center m-0">
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4">
                            <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000"></path>
                        </g>
                    </svg>
                </li>
                <li>Tracking order</li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="row mt-5">
            <form method="get" class="col-12">
                <div class="row">
                    <div class="col-md-6 offset-md-2 col-sm-6">
                        <input type="text" name="invoice" class="form-control" placeholder="Invoice/Transaction ID" value="<?php echo e(request()->get('invoice')); ?>">
                    </div>

                    <div class="col-md-3 col-sm-5">
                        <button class="btn-primary checkOrder float-end form-control" type="submit">Start Tracking</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-5 truckAra" style="display:none">
            <?php if(request()->get('invoice')): ?>
                <?php if($order !=null): ?>
                    <div class="container">
                        <img class="checkOrderTruck" src="<?php echo e(url('/storage/images/truck2.gif')); ?>" style="height:55px;position:relative;top:57px;z-index:999;" id="truck">
                        <?php $orderStatus = \App\Models\Order_status::where('action','continue')->get();?>
                        <div class="track">
                            <?php $__currentLoopData = $orderStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $count = \DB::table('order_status_changes')->where(['order_id'=>$order->id,'order_status_id'=>$status->id])->count();
                                $ids[] = $status->id;
                                if($count > 0){
                                    $doneStep = $doneStep+1;
                                } ?>

                                <div class="step <?php if($count > 0): ?>active <?php endif; ?>">
                                    <span class="icon"> <?php if($count >0): ?><i class="fa fa-check"></i> <?php else: ?> <i class="fa fa-lock"></i> <?php endif; ?> </span> <span class="text"><?php echo e($status->title); ?> </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $lastStatus = \App\Models\Order_status_change::where('order_id',$order->id)->whereNotIn('order_status_id',$ids)->first();?>
                            <?php if($lastStatus !=null): ?>
                                <div class="step active">
                                    <span class="icon"> <?php if($lastStatus->order_status->relational_activity=='delivered'): ?><i class="fa fa-check"></i> <?php else: ?> <i class="fa fa-times text-danger"></i> <?php endif; ?>  </span> <span class="<?php if($lastStatus->order_status->relational_activity=='delivered'): ?>text-success <?php else: ?> text-danger <?php endif; ?> text"><?php echo e($lastStatus->order_status->title); ?> </span>
                                </div>
                            <?php else: ?> 
                                <div class="step">
                                    <span class="icon"><i class="fa fa-lock"></i> </span> <span class="text"> Final step</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="hidden" name="stepDone" value="<?php echo e($doneStep); ?>">
                    <input type="hidden" name="steps" value="<?php echo e($orderStatus->count()); ?>">

                    <div class="row mt-4"> <br>
                        <?php if($order !=null): ?>
                            <p class="text-info text-center">Your order status is: <b><?php echo e($order->order_status->title); ?> </b> <br>
                            <span class="text-primary">Order date: <?php echo e(date('l, jS \of F, Y , h:i:s A',strtotime($order->created_at))); ?></span>
                            </p>
                        <?php else: ?>
                            <p class="text-danger text-center p-5 bg-5">Please put your valid <code>invoice</code>/<code>transaction</code> Number </p>
                        <?php endif; ?>

                    </div>
                <?php else: ?> 
                    <p class="text-center text-warning bg-dark p-md-4"><b>Invoice No</b> / <b>Transaction ID</b> is not correct one</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

   
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <style>

        .container {
            margin-bottom: 50px
        }

        .track {
            position: relative;
            background-color: #ddd;
            height: 7px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px
        }

        .track .step {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            width: 25%;
            margin-top: -18px;
            text-align: center;
            position: relative
        }

        .track .step.active:before {
            background: orange
        }

        .track .step::before {
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 18px
        }

        .track .step.active .icon {
            background:orange;
            color: #fff
        }

        .track .icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            border-radius: 100%;
            background: #ddd
        }

        .track .step.active .text {
            font-weight: 400;
            color: #000
        }

        .track .text {
            display: block;
            margin-top: 7px
        }

        .itemside {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%
        }

        .itemside .aside {
            position: relative;
            -ms-flex-negative: 0;
            flex-shrink: 0
        }

        .img-sm {
            width: 80px;
            height: 80px;
            padding: 7px
        }

        ul.row,
        ul.row-sm {
            list-style: none;
            padding: 0
        }

        .itemside .info {
            padding-left: 15px;
            padding-right: 7px
        }

        .itemside .title {
            display: block;
            margin-bottom: 5px;
            color: #212529
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        .btn-warning {

            background-color: orange;
            border-color: orange;
            border-radius: 1px
        }

        .btn-warning:hover {

            background-color: orange;
            border-color: orange;
            border-radius: 1px
        }
    </style>

    <script>
        $(function(){
            $('.truckAra').slideDown(200);
            var percent = $('[name=stepDone]').val();
            var newPercent = percent * 18;

            $("#truck").animate({ left:newPercent+'%'}, 2000);

            $('.checkOrder').on('click',function (){
                $(this).text('Tracking your data...')
            });

            <?php if($order !=null): ?>
             <?php if($lastStatus !=null): ?>
                setTimeout(() => {
                    $("#truck").animate({
                        top:"101px",
                        left:newPercent - 1 +'%',
                    }, 1500);

                    $("#truck").animate({left:"92%"}, 400);
                    $("#truck").animate({ transform: "rotate(0deg)", top:"57px"}, 1000);

                }, 2000);


             <?php endif; ?>
            <?php endif; ?>
        });

        function AnimateRotate(d){
            $({deg:0}).animate({deg: d}, {
                step: function(now, fx){
                    $("#truck").css({
                        transform: "rotate(" +now + "deg)"
                    });
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?> 

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/trucking.blade.php ENDPATH**/ ?>