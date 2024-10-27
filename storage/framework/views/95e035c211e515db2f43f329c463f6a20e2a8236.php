<?php
    $metas = \DB::table('metas')->where('pageFor','career');
    $meta = \DB::table('metas')->where(['pageFor'=>'career', 'type'=>'title']);

    $metaTitle = 'Career | '.request()->get('system_title');
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

<?php $__env->startSection('title', $metaTitle); ?>

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
            <li>Career</li>
        </ul>
    </div>
</div>


<div class="article-page mt-100">
    <div class="container mt-5 mb-5">
        <?php $__currentLoopData = $careers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $description = str_replace('&amp;', '', strip_tags(str_replace('&nbsp;','',$item->description))); ?>
            <div class="row pb-md-3 pb-sm-3">
                <div class="col-md-12 p-4 mb-2 border border-warning">
                    <header class="jumbotron">
                        <a href="/career-job/<?php echo e($item->slug); ?>"><h1><?php echo e($item->title); ?></h1></a>
                        <p>Deadline: <?php echo e(date('d M, Y',strtotime($item->last_date))); ?></p>
                    </header>

                    <?php if(strlen($description) >120): ?>
                        <?php echo e(mb_substr($description, 0, 120)); ?>

                    <?php else: ?>
                        <?php echo e($description); ?>

                    <?php endif; ?>
                    <a href="<?php echo e(route('career-job',[app()->getLocale(), $item->slug])); ?>" class="btn-primary float-end">Apply Now</a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($careers->count()<1): ?>
            <p class="text-center p-md-5 text-warning border border-warning">No <b>Job post</b> is activated now. Please check after some while!</p>
        <?php endif; ?>
        
    </div>
</div>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/career.blade.php ENDPATH**/ ?>