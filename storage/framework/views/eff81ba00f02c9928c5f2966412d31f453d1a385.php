<?php
    $max = max_price(\DB::table('products')->max('sale_price'));
    if(request()->get('price') !=''){
        $max = explode('-',request()->get('price'))[1];
    }
    
    $min = explode('-',request()->get('price'))[0];// \DB::table('products')->min('sale_price');
    if($max <1) $max += 3;
    $viewType = \DB::table('settings')->where('type','variation-view-type')->pluck('value')->first();
    $listViewVariationId = \DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();

    include_once(app_path('Helper/Sidebar.php'));
    $useragent=$_SERVER['HTTP_USER_AGENT'];

?>
<?php $__env->startSection('title',$title.' | '.request()->get('system_title')); ?>

<?php $__env->startPush('meta'); ?>
    <meta property="og:url" content="<?php echo e(url()->full()); ?>" />
    <meta property="og:type" content="website">
    
    <?php if($meta_data->count() >0): ?>
        <?php $__currentLoopData = $meta_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <meta property="og:<?php echo e($item->meta_type); ?>" content="<?php echo e($item->meta_content); ?>" />
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('includes.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="collection mt-100">
        <div class="container">
            <div class="row flex-row-reverse">
                <!-- product area start -->
                <div class="col-lg-9 col-md-12 col-12">
                    <?php echo $__env->make('includes.product.sorting',['page'=>'product', 'title'=>$title, 'class'=>''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
                    <div class="collection-product-container">
                        <div class="row">
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // dd($product->sale_price);
                                $old = old_price($product->id, $product->sale_price);
                                $new = product_price($product->id, $product->sale_price);
                              
                                if($old !=0 || $new !=0){
                                    $percent = ( ($old - $new) / $old) * 100;
                                }else $percent = 0;
                                
                                if(strtolower(\Session::get('user_currency')->name)=='bdt') $formatNumber = 2;
                                else $formatNumber = 3;
                            ?>

                            <div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-duration="<?php echo e($key+660); ?>">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <a class="hover-switch" href="<?php echo e(route('product',[app()->getLocale(), $product->slug])); ?>">
                                            <img class="primary-img img<?php echo e($product->id); ?>" src="<?php echo e($product->thumbs); ?>" alt="product-img">
                                        </a>
                                        <?php if($percent >0): ?>
                                            <div class="product-badge">
                                                <span class="badge-label badge-percentage rounded">-<?php echo e(number_format($percent)); ?> %</span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(is_stock_out($product->id)): ?>
                                            <div class="product-badge">
                                                <span class="p-2 bg-warning">Stock out</span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="product-card-action product-card-action-2 justify-content-center">
                                            <a href="#quickview-modal" class="action-card action-quickview" data-product_id="<?php echo e($product->id); ?>" data-bs-toggle="modal">
                                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 0C15.5117 0 20 4.48828 20 10C20 12.3945 19.1602 14.5898 17.75 16.3125L25.7188 24.2812L24.2812 25.7188L16.3125 17.75C14.5898 19.1602 12.3945 20 10 20C4.48828 20 0 15.5117 0 10C0 4.48828 4.48828 0 10 0ZM10 2C5.57031 2 2 5.57031 2 10C2 14.4297 5.57031 18 10 18C14.4297 18 18 14.4297 18 10C18 5.57031 14.4297 2 10 2ZM11 6V9H14V11H11V14H9V11H6V9H9V6H11Z" fill="#00234D" />
                                                </svg>
                                            </a>

                                            <a href="javaScript:;" class="action-card action-wishlist " data-product_id="<?php echo e($product->id); ?>" >
                                                <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" fill="#00234D" />
                                                </svg>
                                            </a>

                                            <?php if(request()->get('addToCart')=='1'): ?>
                                                <a href="javaScript:;" class="action-card action-addtocart" data-variation="<?php echo e($product->product_variation_options()->count()); ?>" data-product_id="<?php echo e($product->id); ?>">
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
                                        <h3 class="product-card-title">
                                            <a href="<?php echo e(route('product',[app()->getLocale(), $product->slug])); ?>"><?php echo e($product->title); ?></a>
                                        </h3>
                                        <div class="product-card-price">
                                            <span class="card-price-regular"><?php echo e(Session::get('user_currency')->currencySymbol); ?> <?php echo e(number_format($new,$formatNumber)); ?></span>
                                            <?php if($new < $old ): ?>
                                                <span class="card-price-compare text-decoration-line-through amount"><?php echo e(Session::get('user_currency')->currencySymbol); ?><?php echo e(number_format($old,$formatNumber)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="row mt-5">
                           <ul class=" paginate d-flex justify-content-center">
                                <?php echo e($products->appends(request()->input())->links()); ?>

                           </ul>
                        </div>
                    </div>

                </div>
                

                <!-- sidebar start -->
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="collection-filter filter-drawer">
                        <div class="filter-widget d-lg-none d-flex align-items-center justify-content-between">
                            <h5 class="heading_24">Filter By</h4>
                            <button type="button" class="btn-close text-reset filter-drawer-trigger d-lg-none"></button>
                        </div>
                        <?php echo $__env->make('includes.product.filter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        var cgory= "", colr ="", price=""; sorting="newest";
        let current_url = window.location.pathname;
        // alert(current_url);
        //category checkbox
        $('.checkCategory input[type=checkbox],.colors input[type=checkbox]').on('change',function(){
            var groups = get_groups();
            var colors = get_colors();
            // debugger
            cgory='?category='+groups
            colr='&color='+colors
            price='&price='+price
            sorting='&sorting='+sorting
            window.location = current_url+cgory+colr+price+sorting;
        });

        $('#sortBy').change( function() {
            var groups = get_groups();
            var colors = get_colors();

            sorting='&sorting='+ $(this).val()
            cgory='?category='+groups
            colr='&color='+colors
            price='&price='+price
            sorting='&sorting='+sorting
            window.location = current_url+cgory+colr+price+sorting;
        });

        function get_colors(){
            colors = [];
            $.each($("input[name='color_input']:checked"), function(el){
                colors.push($(this).val());
            }); return colors;
        }

        function get_groups(){
            groups = [];
            $.each($("input[name='group_id']:checked"), function(el){
                groups.push($(this).val());
            }); return groups;
        }

    </script>

    <script>
        $(function() {
            $(".maxNumber").on('keyup', function(){
                var min = $('.minNumber').val();
                var max = $('.maxNumber').val();

                var groups = get_groups();
                var colors = get_colors();
                // debugger
                cgory='?category='+groups;
                colr='&color='+colors;
                price = '&price='+ min + "-" + max

                // window.location =  current_url+cgory+colr+price;
                $('.priceHref').attr('href', current_url+cgory+colr+price);
            });

            $(".minNumber").on('keyup', function(){
                var min = $(this).val();
                var max = $('.maxNumber').val();

                var groups = get_groups();
                var colors = get_colors();
                // debugger
                cgory='?category='+groups;
                colr='&color='+colors;
                price = '&price='+ min + "-" + max

                // window.location =  current_url+cgory+colr+price;
                $('.priceHref').attr('href', current_url+cgory+colr+price);

            });
           
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/products.blade.php ENDPATH**/ ?>