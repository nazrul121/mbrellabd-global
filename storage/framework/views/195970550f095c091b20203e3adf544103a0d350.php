<?php
    $viewType = \DB::table('settings')->where('type','variation-view-type')->pluck('value')->first();
    $listViewVariationId = \DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
?>
<?php if($highlights->count() >0): ?>
    <?php $__currentLoopData = $highlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $highlight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($highlight->products()->count()>0): ?>
        <div class="featured-collection-section mt-100 home-section overflow-hidden">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-heading"><?php echo e($highlight->title); ?></h2>
                </div>

                <div class="product-container position-relative">
                    <div class="common-slider" data-slick='{
                        "slidesToShow": 5, 
                        "slidesToScroll": 1,
                        "dots": false,
                        "arrows": true,
                        "responsive": [
                        {
                            "breakpoint": 1281,
                            "settings": {
                            "slidesToShow": 3
                            }
                        },
                        {
                            "breakpoint": 768,
                            "settings": {
                            "slidesToShow": 2
                            }
                        }
                        ]
                        }'>

                        <?php $__currentLoopData = $highlight->products()->select('products.id','products.title','products.slug','products.thumbs','products.sale_price','products.net_price')->inRandomOrder()->limit(12)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // dd($product->sale_price);
                            $old = old_price($product->id, $product->sale_price);
                            $new = product_price($product->id, $product->sale_price);
                            $percent = ( ($old - $new) / $old) * 100;
                            if(strtolower(\Session::get('user_currency')->name)=='bdt') $formatNumber = 2;
                            else $formatNumber = 3;
                        ?>
                        <div class="new-item" data-aos="fade-up" data-aos-duration="300">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <a class="hover-switch" href="<?php echo e(route('product',[app()->getLocale(), $product->slug])); ?>">
                                        
                                        
                                        <img class="primary-img img<?php echo e($product->id); ?>" src="<?php echo e(url($product->thumbs)); ?>" alt="<?php echo e($product->title); ?>">
                                    </a>

                                    <?php if($percent >0): ?>
                                        <div class="product-badge">
                                            <span class="badge-label badge-percentage rounded"><?php echo e(number_format($percent,1)); ?> % off</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(is_stock_out($product->id)): ?>
                                        <div class="product-badge">
                                            <span class="p-2 bg-warning">Stock out</span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="product-card-action product-card-action-2 justify-content-center">
                                        <a href="#quickview-modal" class="action-card action-quickview" data-bs-toggle="modal" data-product_id="<?php echo e($product->id); ?>">
                                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 0C15.5117 0 20 4.48828 20 10C20 12.3945 19.1602 14.5898 17.75 16.3125L25.7188 24.2812L24.2812 25.7188L16.3125 17.75C14.5898 19.1602 12.3945 20 10 20C4.48828 20 0 15.5117 0 10C0 4.48828 4.48828 0 10 0ZM10 2C5.57031 2 2 5.57031 2 10C2 14.4297 5.57031 18 10 18C14.4297 18 18 14.4297 18 10C18 5.57031 14.4297 2 10 2ZM11 6V9H14V11H11V14H9V11H6V9H9V6H11Z" fill="#00234D" />
                                            </svg>
                                        </a>

                                        <a href="javaScript:;" class="action-card action-wishlist" data-product_id="<?php echo e($product->id); ?>" data-key="<?php echo e($key); ?>">
                                            <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path  d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" fill="#00234D" />
                                            </svg>
                                        </a>

                                        <?php if(request()->get('addToCart')=='1'): ?>
                                            <a href="javaScript:;" class="action-card action-addtocart" data-product_id="<?php echo e($product->id); ?>" data-variation="<?php echo e($product->product_variation_options()->count()); ?>">
                                                <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z" fill="#00234D" />
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="product-card-details">
                                    <ul class="color-lists list-unstyled d-flex align-items-center">
                                        <?php $__currentLoopData = $product->product_variation_options()->where('variation_id',$listViewVariationId)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pvo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $thumb = \DB::table('variation_option_photos')->where(['product_id'=>$product->id,'variation_option_id'=>$pvo->variation_option_id])->pluck('thumbs')->first(); ?>
                                        <button type="button" title="<?php echo e($pvo->variation_option->title); ?>" class="colorbtn" thumb="<?php echo e($thumb); ?>" data-product_id="<?php echo e($product->id); ?>" data-option_id="<?php echo e($pvo->variation_option_id); ?>" 
                                            style="<?php if($viewType=='square'): ?>border-radius:0px; <?php endif; ?> 
                                            <?php if(strpos(strtolower(strtolower($pvo->variation_option->title)),'white') !== false || strpos(strtolower(strtolower($pvo->variation_option->code)),'#fff') !== false): ?>border:2px solid #113c41 !important; <?php endif; ?> 
                                            background:<?php echo e($pvo->variation_option->code); ?>; 
                                            <?php if($viewType=='circle'): ?>border-radius:12px; <?php endif; ?>
                                            <?php if(strpos(strtolower(strtolower($pvo->variation_option->title)),'multi') !== false): ?>background-image: linear-gradient(to right, #113c41,white,#e29d1b,blue,red); <?php endif; ?>">
                                        </button> &nbsp; 
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>

                                    <input type="hidden" class="default_thumb<?php echo e($product->id); ?>" value="<?php echo e($product->thumbs); ?>">

                                    <h3 class="product-card-title">
                                        <a href="<?php echo e(route('product',[app()->getLocale(), $product->slug])); ?>"><?php echo e($product->title); ?></a>
                                    </h3>
                                    <div class="product-card-price">
                                        <span class="card-price-regular"><?php echo e(Session::get('user_currency')->currencySymbol); ?></span> <?php echo e(number_format(product_price($product->id, $product->sale_price), $formatNumber)); ?></span>
                                        <?php if($percent >0): ?> <span class="card-price-compare text-decoration-line-through"><?php echo e(Session::get('user_currency')->currencySymbol); ?> <?php echo e(number_format($old,$formatNumber)); ?></span> <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </div>
                    <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<script src="<?php echo e(asset('/assets/js/main.js')); ?>"></script>

<script>
    $('.action-quickview, .quickview-btn').on('click', function(){
        var id = $(this).data('product_id');
        $(".quickViewModal" ).html( 'Working....' );
        // window.open(url + "/modal-product/"+id);
        $.get( "<?php echo e(url(app()->getLocale().'/modal-product')); ?>/"+id, function( data ) {
            $( ".quickViewModal" ).html( data );
        });
        set_modal_background();
    });
    $('.action-addtocart, .addtocart-btn').on('click',function(){
        let id = $(this).data('product_id');
        let variation = $(this).data('variation');
    
        if(variation >0){
            $(".quickViewModal" ).html( 'Working....' );
            $('#quickview-modal').modal('show');
            $.get( "<?php echo e(url(app()->getLocale().'/modal-product')); ?>/"+id, function( data ) {
                $( ".quickViewModal" ).html( data );
            });

            set_modal_background();
        }else{
            $.get("/<?php echo e(app()->getLocale()); ?>/add-to-cart?qty=1&id="+id, function(data, status){
                if(data[1]=='success') {
                    $('#notice').fadeIn('slow');
                    $('#notice').html('&#10003; The item added to wishlist successfully!!');
                    setTimeout(function(){
                        $('.btn-add-to-cart').css('display','block');
                        $('#notice').fadeOut('hide');
                    }, 1000);
                    $(".addToCardNum").html(data[0]); 
                }
                $(".action-addtocart").html('<svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z" fill="#00234D" /></svg>');
            })
        }
        
    });

    $('.action-wishlist, .card-wishlist').on('click',function(){
        let id = $(this).data('product_id');
        $.get("<?php echo e(url('/'.app()->getLocale().'/add-to-wishlist')); ?>/"+id, function( data ) {
            $('#notice').fadeIn('slow');
            $('#notice').html('&#10003; The item added to wishlist successfully!!');
            setTimeout(function(){
                $('.btn-add-to-cart').css('display','block');
                $('#notice').fadeOut('hide');
            }, 1000);
        });
    });

    function set_modal_background(){
        if($('.modal-backdrop').length >1){
            var elementsToRemove = $('.modal-backdrop');
            if (elementsToRemove.length > 1) {
                elementsToRemove.slice(1).remove();
            }
        }
        $('body').css('overflow','auto');
    }
		  

</script>


<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/includes/highlights.blade.php ENDPATH**/ ?>