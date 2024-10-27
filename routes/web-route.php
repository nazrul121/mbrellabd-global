<?php

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');// if function name is not written, __invoke(){} will be as deafault
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');



Route::get('update-thumbs', [App\Http\Controllers\HomeController::class, 'update_thumbs'])->name('update-thumbs');
Route::get('update-group-in-product/{type}', [App\Http\Controllers\HomeController::class, 'update_groupIdIn'])->name('update-group-in-product');
Route::get('/instagram-feed', [App\Http\Controllers\HomeController::class, 'instagram_feed'])->name('instagram-feed');




Route::get('/category/{slug}', [App\Http\Controllers\HomeController::class, 'category_products'])->name('category-products');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'all_products'])->name('products');
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'single_product'])->name('product');
Route::get('/change-variant-photo/{variation_option}/{thumbs_photo}/{product}', [App\Http\Controllers\ProductController::class, 'change_variant_photo'])->name('change-variant-photo');
Route::get('/get-variation-ption-to-variation/{variation_option}', [App\Http\Controllers\ProductController::class, 'get_option2Variation'])->name('get-variation-ption-to-variation');


Route::get('/highlight-products/{highlight}', [App\Http\Controllers\ProductController::class, 'highlight_products'])->name('highlight-products');

Route::get('/modal-product/{product}', [App\Http\Controllers\ProductController::class, 'modal_product'])->name('modal-product');

Route::get('/group/{slug}', [App\Http\Controllers\ProductController::class, 'group_products'])->name('group');
Route::get('/group-in/{slug}', [App\Http\Controllers\ProductController::class, 'inner_group_products'])->name('group-in');
Route::get('/child-in/{slug}', [App\Http\Controllers\ProductController::class, 'child_group_products'])->name('child-in');
Route::get('/about/policy/{slug}', [App\Http\Controllers\HomeController::class, 'policy'])->name('policy');
Route::get('/page/{slug}', [App\Http\Controllers\HomeController::class, 'page_info'])->name('page');
Route::get('/faqs', [App\Http\Controllers\HomeController::class, 'faqs'])->name('faqs');
Route::post('/save-contact', [App\Http\Controllers\HomeController::class, 'save_contact'])->name('save-contact');

Route::get('/add-to-cart', [App\Http\Controllers\CartController::class, 'addTocart'])->name('add-to-cart');
Route::get('/remove-cart/{key}/{id}', [App\Http\Controllers\CartController::class, 'remove_cart'])->name('remove-cart');
Route::get('/my-cart-ajax', [App\Http\Controllers\CartController::class, 'my_cart_ajax'])->name('my-cart-ajax');
Route::get('/my-cart', [App\Http\Controllers\CartController::class, 'my_cart'])->name('my-cart');

Route::get('/add-to-wishlist/{product}', [App\Http\Controllers\CartController::class, 'addWishlist'])->name('add-to-wishlist');
Route::get('/remove-wishlist/{wishlist}', [App\Http\Controllers\CartController::class, 'removeWishlist'])->name('remove-wishlist');
Route::get('/wishlist', [App\Http\Controllers\HomeController::class, 'wishlist'])->name('wishlist');

Route::get('/categories', [App\Http\Controllers\HomeController::class, 'categories'])->name('categories');

Route::get('/career', [App\Http\Controllers\CareerController::class, 'index'])->name('career');

Route::get('/career-job/{slug}', [App\Http\Controllers\CareerController::class, 'career_job'])->name('career-job');
Route::post('/save-career-applicant/{career}', [App\Http\Controllers\CareerController::class, 'save_applicant'])->name('save-career-applicant');

Route::get('/promotions', [App\Http\Controllers\PromoProductController::class, 'promotions'])->name('promotions');
Route::get('/promo-items/{slug}', [App\Http\Controllers\PromoProductController::class, 'index'])->name('promo-item');

// season
Route::get('/season-items/{slug}', [App\Http\Controllers\SeasonController::class, 'season_products'])->name('season-products');
Route::get('/season-group/{season_slug}/{slug}', [App\Http\Controllers\SeasonController::class, 'group_products'])->name('season-group');
Route::get('/season-group-in/{season_slug}/{slug}', [App\Http\Controllers\SeasonController::class, 'inner_group_products'])->name('season-group-in');
Route::get('/season-child-in/{season_slug}/{slug}', [App\Http\Controllers\SeasonController::class, 'child_group_products'])->name('season-child-in');

// blog
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/news/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('news');


// socialate
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class,'handleGoogleCallback']);

Route::get('/auth/facebook', [App\Http\Controllers\Auth\FacebookController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [App\Http\Controllers\Auth\FacebookController::class, 'handleFacebookCallback']);



Route::get('/autocomplete-search', [App\Http\Controllers\HomeController::class, 'autocompleteSearch']);
Route::post('/search', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/change-currency', [App\Http\Controllers\HomeController::class, 'change_currency'])->name('change-currency');


Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/change-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'change_password'])->name('change-password')->middleware('auth');
Route::post('/change-password/save', [App\Http\Controllers\Auth\ResetPasswordController::class, 'save_password'])->name('save-password')->middleware('auth');



Route::get('/get-sub-categories/{group}', [App\Http\Controllers\ProductController::class, 'sub_categories'])->name('sub-categories');
Route::get('/get-child-categories/{inner_group}', [App\Http\Controllers\ProductController::class, 'child_categories'])->name('child-categories');

Route::get('/get-districts/{division}', [App\Http\Controllers\HomeController::class, 'districts'])->name('districts');
Route::get('/get-cities/{district}', [App\Http\Controllers\HomeController::class, 'cities'])->name('cities');
Route::get('/district-delivery-info/{district}', [App\Http\Controllers\HomeController::class, 'district_delivery_cost'])->name('district-delivery-info');

Route::get('/get-zone-from-city/{city}/{subtotal}', [App\Http\Controllers\HomeController::class, 'zone_from_city'])->name('get-zone-from-city');

Route::get('/check-product-stock/{product}', [App\Http\Controllers\CartController::class, 'check_product_stock'])->name('check-product-stock');



Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/check-billing/{field}/{field_value}', [App\Http\Controllers\CheckoutController::class, 'check_billing_address'])->name('check-billing');
Route::get('/check-shipping/{field}/{field_value}', [App\Http\Controllers\CheckoutController::class, 'check_shipping_address'])->name('check-shipping');

Route::get('/save-checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('save-checkout');
Route::get('/order-info/{transaction_id}', [App\Http\Controllers\CheckoutController::class, 'order_info'])->name('order-info');
Route::get('/print-invoice/{transaction_id}', [App\Http\Controllers\CheckoutController::class, 'print_invoice'])->name('print-invoice');
Route::get('/truck', [App\Http\Controllers\CheckoutController::class, 'order_trucking'])->name('truck');
Route::get('/showrooms', [App\Http\Controllers\HomeController::class, 'showrooms'])->name('showrooms');
Route::get('/showroom-map/{show_room}', [App\Http\Controllers\HomeController::class, 'showroom_map'])->name('showroom-map');
Route::get('/subscribe', [App\Http\Controllers\HomeController::class, 'subscribe'])->name('subscribe');

Route::get('/get-product-meta/{product}', [App\Http\Controllers\HomeController::class, 'product_meta'])->name('get-product-meta');

Route::get('/size-guide', [App\Http\Controllers\HomeController::class, 'size_guird'])->name('size-guide');


// sslcommerz
Route::post('sslcommerz/success',[App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
Route::post('sslcommerz/failure',[App\Http\Controllers\PaymentController::class, 'failure'])->name('payment.failure');
Route::post('sslcommerz/cancel',[App\Http\Controllers\PaymentController::class, 'cancel'])->name('sslc.cancel');
Route::post('sslcommerz/ipn',[App\Http\Controllers\PaymentController::class, 'ipn'])->name('sslc.ipn');
Route::get('sslcommerz/take-order/{order}',[App\Http\Controllers\PaymentController::class, 'order'])->name('sslcommerce.place-order');

Route::get('sitemap', '\App\Http\Controllers\HomeController@sitemap')->name('sitemap');


Route::get('check-outlet-customer', [\App\Http\Controllers\HomeController::class, 'outlet_customers'])->name('check-outlet-customer');

// Password reset routes 
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}',[App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'update'])->name('password.update');






