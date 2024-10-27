<?php
    $ids = \App\Models\Country_quick_service::where('country_id',session('user_currency')->id)->select('quick_service_id')->distinct()->get()->toArray();
    $quick_services = \DB::table('quick_services')->whereIn('id',$ids)->where('status','1')->select('title','type','type_info','photo','description')->get();
?>

<?php if($quick_services->count()>0): ?>
    <div class="latest-blog-section overflow-hidden home-section mt-5">
        <div class="latest-blog-inner">
            <div class="container">
                <div class="article-card-container position-relative">
                    <div class="common-slider" data-slick='{
                        "slidesToShow":3,  
                        "slidesToScroll": 1,
                        "dots": true, 
                        "arrows": true,
                        "autoplay":true,
                        "autoplaySpeed": 1500,
                        "responsive": [
                            {
                                "breakpoint": 1281, "settings": { "slidesToShow": 2 }
                            },
                            {
                                "breakpoint": 602, "settings": { "slidesToShow": 1}
                            }
                        ]
                        }'>
                        <?php $__currentLoopData = $quick_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php if($q->type=='phone'): ?>tel: <?php elseif($q->type=='email'): ?>mailto: <?php else: ?> <?php endif; ?> <?php echo e($q->type_info); ?>">
                                <div class="article-slick-item">
                                    <div class="article-card bg-transparent p-0 shadow-none">
                                        <div class="col-12">
                                        <div class="trusted-badge rounded bg-dark <?php echo e($key); ?>">
                                        <div class="trusted-icon">
                                            <img class="icon-trusted" alt="<?php echo e($q->title); ?>" src="<?php echo e(url('storage').'/'.$q->photo); ?>">
                                        </div>
                                        <div class="trusted-content">
                                            <h2 class="heading_18 trusted-heading text-white" style="<?php if(strlen($q->title) >25): ?> font-size:16px; <?php endif; ?>"><?php echo e($q->title); ?></h2>
                                            <p class="text_16 trusted-subheading trusted-subheading-3"><?php echo e($q->description); ?></p>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </a>  
                    </div>
                    
                    <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/laravelapp/resources/views/includes/quick-service.blade.php ENDPATH**/ ?>