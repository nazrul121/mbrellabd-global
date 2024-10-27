<?php
    $metas = \DB::table('metas')->where('pageFor','showroom')->get();
    $meta = \DB::table('metas')->where(['pageFor'=>'order-placed', 'type'=>'title']);
    
    $metaTitle = 'outlets | '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }

?>

<?php $__env->startSection('title',$metaTitle); ?>

<?php $__env->startPush('meta'); ?>
    <meta property="og:url" content="<?php echo e(url()->full()); ?>" />
    <meta property="og:type" content="website">
    <?php $__currentLoopData = $metas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <meta property="og:<?php echo e($meta->type); ?>" content="<?php echo e($meta->description); ?>" />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopPush(); ?>


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
                <li>Outlets</li>
            </ul>
            <select name="district" class="districtSearach">
                <option value="">Choose district</option>
                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item->id); ?>" <?php if(request()->get('district')==$item->id): ?>selected <?php endif; ?> ><?php echo e($item->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            
        </div>
    </div>


    <?php $key=1;?>

    <div class="banner-section mt-100 overflow-hidden">
        <div class="banner-section-inner">
            <div class="container">
                <div class="row mb-3">
                    <?php $__currentLoopData = $showrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$showroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="<?php echo e($key+660); ?>">
                        <div class="row m-1 p-2 bg-5">
                            <div class="col-md-4 col-sm-5 mt-3">
                                <a target="_blank" href="<?php echo e(url('storage').'/'.$showroom->photo); ?>">
                                    <img class="p-3" src="<?php echo e(url('storage').'/'.$showroom->photo); ?>" style="max-width:95%"></a>
                            </div>
                            <div class="col-md-8 col-sm-7 mt-md-4 mt-sm-4">
                                <h4><?php echo e($showroom->title); ?></h4>
                                <p>Contact No: <?php echo e($showroom->phone); ?> <br>
                                Address: <?php echo e($showroom->location); ?></p>
                                <?php if($showroom->embed_code !=null): ?>
                                <button class="p-2 mapView" id="<?php echo e($showroom->id); ?>"><i class="fa fa-street-view " style="font-size:18px"></i> View on Google Map</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>  <?php $key++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="trackingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="preload text-center text-secondary"></div>
                <iframe src="" width="600" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<style>
    .districtSearach{
        position: relative;
        right: 0;top: -25px;
        padding: 3px; float: right;
        margin-bottom: -25px;
        display: block;
    }
</style>
    <script>
        $(function(){
            var url = $("#url").val();

            $('.districtSearach').on('change', function(){
                var id = $(this).val();
                var currentUrl = window.location.href;
                var url = new URL(currentUrl);

                // Set or update the parameter in the URL
                url.searchParams.set('district', id);

                // Reload the page with the updated URL
                window.location.href = url;
            })

            $('.mapView').on('click',function(){
                $('.preload').html('<p><br/>Loading Google map. Please wait...<br/></p>');
                $('iframe').css('display','none');
                var id = $(this).attr('id');
                $('#trackingModal').modal('show');
        
                $('.modal').css('margin-top','5%');
              
                setTimeout(() => {
                    $.get(url+"/showroom-map/"+id, function(data, status){
                        $('iframe').attr('src',data);
                        $('.preload').html('');
                        $('iframe').css('display','block');
                    });
                }, 200);
            })

        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/showrooms.blade.php ENDPATH**/ ?>