<?php
    $ids = \App\Models\Country_testimonial::where('country_id',session('user_currency')->id)->select('testimonial_id')->distinct()->get()->toArray();
    $testimonials = \DB::table('testimonials')->whereIn('id',$ids)->where('status','1')->select('name','title','photo','testimonial')->get();
?>
<?php if($testimonials->count()>0): ?>
<div class="newsletter-section mt-3 overflow-hidden">
    <div class="testimonial-section mt-5 overflow-hidden home-section">
        <div class="container" style="background: rgb(237, 237, 237);">
            <div class="row">
                <h2 class="text-center p-3"> Customer Reviews</h2>  
                <div class="col-sm-12" data-aos-duration="700">
                    <div id="customers-testimonials" class="owl-carousel">
                        <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item">
                            <div class="shadow-effect">
                                <img class="img-circle reveiwImg" src="<?php echo e(url('storage').'/'.$review->photo); ?>" alt="<?php echo e($review->name); ?>">
                                <p>
                                    <q><?php echo e($review->title); ?></q> <br>
                                    <?php echo e($review->testimonial); ?>

                                </p>
                            </div>
                            <div class="testimonial-name signature"><?php echo e($review->name); ?></div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

 <?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css" />
    <style>
        .shadow-effect {
		    background: #fff;
		    padding: 20px;
		    border-radius: 4px;
		    text-align: center;
	        border:1px solid #ECECEC;
		    box-shadow: 0 19px 38px rgba(0,0,0,0.10), 0 15px 12px rgba(0,0,0,0.02);
		}
		#customers-testimonials .shadow-effect p {
		    font-family: inherit;
		    font-size: 17px;
		    line-height: 1.5;
		    margin: 0 0 17px 0;
		    font-weight: 300;
		}
		.testimonial-name {
            margin: -17px auto 0;
            display: table;
            width: auto;
            background: #b9b9b9b0;
            padding: 9px 35px;
            border-radius: 12px;
            text-align: center;
            color: #000;
            box-shadow: 0 9px 18px rgba(0,0,0,0.12), 0 5px 7px rgba(0,0,0,0.05);
        }
		#customers-testimonials .item {
		    text-align: center;
		    padding: 5px;
		    opacity: .2;
		    -webkit-transform: scale3d(0.8, 0.8, 1);
		    transform: scale3d(0.8, 0.8, 1);
		    -webkit-transition: all 0.3s ease-in-out;
		    -moz-transition: all 0.3s ease-in-out;
		    transition: all 0.3s ease-in-out;
		}
		#customers-testimonials .owl-item.active.center .item {
		    opacity: 1;
		    -webkit-transform: scale3d(1.0, 1.0, 1);
		    transform: scale3d(1.0, 1.0, 1);
		}
		.owl-carousel .owl-item img {
		    transform-style: preserve-3d;
		    max-width: 100px;
    		margin: 0 auto 17px;
		}
		#customers-testimonials.owl-carousel .owl-dots .owl-dot.active span,#customers-testimonials.owl-carousel .owl-dots .owl-dot:hover span {
		    background: #3190E7;
		    transform: translate3d(0px, -50%, 0px) scale(0.7);
		}
        #customers-testimonials.owl-carousel .owl-dots{
            display: inline-block;
            width: 100%;
            text-align: center;
        }
        #customers-testimonials.owl-carousel .owl-dots .owl-dot{
            display: inline-block;
        }
		#customers-testimonials.owl-carousel .owl-dots .owl-dot span {
		    background: #3190E7;
		    display: inline-block;
		    height: 20px;
		    margin: 0 2px 5px;
		    transform: translate3d(0px, -50%, 0px) scale(0.3);
		    transform-origin: 50% 50% 0;
		    transition: all 250ms ease-out 0s;
		    width: 20px;
		}
    </style>
<?php $__env->stopPush(); ?>




<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        "use strict";
        //  TESTIMONIALS CAROUSEL HOOK
        $('#customers-testimonials').owlCarousel({
            loop: true,
            center: true,
            items: 3,
            margin: 0,
            autoplay: true,
            dots:true,
            autoplayTimeout: 8500,
            smartSpeed: 450,
            responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            1170: {
                items: 3
            }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<style>
    .reveiwImg{
        border-radius: 50%;
        background: linear-gradient(to bottom, #000000, #00bf55);
        border: 5px solid transparent;
        height: 100px;
    }
    
    blockquote::before {
        content: open-quote;
    }
    blockquote::after {
        content: close-quote;
    }
    blockquote {
        quotes: "“" "”" "‘" "’";
        font: 1.3rem/1.7 Georgia, serif
    }
    @font-face {
        src: url('/assets/fonts/JustSignature-92w7.ttf');
        font-family: "Harveyscript";
    }

</style>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/testimonial.blade.php ENDPATH**/ ?>