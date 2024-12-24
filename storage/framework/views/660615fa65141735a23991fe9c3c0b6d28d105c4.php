<?php

    $metas = \DB::table('metas')->where('pageFor','home');
    $meta = \DB::table('metas')->where(['pageFor'=>'home', 'type'=>'title']);

	$metaTitle = 'Mbrella | A Lifestyle Clothing Brand';
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }

?>
<?php $__env->startPush('meta'); ?>
    <meta property="og:title" content="<?php echo e(request()->get('title')); ?>">
    <meta name="description" content="<?php echo e(\DB::table('general_infos')->where('field','system_description')->pluck('value')->first()); ?>" />
   
    <?php $__currentLoopData = $metas->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <meta property="og:<?php echo e($meta->type); ?>" content="<?php echo e($meta->description); ?>" />
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <meta property="og:url" content="<?php echo e(url()->full()); ?>">
    
    <meta property="og:type" content="website">

<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', $metaTitle); ?>

<?php $__env->startSection('content'); ?>


    <!-- slideshow start -->
    <?php echo $__env->make('includes.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php 
        $invoice = Cache::remember('invoice', 30, function() {
            $ids = \App\Models\Country_invoice_discount::where('country_id', session('user_currency')->id)->select('invoice_discount_id')->distinct()->pluck('invoice_discount_id')->toArray();
            return \App\Models\Invoice_discount::whereIn('id', $ids)->where('status', '1') ->select('title','photo')->orderBy('id', 'DESC') ->first();
        });

    ?>
    <?php if($invoice !=null): ?> 
    <div class="single-banner-section overflow-hidden">
        <div class="position-relative overlay pt-2 mt-3 text-center">
            <img src="<?php echo e(url('storage/'.$invoice->photo)); ?>" alt="<?php echo e($invoice->title); ?>">
            <div class="content-absolute content-slide">
                <div class="container height-inherit d-flex align-items-center">
                    <div class="content-box single-banner-content py-4">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php echo $__env->make('includes.promotions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="loadCategory"></div>

    <div class="loadSubCategory"></div>

    <!-- video start -->
    <div class="loadVideo"></div>

    <!-- highlight start -->
    <div class="loadHighlight"></div> 

  
    <?php echo $__env->make('includes.instagram-feed', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>     
   
    <!-- testimonial start -->
    <?php echo $__env->make('includes.testimonial', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- latest blog start -->
    <div class="loadBlogs"></div>

    <!-- quick service -->
    <?php echo $__env->make('includes.quick-service', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('includes.subscribe', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- welcome modal -->
    <?php if($promotions->count() >0): ?>
        <div class="modal fade" tabindex="-1" id="startUpModal" aria-modal="false" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" style="width: fit-content;">
                <div class="modal-content newsletter-modal-content modal-lg">
                    <div class="modal-body">
                        <a href="#close-modal" rel="modal:close" class="close-modal " data-bs-dismiss="modal" aria-label="Close"></a>
                        <a  href="<?php echo e(route('promo-items',[app()->getLocale(), $promotions[0]->slug])); ?>" >
                            <img src="<?php echo e(url('storage/'.$promotions[0]->photo)); ?>" style="border: 4px solid transparent; border-image: linear-gradient(to bottom, <?php echo e($promotions[0]->bg_color); ?>, <?php echo e($promotions[0]->text_color); ?>); border-image-slice: 1;"> 
                        </a>
                    </div>
                </div>
            </div>
        </div> 
    <?php endif; ?> 


<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .example-marquee { position: relative; }
        .content {z-index: 1; position: relative; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

    <script>
        
        $(document).ready(function() {
            $('.loadVideo').load("<?php echo e(route('load-home-video',app()->getLocale())); ?>")
            $('.loadBlogs').load("<?php echo e(route('load-home-blog', app()->getLocale())); ?>")
            $('.loadHighlight').load("<?php echo e(route('load-home-highlight', app()->getLocale())); ?>")
            $('.loadCategory').load("<?php echo e(route('load-home-category', app()->getLocale())); ?>")
            $('.loadSubCategory').load("<?php echo e(route('load-home-subCategory', app()->getLocale())); ?>")
            // var isshow = localStorage.getItem('isshow');
            // if (isshow== null) {
                // localStorage.setItem('isshow', 1);
                $('#startUpModal').modal('show');
            // }

            var elementTop, elementBottom, viewportTop, viewportBottom;

            function isScrolledIntoView(elem) {
                elementTop = $(elem).offset().top;
                elementBottom = elementTop + $(elem).outerHeight();
                viewportTop = $(window).scrollTop();
                viewportBottom = viewportTop + $(window).height();
                return (elementBottom > viewportTop && elementTop < viewportBottom);
            }
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/home.blade.php ENDPATH**/ ?>