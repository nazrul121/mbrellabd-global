<?php $subtotal = array();?>

<?php if(Session::has('cart') && Session::get('cart')->where('country_id',session('user_currency')->id)->count()>0): ?>
    <div class="cart-content-area d-flex justify-content-between flex-column">
        <div class="minicart-loop custom-scrollbar">
            
            <?php $__currentLoopData = Session::get('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    if($cart->variation_option_id !=null){
                        $thumb = \DB::table('variation_option_photos')->where(['product_id'=>$cart->product_id,'variation_option_id'=>$cart->variation_option_id])->pluck('thumbs')->first();
                    }else $thumb = $cart->product->thumbs;

                ?>

                <div class="minicart-item d-flex">
                    <div class="mini-img-wrapper">
                        <?php if($thumb !=null): ?>
                            <img class="mini-img" src="<?php echo e($thumb); ?>" alt="img">
                        
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h2 class="product-title"><a href="<?php echo e(route('product',[app()->getLocale(), $cart->product->slug])); ?>" target="_blank"><?php echo e($cart->product->title); ?></a></h2>
                        
                        <?php if($cart->product_combination_id !=null): ?>
                        <p class="product-vendor">
                            <?php $__currentLoopData = $cart->product_combination()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pComb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = explode('~',$pComb->combination_string); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $string): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                        <span class="p-1 bg-2 text-white"><?php echo e($v->variation->title.': '.$v->title); ?> </span> &nbsp; 
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </p>
                        <?php endif; ?>

                        <div class="misc d-flex align-items-end justify-content-between">
                            <div>
                                <a href="javaScript:;" data-key="<?php echo e($key); ?>" data-id="<?php echo e($cart->id); ?>" class="product-remove text-danger">Remove</a>
                            </div>
                            <div class="product-remove-area d-flex flex-column align-items-end">
                                <div class="product-price"><?php echo e($cart->qty); ?> x <?php echo e(number_format(product_price($cart->product_id, $cart->product->sale_price),2)); ?> = 
                                    <?php echo e(Session::get('user_currency')->currencySymbol); ?> <?php echo e(number_format(product_price($cart->product_id, $cart->product->sale_price) * $cart->qty,2)); ?></div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <?php $subtotal[] = product_price($cart->product_id, $cart->product->sale_price) * $cart->qty; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
        </div>
        <div class="minicart-footer">
            <div class="minicart-calc-area">
                <div class="minicart-calc d-flex align-items-center justify-content-between">
                    <span class="cart-subtotal mb-0">Subtotal</span>
                    <span class="cart-subprice"><?php echo e(Session::get('user_currency')->currencySymbol . number_format(array_sum($subtotal),2)); ?></span>
                </div>
                <p class="cart-taxes text-center my-4">Taxes and shipping will be calculated at checkout. </p>
            </div>
            <div class="minicart-btn-area d-flex align-items-center justify-content-between">
                <a href="<?php echo e(route('my-cart',app()->getLocale())); ?>" class="minicart-btn btn-secondary">View Cart</a>
                <a href="<?php echo e(route('checkout',app()->getLocale())); ?>" class="minicart-btn btn-primary">Checkout</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="cart-empty-area text-center py-5">
        <div class="cart-empty-icon pb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                <line x1="15" y1="9" x2="15.01" y2="9"></line>
            </svg>
        </div>
        <p class="cart-empty">You have no items in your cart</p>
    </div>
<?php endif; ?>






<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/includes/cart-ajax.blade.php ENDPATH**/ ?>