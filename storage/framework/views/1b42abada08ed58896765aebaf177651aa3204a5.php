<?php
    $metas = \DB::table('metas')->where('pageFor','blog');
    $meta = \DB::table('metas')->where(['pageFor'=>'blog', 'type'=>'title']);

    $metaTitle = 'Blog - '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $meta->where('type','title')->pluck('description')->first();
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
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"
                            fill="#000" />
                    </g>
                </svg>
            </li>
            <li>Blog</li>
        </ul>
    </div>
</div>

<div class="blog-page mt-100">
    <div class="blog-page-wrapper">
        <div class="container">
            <div class="row">
                <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $cat = $blog->blog_categories()->orderBy('id','DESC')->first(); ?>
                <div class="col-lg-4 col-md-6 col-12" data-aos="fade-up" data-aos-duration="700">
                    <div class="article-card bg-transparent p-0 shadow-none">
                        <a class="article-card-img-wrapper" href="<?php echo e(route('news',[app()->getLocale(), $blog->slug])); ?>">
                            <img src="<?php echo e(url('storage').'/'.$blog->photo); ?>" alt="<?php echo e($blog->title); ?>" class="article-card-img rounded">

                            <span class="article-tag article-tag-absolute rounded"><?php echo e($cat->title); ?></span>
                           
                        </a>
                        <p class="article-card-published text_12 d-flex align-items-center">
                            <span class="article-date d-flex align-items-center">
                                <span class="icon-publish">
                                    <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.46875 0.875V1.59375H0.59375V17.4063H16.4063V1.59375H13.5313V0.875H12.0938V1.59375H4.90625V0.875H3.46875ZM2.03125 3.03125H3.46875V3.75H4.90625V3.03125H12.0938V3.75H13.5313V3.03125H14.9688V4.46875H2.03125V3.03125ZM2.03125 5.90625H14.9688V15.9688H2.03125V5.90625ZM6.34375 7.34375V8.78125H7.78125V7.34375H6.34375ZM9.21875 7.34375V8.78125H10.6563V7.34375H9.21875ZM12.0938 7.34375V8.78125H13.5313V7.34375H12.0938ZM3.46875 10.2188V11.6563H4.90625V10.2188H3.46875ZM6.34375 10.2188V11.6563H7.78125V10.2188H6.34375ZM9.21875 10.2188V11.6563H10.6563V10.2188H9.21875ZM12.0938 10.2188V11.6563H13.5313V10.2188H12.0938ZM3.46875 13.0938V14.5313H4.90625V13.0938H3.46875ZM6.34375 13.0938V14.5313H7.78125V13.0938H6.34375ZM9.21875 13.0938V14.5313H10.6563V13.0938H9.21875Z"fill="#00234D" />
                                    </svg>
                                </span>
                                <span class="ms-2"><?php echo e(date('F m, Y',strtotime($blog->created_at))); ?></span>
                            </span>
                            <span class="article-author d-flex align-items-center ms-4">
                                <span class="icon-author"><svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 0.59375C4.72888 0.59375 2.46875 2.85388 2.46875 5.625C2.46875 7.3573 3.35315 8.89587 4.69238 9.80274C2.12903 10.9033 0.3125 13.447 0.3125 16.4063H1.75C1.75 13.2224 4.31616 10.6563 7.5 10.6563C10.6838 10.6563 13.25 13.2224 13.25 16.4063H14.6875C14.6875 13.447 12.871 10.9033 10.3076 9.80274C11.6469 8.89587 12.5313 7.3573 12.5313 5.625C12.5313 2.85388 10.2711 0.59375 7.5 0.59375ZM7.5 2.03125C9.49341 2.03125 11.0938 3.63159 11.0938 5.625C11.0938 7.61841 9.49341 9.21875 7.5 9.21875C5.50659 9.21875 3.90625 7.61841 3.90625 5.625C3.90625 3.63159 5.50659 2.03125 7.5 2.03125Z" fill="#00234D" />
                                    </svg>
                                </span>
                                <span class="ms-2">Admin</span>
                            </span>
                        </p>
                        <h2 class="article-card-heading heading_18">
                            <a class="heading_18" href="<?php echo e(route('news',[app()->getLocale(), $blog->slug])); ?>"> <?php echo e($blog->title); ?> </a>
                        </h2>
                    </div>
                </div>  
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>             
                
            </div>

            <div class="pagination justify-content-center mt-100">
                <nav>
                    <ul class="pagination m-0 d-flex align-items-center">
                        <li class="item"><?php echo e($blogs->links()); ?></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>            
</div>   


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/blog.blade.php ENDPATH**/ ?>