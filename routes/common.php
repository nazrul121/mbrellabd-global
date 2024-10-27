<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'common', 'middleware' => ['web', 'common', 'auth'], 'namespace' => 'common'], function () {
    Route::get('dashboard', [App\Http\Controllers\common\DashboardController::class,'index'])->name('common.dashbaord');

    // category
    Route::get('catalog/category/main', [App\Http\Controllers\common\CategoryController::class, 'index'])->name('common.category');
    Route::get('catalog/category/main/single-item/{group}', [App\Http\Controllers\common\CategoryController::class, 'show'])->name('common.category.single-item');
    Route::get('catalog/category/main/delete/{group}', [App\Http\Controllers\common\CategoryController::class, 'destroy'])->name('common.category.delete');
    Route::post('catalog/category/main/create', [App\Http\Controllers\common\CategoryController::class, 'store'])->name('common.category.create');
    Route::post('catalog/category/main/update/{group}', [App\Http\Controllers\common\CategoryController::class, 'update'])->name('common.category.update');
    // category meta
    Route::get('catalog/category/meta/{group}', [App\Http\Controllers\common\GroupMetaController::class, 'index'])->name('common.group-meta');
    Route::get('catalog/category/meta/single-item/{group_meta}', [App\Http\Controllers\common\GroupMetaController::class, 'show'])->name('common.group-meta.single-item');
    Route::get('catalog/category/meta/delete/{group_meta}', [App\Http\Controllers\common\GroupMetaController::class, 'destroy'])->name('common.group-meta.delete');
    Route::post('catalog/category/meta/create', [App\Http\Controllers\common\GroupMetaController::class, 'store'])->name('common.group-meta.create');
    Route::post('catalog/category/meta/update/{group_meta}', [App\Http\Controllers\common\GroupMetaController::class, 'update'])->name('common.group-meta.update');

    // sub-category
    Route::get('catalog/category/sub/{sortBy?}', [App\Http\Controllers\common\SubCategoryController::class, 'index'])->name('common.sub-category');
    Route::get('catalog/category/sub/single-item/{inner_group}', [App\Http\Controllers\common\SubCategoryController::class, 'show'])->name('common.sub-category.single-item');
    Route::get('catalog/category/sub/delete/{inner_group}', [App\Http\Controllers\common\SubCategoryController::class, 'destroy'])->name('common.sub-category.delete');
    Route::post('catalog/category/sub/create', [App\Http\Controllers\common\SubCategoryController::class, 'store'])->name('common.sub-category.create');
    Route::post('catalog/category/sub/update/{inner_group}', [App\Http\Controllers\common\SubCategoryController::class, 'update'])->name('common.sub-category.update');
    // sub category meta
    Route::get('catalog/sub/meta/{inner_group}', [App\Http\Controllers\common\SubCategoryMetaController::class, 'index'])->name('common.inner-group-meta');
    Route::get('catalog/sub/meta/single-item/{inner_group_meta}', [App\Http\Controllers\common\SubCategoryMetaController::class, 'show'])->name('common.inner-group-meta.single-item');
    Route::get('catalog/sub/meta/delete/{inner_group_meta}', [App\Http\Controllers\common\SubCategoryMetaController::class, 'destroy'])->name('common.inner-group-meta.delete');
    Route::post('catalog/sub/meta/create', [App\Http\Controllers\common\SubCategoryMetaController::class, 'store'])->name('common.inner-group-meta.create');
    Route::post('catalog/sub/meta/update/{inner_group_meta}', [App\Http\Controllers\common\SubCategoryMetaController::class, 'update'])->name('common.inner-group-meta.update');


    // child-category
    Route::get('catalog/category/child', [App\Http\Controllers\common\ChildCategoryController::class, 'index'])->name('common.child-category');
    //get sub-categories form category_id
    Route::get('main2sub-categories/{season}/{group}', [App\Http\Controllers\common\ChildCategoryController::class, 'main2sub_categories'])->name('common.main2sub-categories');
    Route::get('sub2child-categories/{season}/{inner_group}', [App\Http\Controllers\common\ChildCategoryController::class, 'sub2child_categories'])->name('common.sub2child-categories');
    Route::get('catalog/category/child/single-item/{child_group}', [App\Http\Controllers\common\ChildCategoryController::class, 'show'])->name('common.child-category.single-item');
    
    Route::get('catalog/category/child/delete/{child_group}', [App\Http\Controllers\common\ChildCategoryController::class, 'destroy'])->name('common.child-category.delete');
    Route::post('catalog/category/child/create', [App\Http\Controllers\common\ChildCategoryController::class, 'store'])->name('common.child-category.create');
    Route::post('catalog/category/child/update/{child_group}', [App\Http\Controllers\common\ChildCategoryController::class, 'update'])->name('common.child-category.update');

    // cild category meta
    Route::get('catalog/child/meta/{child_group}', [App\Http\Controllers\common\ChildCategoryMetaController::class, 'index'])->name('common.child-group-meta');
    Route::get('catalog/child/meta/single-item/{child_group_meta}', [App\Http\Controllers\common\ChildCategoryMetaController::class, 'show'])->name('common.child-group-meta.single-item');
    Route::get('catalog/child/meta/delete/{child_group_meta}', [App\Http\Controllers\common\ChildCategoryMetaController::class, 'destroy'])->name('common.child-group-meta.delete');
    Route::post('catalog/child/meta/create', [App\Http\Controllers\common\ChildCategoryMetaController::class, 'store'])->name('common.child-group-meta.create');
    Route::post('catalog/child/meta/update/{child_group_meta}', [App\Http\Controllers\common\ChildCategoryMetaController::class, 'update'])->name('common.child-group-meta.update');


    // group/category ordering
    Route::get('catalog/category/ordering', [App\Http\Controllers\common\SortingController::class, 'ordering'])->name('common.category-ordering')->middleware('access:group-ordering');
    Route::get('catalog/category/group-ordering', [App\Http\Controllers\common\SortingController::class, 'group_ordering'])->name('common.group-ordering');
    Route::get('catalog/category/sub-group-ordering', [App\Http\Controllers\common\SortingController::class, 'inner_group_ordering'])->name('common.sub-group-ordering');
    Route::get('catalog/category/child-group-ordering', [App\Http\Controllers\common\SortingController::class, 'child_group_ordering'])->name('common.child-group-ordering');


    // sliders
    Route::get('page-post/slider', [App\Http\Controllers\common\SliderController::class, 'index'])->name('common.slider');
    Route::get('page-post/slider/single-item/{slider}', [App\Http\Controllers\common\SliderController::class, 'show'])->name('common.slider.single-item');
    Route::get('page-post/slider/delete/{slider}', [App\Http\Controllers\common\SliderController::class, 'destroy'])->name('common.slider.delete');
    Route::post('page-post/slider/create', [App\Http\Controllers\common\SliderController::class, 'store'])->name('common.slider.create');
    Route::post('page-post/slider/update/{slider}', [App\Http\Controllers\common\SliderController::class, 'update'])->name('common.slider.update');

    Route::get('page-post/slider/ordering', [App\Http\Controllers\common\SortingController::class, 'slider_ordering'])->name('common.slider-ordering');

    // page-post
    Route::get('page-post/page/{page_post_type}', [App\Http\Controllers\common\PagePostController::class, 'index'])->name('common.page-post');
    Route::get('page-post/page/create/{page_post_type}', [App\Http\Controllers\common\PagePostController::class, 'create'])->name('common.page-post.create');
    Route::post('page-post/page/save/{page_post_type}', [App\Http\Controllers\common\PagePostController::class, 'store'])->name('common.page-post.save');
    Route::get('page-post/page/single-item/{page_post}', [App\Http\Controllers\common\PagePostController::class, 'show'])->name('common.page-post.single-item');
    Route::get('page-post/page/delete/{page_post}', [App\Http\Controllers\common\PagePostController::class, 'destroy'])->name('common.page-post.delete');
    Route::get('page-post/page/edit/{page_post}', [App\Http\Controllers\common\PagePostController::class, 'edit'])->name('common.page-post.edit');
    Route::post('page-post/page/update/{page_post}', [App\Http\Controllers\common\PagePostController::class, 'update'])->name('common.page-post.update');
    Route::post('page-post/page/cover-photo/{page_post_type}', [App\Http\Controllers\common\PagePostController::class, 'coverPhoto'])->name('common.page-post.cover-photo');

    // banners
    Route::get('ad/banner', [App\Http\Controllers\common\BannerController::class, 'index'])->name('common.banner');
    Route::get('ad/banner/single-item/{banner}', [App\Http\Controllers\common\BannerController::class, 'show'])->name('common.banner.single-item');
    Route::get('ad/banner/delete/{banner}', [App\Http\Controllers\common\BannerController::class, 'destroy'])->name('common.banner.delete');
    Route::post('ad/banner/create', [App\Http\Controllers\common\BannerController::class, 'store'])->name('common.banner.create');
    Route::post('ad/banner/update/{banner}', [App\Http\Controllers\common\BannerController::class, 'update'])->name('common.banner.update');


    //variations
    Route::get('catalog/category/variant', [App\Http\Controllers\common\VariantController::class, 'index'])->name('common.variant');
    Route::get('catalog/category/variant/single-item/{variation}', [App\Http\Controllers\common\VariantController::class, 'show'])->name('common.variant.single-item');
    Route::get('catalog/category/variant/delete/{variation}', [App\Http\Controllers\common\VariantController::class, 'destroy'])->name('common.variant.delete');
    Route::post('catalog/category/variant/create', [App\Http\Controllers\common\VariantController::class, 'store'])->name('common.variant.create');
    Route::post('catalog/category/variant/update/{variation}', [App\Http\Controllers\common\VariantController::class, 'update'])->name('common.variant.update');

    // variation options
    Route::get('catalog/category/variant-option/{variation}', [App\Http\Controllers\common\VariationOptionController::class, 'index'])->name('common.variant-option');
    Route::get('catalog/category/variant-option/single-item/{variation_option}', [App\Http\Controllers\common\VariationOptionController::class, 'show'])->name('common.variant-option.single-item');
    Route::get('catalog/category/variant-option/delete/{variation_option}', [App\Http\Controllers\common\VariationOptionController::class, 'destroy'])->name('common.variant-option.delete');
    Route::post('catalog/category/variant-option/create', [App\Http\Controllers\common\VariationOptionController::class, 'store'])->name('common.variant-option.create');
    Route::post('catalog/category/variant-option/update/{variation_option}', [App\Http\Controllers\common\VariationOptionController::class, 'update'])->name('common.variant-option.update');


    // add/remove product variations
    Route::get('catalog/product/variant/product-variants/{product}', [App\Http\Controllers\common\ProductVariantController::class, 'create'])->name('common.product-variants');
    Route::post('catalog/product/variant/product-variants/create/{product}', [App\Http\Controllers\common\ProductVariantController::class, 'store'])->name('common.product-variants.create');


    Route::get('catalog/product/product-variant-delete/{product_combination}', [App\Http\Controllers\common\ProductVariantController::class, 'destroy'])->name('common.variant.delete.item');
    Route::get('catalog/product/edit-product-variation/{product_combination}', [App\Http\Controllers\common\ProductVariantController::class, 'edit'])->name('common.variant.edit.combination');
    Route::get('catalog/product/update-product-variation/{product_combination}', [App\Http\Controllers\common\ProductVariantController::class, 'update'])->name('common.variant.update.combination');
    Route::get('catalog/product/update-combination-qty/{product_combination}/{qty}', [App\Http\Controllers\common\ProductVariantController::class, 'update_qty'])->name('common.update-combination-qty');

    //dynmic variations
    Route::get('catalog/product/product-variation-option/{product}', [App\Http\Controllers\common\ProductVariantController::class, 'save2product_variation_option'])->name('common.save-product-variation-option');


    // product
    Route::get('catalog/product', [App\Http\Controllers\common\ProductController::class, 'index'])->name('common.product');
    Route::get('catalog/product/single-item/{product}', [App\Http\Controllers\common\ProductController::class, 'show'])->name('common.product.single-item');
    Route::get('catalog/product/delete/{product}', [App\Http\Controllers\common\ProductController::class, 'destroy'])->name('common.product.delete');
    Route::get('catalog/product/create', [App\Http\Controllers\common\ProductController::class, 'create'])->name('common.product.create');
    Route::post('catalog/product/store', [App\Http\Controllers\common\ProductController::class, 'store'])->name('common.product.store');
    Route::get('catalog/product/edit/{product}', [App\Http\Controllers\common\ProductController::class, 'edit'])->name('common.product.edit');
    Route::post('catalog/product/update/{product}', [App\Http\Controllers\common\ProductController::class, 'update'])->name('common.product.update');
    Route::get('catalog/product/product-photos/{id}', [App\Http\Controllers\common\ProductController::class, 'product_photos'])->name('common.product-photos');
    // remove product single photo
    Route::get('remove-product-photo/{product_photo}', '\App\Http\Controllers\common\ProductController@remove_product_photo')->name('remove-product-photo');

    Route::get('catalog/product/variant-products/{id}/{fk_id}', '\App\Http\Controllers\common\ProductController@variant_products')->name('common.variant-products');
    Route::get('catalog/product/category/{group}', '\App\Http\Controllers\common\ProductController@category_products')->name('common.category-products');

    Route::post('catalog/product/quick-update/{product}', [App\Http\Controllers\common\ProductController::class, 'quick_update'])->name('common.product.quick-update');


    Route::get('catalog/product/sub-category/{inner_group}', '\App\Http\Controllers\common\ProductController@sub_category_products')->name('common.sub-category-products');
    Route::get('catalog/product/child-category/{child_group}', '\App\Http\Controllers\common\ProductController@child_category_products')->name('common.child-category-products');
    Route::get('catalog/product/brand/{barnd}', '\App\Http\Controllers\common\ProductController@brand_products')->name('common.brand-products');
    Route::get('catalog/product/design-code/{code}', '\App\Http\Controllers\common\ProductController@design_code_product')->name('common.design-code-products');

    // product videos
    Route::get('catalog/product/videos/{product}', '\App\Http\Controllers\common\ProductVideoController@index')->name('common.product.videos');
    Route::post('catalog/product/videos/create/{product}', '\App\Http\Controllers\common\ProductVideoController@store')->name('common.product.videos.create');
    Route::post('catalog/product/videos/update/{product_video}', '\App\Http\Controllers\common\ProductVideoController@update')->name('common.product.videos.update');
    Route::get('catalog/product/videos/delete/{product_video}', '\App\Http\Controllers\common\ProductVideoController@destroy')->name('common.product.videos.delete');
    Route::get('catalog/product/videos/single-video/{product_video}', '\App\Http\Controllers\common\ProductVideoController@single_video')->name('common.product.videos.single-one');
    Route::get('catalog/product/videos/show-one/{product_video}', '\App\Http\Controllers\common\ProductVideoController@show')->name('common.product.videos.show');

    Route::get('catalog/product/change-status/{product}', '\App\Http\Controllers\common\ProductController@change_status')->name('common.change-status');
    Route::get('catalog/product/assign2group/{group}/{product_ids}', '\App\Http\Controllers\common\ProductController@assign2groups')->name('common.assign2group');

    // Highlight
    Route::get('catalog/product/highlight', [App\Http\Controllers\common\HighlightController::class, 'index'])->name('common.highlight');
    Route::get('catalog/product/highlight/single-item/{highlight}', [App\Http\Controllers\common\HighlightController::class, 'show'])->name('common.highlight.single-item');
    Route::get('catalog/product/highlight/delete/{highlight}', [App\Http\Controllers\common\HighlightController::class, 'destroy'])->name('common.highlight.delete');
    Route::post('catalog/product/highlight/create', [App\Http\Controllers\common\HighlightController::class, 'store'])->name('common.highlight.create');
    Route::post('catalog/product/highlight/update/{highlight}', [App\Http\Controllers\common\HighlightController::class, 'update'])->name('common.highlight.update');
    Route::get('catalog/product/highlight/{highlight}', '\App\Http\Controllers\common\ProductController@highlight_products')->name('common.highlight-products');

    // add/remove product into highlight_product table
    Route::get('catalog/product/highlight/add-products/{highlight}', [App\Http\Controllers\common\HighlightProductController::class, 'add_product'])->name('common.highlight.add-product');
    Route::get('catalog/product/highlight-serarch/{highlight}', [App\Http\Controllers\common\HighlightProductController::class, 'search_product'])->name('common.highlight.searach-products');
    Route::get('catalog/product/highlight/product/delete/{highlight_product}', [App\Http\Controllers\common\HighlightProductController::class, 'destroy'])->name('common.highlight.delete.item');

    // copy product
    Route::get('catalog/product/copy/{product}', [App\Http\Controllers\common\ProductController::class, 'copy'])->name('common.product.copy-item');

    // color_product table
    Route::get('catalog/product/variation-photo/{product}', [App\Http\Controllers\common\VariationPhotoColortController::class, 'index'])->name('common.product.variation.photo');
    Route::get('catalog/product/delete-product-variation-photo/{variation_option_photo}', [App\Http\Controllers\common\VariationPhotoColortController::class, 'destroy'])->name('common.delete.product-variation-photo');
    Route::post('catalog/product/upload-variation-photo/{product}', [App\Http\Controllers\common\VariationPhotoColortController::class, 'store'])->name('common.upload-variation-photo');
    Route::post('catalog/product/update-variation-photo/{product}', [App\Http\Controllers\common\VariationPhotoColortController::class, 'update'])->name('common.update-variation-photo');

    Route::get('group2sub-categories/{group}', [App\Http\Controllers\common\CategoryController::class, 'sub_categories'])->name('common.get-sub-categories');
    Route::get('innerGroup2child-categories/{inner_group}', [App\Http\Controllers\common\CategoryController::class, 'child_categories'])->name('common.get-child-categories');


    // settings
    Route::get('settings/system-settings',[App\Http\Controllers\common\GeneralController::class, 'index'])->name('common.system-settings')->middleware('access:system-settings');
    Route::post('settings/system-settings', [App\Http\Controllers\common\GeneralController::class, 'update'])->name('common.save-system-settings');
    Route::get('settings/social-settings', [App\Http\Controllers\common\SocialController::class, 'index'])->name('common.social-settings');
    Route::post('settings/social-settings/store', [App\Http\Controllers\common\SocialController::class, 'store'])->name('common.social-store');
    Route::get('settings/social-settings/single-item/{social_media}', [App\Http\Controllers\common\SocialController::class, 'show'])->name('common.single-media');
    Route::post('settings/social-settings/update/{social_media}', [App\Http\Controllers\common\SocialController::class, 'update'])->name('common.social-update');
    Route::get('settings/social-settings/delete/{social_media}', [App\Http\Controllers\common\SocialController::class, 'destroy'])->name('common.social.delete');
    Route::get('settings/brand-info', [App\Http\Controllers\common\BrandController::class, 'index'])->name('common.brand-info');
    Route::post('settings/brand-update/{brand}', [App\Http\Controllers\common\BrandController::class, 'update'])->name('common.brand-update');

    // currency
    Route::get('settings/currency', [App\Http\Controllers\common\CurrencyController::class, 'index'])->name('common.currency');
    Route::post('settings/currency/store', [App\Http\Controllers\common\CurrencyController::class, 'store'])->name('common.currency-store');
    Route::get('settings/currency/single-item/{country}', [App\Http\Controllers\common\CurrencyController::class, 'show'])->name('common.currency.single-item');
    Route::get('settings/currency/delete/{country}', [App\Http\Controllers\common\CurrencyController::class, 'destroy'])->name('common.currency.delete');
    Route::post('settings/currency/update/{country}', [App\Http\Controllers\common\CurrencyController::class, 'update'])->name('common.currency.update');

    // dollar convertion
    Route::get('settings/dollar', [App\Http\Controllers\common\DollarConvController::class, 'index'])->name('common.dollar');
    Route::post('settings/dollar/store', [App\Http\Controllers\common\DollarConvController::class, 'store'])->name('common.dollar-store');
   

    

    // blog category
    Route::get('page-post/blog/category', [App\Http\Controllers\common\BlogCategoryController::class, 'index'])->name('common.blog-category');
    Route::get('page-post/blog/category/single-item/{blog_category}', [App\Http\Controllers\common\BlogCategoryController::class, 'show'])->name('common.blog-category.single-item');
    Route::get('page-post/blog/category/delete/{blog_category}', [App\Http\Controllers\common\BlogCategoryController::class, 'destroy'])->name('common.blog-category.delete');
    Route::post('page-post/blog/category/create', [App\Http\Controllers\common\BlogCategoryController::class, 'store'])->name('common.blog-category.create');
    Route::post('page-post/blog/category/update/{blog_category}', [App\Http\Controllers\common\BlogCategoryController::class, 'update'])->name('common.blog-category.update');

    // blog
    Route::get('page-post/blog/', [App\Http\Controllers\common\BlogController::class, 'index'])->name('common.blogs');
    Route::get('page-post/blog/single-item/{blog}', [App\Http\Controllers\common\BlogController::class, 'show'])->name('common.blog.single-item');
    Route::get('page-post/blog/delete/{blog}', [App\Http\Controllers\common\BlogController::class, 'destroy'])->name('common.blog.delete');
    Route::get('page-post/blog/create', [App\Http\Controllers\common\BlogController::class, 'create'])->name('common.blog.create');
    Route::post('page-post/blog/save', [App\Http\Controllers\common\BlogController::class, 'store'])->name('common.blog.save');
    Route::get('page-post/blog/edit/{blog}', [App\Http\Controllers\common\BlogController::class, 'edit'])->name('common.blog.edit');
    Route::post('page-post/blog/update/{blog}', [App\Http\Controllers\common\BlogController::class, 'update'])->name('common.blog.update');

    // policies
    Route::get('page-post/policy/{slug}', [App\Http\Controllers\common\PolicyController::class, 'index'])->name('common.policy');
    Route::get('page-post/policy/create/{policy_tpye}', [App\Http\Controllers\common\PolicyController::class, 'create'])->name('common.policy.create');
    Route::post('page-post/policy/create/{policy_tpye}', [App\Http\Controllers\common\PolicyController::class, 'store'])->name('common.policy.store');
    Route::get('page-post/policy/single-item/{policy}', [App\Http\Controllers\common\PolicyController::class, 'show'])->name('common.policy.single-item');
    Route::get('page-post/policy/delete/{policy}', [App\Http\Controllers\common\PolicyController::class, 'destroy'])->name('common.policy.delete');
    Route::get('page-post/policy/edit/{policy}', [App\Http\Controllers\common\PolicyController::class, 'edit'])->name('common.policy.edit');
    Route::post('page-post/policy/update/{policy}', [App\Http\Controllers\common\PolicyController::class, 'update'])->name('common.policy.update');

    Route::post('page-post/policy/cover-photo/{policy_type}', [App\Http\Controllers\common\PolicyController::class, 'coverPhoto'])->name('common.policy.cover-photo');


    // faqs
    Route::get('page-post/faq', [App\Http\Controllers\common\FaqController::class, 'index'])->name('common.faq');
    Route::get('page-post/faq/single-item/{faq}', [App\Http\Controllers\common\FaqController::class, 'show'])->name('common.faq.single-item');
    Route::get('page-post/faq/delete/{faq}', [App\Http\Controllers\common\FaqController::class, 'destroy'])->name('common.faq.delete');
    Route::post('page-post/faq/create', [App\Http\Controllers\common\FaqController::class, 'store'])->name('common.faq.create');
    Route::post('page-post/faq/update/{faq}', [App\Http\Controllers\common\FaqController::class, 'update'])->name('common.faq.update');


    //Payment gateways
    Route::get('payment/payment-method', [App\Http\Controllers\common\PaymentGatewayController::class, 'index'])->name('common.payment-gateway');
    Route::get('payment/payment-method/single-item/{payment_gateway}', [App\Http\Controllers\common\PaymentGatewayController::class, 'show'])->name('common.payment-gateway.single-item');
    Route::post('payment/payment-method/update/{payment_gateway}', [App\Http\Controllers\common\PaymentGatewayController::class, 'update'])->name('common.payment-gateway.update');

    // payment types
    Route::get('payment/payment-type', [App\Http\Controllers\common\PaymentTypeController::class, 'index'])->name('common.payment-type');
    Route::post('payment/payment-type/create', [App\Http\Controllers\common\PaymentTypeController::class, 'create'])->name('common.create-payment-type');
    Route::get('payment/payment-type/single-item/{payment_type}', [App\Http\Controllers\common\PaymentTypeController::class, 'show'])->name('common.payment-type.single-item');
    Route::post('payment/payment-type/update/{payment_type}', [App\Http\Controllers\common\PaymentTypeController::class, 'update'])->name('common.payment-type.update');
    Route::get('payment/payment-type/delete/{payment_type}', [App\Http\Controllers\common\PaymentTypeController::class, 'destroy'])->name('common.payment-type.delete');


    // quick settings
    Route::get('settings/quick-setting', [App\Http\Controllers\common\QuickSettingController::class, 'index'])->name('common.quick-setting')->middleware('access:quick-settings');
    Route::get('settings/quick-setting/delivery', [App\Http\Controllers\common\QuickSettingController::class, 'update_delivery'])->name('common.quick-delivery');
    Route::get('settings/quick-setting/product', [App\Http\Controllers\common\QuickSettingController::class, 'update_product'])->name('common.quick-product');
    Route::get('settings/quick-size-guide', [App\Http\Controllers\common\QuickSettingController::class, 'update_size_guide'])->name('common.quick-size-guide');
    Route::get('settings/quick-setting/blog', [App\Http\Controllers\common\QuickSettingController::class, 'update_blog'])->name('common.quick-blog');
    Route::get('settings/quick-setting/catView', [App\Http\Controllers\common\QuickSettingController::class, 'catView'])->name('common.quick-catView');
    Route::get('settings/quick-setting/colorView', [App\Http\Controllers\common\QuickSettingController::class, 'colorView'])->name('common.quick-colorView');
    Route::get('settings/quick-setting/addToCart', [App\Http\Controllers\common\QuickSettingController::class, 'addToCart_status'])->name('common.change-addToCart-status');
    Route::get('settings/quick-setting/addToCart-logs', [App\Http\Controllers\common\QuickSettingController::class, 'addToCart_logs'])->name('common.addToCart-logs');

    // customers
    Route::get('user/customer', [App\Http\Controllers\common\CustomerController::class, 'index'])->name('common.customer');
    Route::get('user/customer/single-item/{customer}', [App\Http\Controllers\common\CustomerController::class, 'show'])->name('common.customer.single-item');
    Route::get('user/customer/login-info/{customer}', [App\Http\Controllers\common\CustomerController::class, 'login_info'])->name('common.customer.login-info');
    Route::post('user/customer/login-info/{user}', [App\Http\Controllers\common\CustomerController::class, 'update_login'])->name('common.update-customer-login');
    Route::get('user/customer/delete/{customer}', [App\Http\Controllers\common\CustomerController::class, 'destroy'])->name('common.customer.delete');
    Route::post('user/customer/create', [App\Http\Controllers\common\CustomerController::class, 'store'])->name('common.customer.create');
    Route::post('user/customer/update/{customer}', [App\Http\Controllers\common\CustomerController::class, 'update'])->name('common.customer.update');

    // employee categoreis
    Route::get('user/employee/category', [App\Http\Controllers\common\EmployeeCategoryController::class, 'index'])->name('common.employee-category');
    Route::get('user/employee/category/single-item/{staff_type}', [App\Http\Controllers\common\EmployeeCategoryController::class, 'show'])->name('common.employee-category.single-item');
    Route::get('user/employee/category/delete/{staff_type}', [App\Http\Controllers\common\EmployeeCategoryController::class, 'destroy'])->name('common.employee-category.delete');
    Route::post('user/employee/category/create', [App\Http\Controllers\common\EmployeeCategoryController::class, 'store'])->name('common.employee-category.create');
    Route::post('user/employee/category/update/{staff_type}', [App\Http\Controllers\common\EmployeeCategoryController::class, 'update'])->name('common.employee-category.update');

    // employees
    Route::get('user/employee', [App\Http\Controllers\common\EmployeeController::class, 'index'])->name('common.employee');
    Route::get('user/employee/single-item/{staff}', [App\Http\Controllers\common\EmployeeController::class, 'show'])->name('common.employee.single-item');
    Route::get('user/employee/login-info/{staff}', [App\Http\Controllers\common\EmployeeController::class, 'login_info'])->name('common.employee.login-info');
    Route::get('user/employee/delete/{staff}', [App\Http\Controllers\common\EmployeeController::class, 'destroy'])->name('common.employee.delete');
    Route::post('user/employee/create', [App\Http\Controllers\common\EmployeeController::class, 'store'])->name('common.employee.create');
    Route::post('user/employee/update/{staff}', [App\Http\Controllers\common\EmployeeController::class, 'update'])->name('common.employee.update');

    // supplier
    Route::get('user/supplier', [App\Http\Controllers\common\SupplierController::class, 'index'])->name('common.supplier');
    Route::get('user/supplier/single-item/{supplier}', [App\Http\Controllers\common\SupplierController::class, 'show'])->name('common.supplier.single-item');
    Route::get('user/supplier/delete/{supplier}', [App\Http\Controllers\common\SupplierController::class, 'destroy'])->name('common.supplier.delete');
    Route::post('user/supplier/create', [App\Http\Controllers\common\SupplierController::class, 'store'])->name('common.supplier.create');
    Route::post('user/supplier/update/{supplier}', [App\Http\Controllers\common\SupplierController::class, 'update'])->name('common.supplier.update');

    // admins
    Route::get('user/admin', [App\Http\Controllers\common\AdminController::class, 'index'])->name('common.admin');
    Route::get('user/admin/single-item/{admin}', [App\Http\Controllers\common\AdminController::class, 'show'])->name('common.admin.single-item');
    Route::get('user/admin/delete/{admin}', [App\Http\Controllers\common\AdminController::class, 'destroy'])->name('common.admin.delete');
    Route::post('user/admin/create', [App\Http\Controllers\common\AdminController::class, 'store'])->name('common.admin.create');
    Route::post('user/admin/update/{admin}', [App\Http\Controllers\common\AdminController::class, 'update'])->name('common.admin.update');
    Route::get('user/admin/login-info/{admin}', [App\Http\Controllers\common\AdminController::class, 'login_info'])->name('common.admin.login-info');
    Route::get('user/admin/update-login-access/{user}/{type}', [App\Http\Controllers\common\AdminController::class, 'login_access'])->name('common.admin.login-access');


    // user-types
    Route::get('user/user-types/', [App\Http\Controllers\common\AdminController::class, 'user_types'])->name('common.user-types');
    Route::post('save-user_type-permission', [App\Http\Controllers\common\AdminController::class, 'save_user_type_permission'])->name('common.user-type-permission');
    Route::get('user/user-type-permissions/{user_type}', [App\Http\Controllers\common\AdminController::class, 'user_type_permission'])->name('common.user-type-permissions');
    Route::get('user/user-permissions/{staff}', [App\Http\Controllers\common\EmployeeController::class, 'user_permission'])->name('common.user-permissions');
    Route::post('save-staff-permission', '\App\Http\Controllers\common\EmployeeController@save_staff_permission')->name('common.save-staff-permission');

    // coupons
    Route::get('ad/coupon', [App\Http\Controllers\common\CouponController::class, 'index'])->name('common.coupon');
    Route::get('ad/coupon/single-item/{coupon}', [App\Http\Controllers\common\CouponController::class, 'show'])->name('common.coupon.single-item');
    Route::get('ad/coupon/delete/{coupon}', [App\Http\Controllers\common\CouponController::class, 'destroy'])->name('common.coupon.delete');
    Route::post('ad/coupon/create', [App\Http\Controllers\common\CouponController::class, 'store'])->name('common.coupon.create');
    Route::post('ad/coupon/update/{coupon}', [App\Http\Controllers\common\CouponController::class, 'update'])->name('common.coupon.update');
    Route::get('ad/coupon/customer/{customer}', [App\Http\Controllers\common\CouponCustomerController::class, 'index'])->name('common.coupon.customers');


    // bundle promtion creating
    Route::get('ad/promotion/temp-bundles/{promotion}', [App\Http\Controllers\common\BundlePromotionController::class, 'temp_bundles'])->name('common.temp-bundles');
    Route::get('ad/promotion/remove-temp-bundles/{bundle_promotion_product}', [App\Http\Controllers\common\BundlePromotionController::class, 'remove_temp'])->name('common.temp-bundle.delete');
    Route::post('ad/promotion/save-bundles', [App\Http\Controllers\common\BundlePromotionController::class, 'create_bundle'])->name('common.save-bundle');

    // promotion
    Route::post('ad/promotion/create', [App\Http\Controllers\common\PromotionController::class, 'store'])->name('common.promotion.create');
    Route::get('ad/promotion/single-item/{promotion}', [App\Http\Controllers\common\PromotionController::class, 'show'])->name('common.promotion.single-item');
    Route::post('ad/promotion/update/{promotion}', [App\Http\Controllers\common\PromotionController::class, 'update'])->name('common.promotion.update');

    Route::get('ad/promotion/form/{promotion}', [App\Http\Controllers\common\ProductPromotionController::class, 'promotion_form'])->name('common.promotion.form');
    Route::get('ad/promotion/products/{promotion}', [App\Http\Controllers\common\ProductPromotionController::class, 'promotion_proudcts'])->name('common.promotion.products');
    Route::get('ad/promotion/remove-product/{product_promotion}', [App\Http\Controllers\common\ProductPromotionController::class, 'remove_product_promotion'])->name('common.promotion.product.delete');
    Route::get('ad/promotion/remove-products/{ids}', [App\Http\Controllers\common\ProductPromotionController::class, 'remove_products_promotion'])->name('common.promotion.products.delete');

    // Route::get('ad/promotion/type-wie-promotion/{promotion}', [App\Http\Controllers\common\ProductPromotionController::class, 'type_wise_promotion'])->name('common.promotion.info');
    Route::get('ad/promotion/show-form/{promotion}', [App\Http\Controllers\common\ProductPromotionController::class, 'show_promotion_form'])->name('common.promotion.show-form');
    Route::post('ad/promotion/save-form/{promotion}', [App\Http\Controllers\common\ProductPromotionController::class, 'save_promotion'])->name('common.promotion.save-form');
    Route::get('ad/promotion/{promotion_type}', [App\Http\Controllers\common\PromotionController::class, 'index'])->name('common.promotion');
    Route::get('ad/promotion/delete/{promotion}', [App\Http\Controllers\common\PromotionController::class, 'destroy'])->name('common.promotion.delete');


    Route::get('main2sub-categories-promotion/{promotion}/{group}', [App\Http\Controllers\common\PromotionController::class, 'main2sub_categories'])->name('common.main2sub-categories-promotion');
    Route::get('sub2child-categories-promotion/{promotion}/{inner_group}', [App\Http\Controllers\common\PromotionController::class, 'sub2child_categories'])->name('common.sub2child-categories-promotion');

    //category wise product shoing on flat promotion
    Route::get('get-group-products/{group}/{promotion}/{checkUncheck?}/{is_variation?}', [App\Http\Controllers\common\ProductPromotionController::class, 'get_group_products'])->name('common.get-group-products');
    Route::get('get-inner-group-products/{inner_group_id}/{promotion}/{checkUncheck?}/{is_variation?}', [App\Http\Controllers\common\ProductPromotionController::class, 'get_inner_group_products'])->name('common.get-inner-group-products');
    Route::get('get-child-group-products/{child_group_id}/{promotion}/{checkUncheck?}/{is_variation?}', [App\Http\Controllers\common\ProductPromotionController::class, 'get_child_group_products'])->name('common.get-child-group-products');


    // area
    Route::post('area/save-division/{country}', '\App\Http\Controllers\common\AreaController@save_division')->name('save-division');

    Route::get('area/', '\App\Http\Controllers\common\AreaController@index')->name('common.area');
    Route::post('area/save-district/{country}', '\App\Http\Controllers\common\AreaController@save_district')->name('save-district');
    Route::post('area/update-district', '\App\Http\Controllers\common\AreaController@update_district')->name('update-district');
    Route::get('area/single-district/{district}', '\App\Http\Controllers\common\AreaController@single_district')->name('single-district');
    Route::get('area/delete-district/{district}', '\App\Http\Controllers\common\AreaController@delete_district')->name('delete-district');
    Route::get('area/delete-division/{division}', '\App\Http\Controllers\common\AreaController@delete_division')->name('delete-division');

    Route::post('area/save-city', '\App\Http\Controllers\common\AreaController@save_city')->name('save-city');
    Route::get('area/update-city', '\App\Http\Controllers\common\AreaController@update_city')->name('update-city');
    Route::get('area/delete-city/{city}', '\App\Http\Controllers\common\AreaController@delete_city')->name('delete-city');
    Route::get('area/districts/{division}', '\App\Http\Controllers\common\AreaController@districts')->name('division-to-districts');
    Route::get('area/district/city/{division}', '\App\Http\Controllers\common\AreaController@cities')->name('division-to-cities');

    // zone
    Route::get('area/zone', [\App\Http\Controllers\common\ZoneController::class, 'index'])->name('common.area.zone');
    Route::get('area/zone/single-item/{zone}', '\App\Http\Controllers\common\ZoneController@show')->name('common.area.zone.single-item');
    Route::get('area/zone/delete/{zone}', '\App\Http\Controllers\common\ZoneController@destroy')->name('common.area.zone.delete');
    Route::post('area/zone/create', '\App\Http\Controllers\common\ZoneController@store')->name('common.area.zone.create');
    Route::post('area/zone/update/{zone}', '\App\Http\Controllers\common\ZoneController@update')->name('common.area.zone.update');
    Route::post('area/zone/add-city', '\App\Http\Controllers\common\ZoneController@add_city')->name('common.area.zone.addCity');
    Route::get('area/zone/zone-cities/{zone}', '\App\Http\Controllers\common\ZoneController@zone_city')->name('common.area.zone.cites');
    Route::get('area/zone/zone-city/delete/{id}', '\App\Http\Controllers\common\ZoneController@delete_city_zone')->name('common.area.zone.city.delete');
    Route::get('/cheack-area-cities/{zone_id}/{city_id}', [\App\Http\Controllers\common\ZoneController::class, 'cities'])->name('common.area-cities');


    //zone for courier companies
    Route::get('courier/zone', [\App\Http\Controllers\common\CourierZoneController::class, 'index'])->name('common.courier.zone');
    Route::get('courier/zone/single-item/{courier_zone}', '\App\Http\Controllers\common\CourierZoneController@show')->name('common.courier.zone.single-item');
    Route::get('courier/zone/delete/{courier_zone}', '\App\Http\Controllers\common\CourierZoneController@destroy')->name('common.courier.zone.delete');
    Route::post('courier/zone/create', '\App\Http\Controllers\common\CourierZoneController@store')->name('common.courier.zone.create');
    Route::post('courier/zone/update/{courier_zone}', '\App\Http\Controllers\common\CourierZoneController@update')->name('common.courier.zone.update');

    // orders
    Route::get('order/all-orders', [\App\Http\Controllers\common\OrderController::class, 'all_orders'])->name('common.all-orders');
    Route::get('/report/single-product-report', [\App\Http\Controllers\common\OrderController::class,'single_product_orders'])->name('common.single-product-report');

    Route::get('report/order-export', [App\Http\Controllers\common\ExportOrderController::class,'order_report'])->name('common.order-export');
    Route::get('report/order-excel', [App\Http\Controllers\common\ExportOrderController::class, 'order_excel'])->name('common.order-excel');
    Route::get('report/order-pdf', [App\Http\Controllers\common\ExportOrderController::class, 'order_pdf'])->name('common.order-pdf');
    Route::get('report/order-durations', [App\Http\Controllers\common\ExportOrderController::class, 'order_durations'])->name('common.order-durations');
    Route::get('report/duration-orders/{hour}', [App\Http\Controllers\common\ExportOrderController::class, 'duration_orders'])->name('common.duration-orders');
    Route::get('order/order-dhl/{order}', [\App\Http\Controllers\common\OrderController::class, 'order_dhl'])->name('common.order-dhl');
    Route::get('order/reorder-dhl/{order}', [\App\Http\Controllers\common\OrderController::class, 'reorder_dhl'])->name('common.reorder-dhl');
    
    Route::get('order/create-dhl-pickup/{order}', [\App\Http\Controllers\common\OrderController::class, 'create_dhl_pickup'])->name('common.create-dhl-pickup');

    // last week orders 
    Route::get('report/last-week-orders', [App\Http\Controllers\common\ExportOrderController::class, 'last_week_orders'])->name('common.last-week-orders');
    Route::get('report/date-orders/{date}', [App\Http\Controllers\common\ExportOrderController::class, 'date_orders'])->name('common.date-orders');
    Route::get('report/date-status-orders/{date}/{status}', [App\Http\Controllers\common\ExportOrderController::class, 'date_status_orders'])->name('common.date-status-orders');

    Route::get('order/date-to-date-orders/{date1}/{date2}', [App\Http\Controllers\common\OrderController::class, 'date_range'])->name('common.date-to-date-orders');

    Route::get('report/invoice-base-orders', [App\Http\Controllers\common\OrderController::class, 'invoice_base_orders'])->name('common.invoice-base-reports');
    Route::get('report/invoice-base-order-excel', [App\Http\Controllers\common\OrderController::class, 'order_excel'])->name('common.invoice-base-order-excel');
    Route::get('report/invoice-base-order-pdf', [App\Http\Controllers\common\OrderController::class, 'order_pdf'])->name('common.invoice-base-order-pdf');
    Route::get('report/company-report/{courier_company?}', [\App\Http\Controllers\common\CourierController::class,'company_report'])->name('common.courier.company.report');
    Route::get('/bunle-orders/{courier_order_bundle}', [\App\Http\Controllers\common\CourierController::class,'courier_order_bundle'])->name('common.bunle-orders');

    Route::get('report/company-monthly-report/{courier_company?}', [\App\Http\Controllers\common\CourierController::class,'monthly_orders'])->name('common.courier.monthly-report');


    // area wise orders
    Route::get('report/area-wise-order', '\App\Http\Controllers\common\OrderAreaController@index')->name('common.area-wize-orders');
    Route::get('report/area-wize-customer-orders/{ids}', '\App\Http\Controllers\common\OrderAreaController@orders')->name('common.area-wize-customer-orders');
    Route::get('report/area-wize-customers/{ids}', '\App\Http\Controllers\common\OrderAreaController@customers')->name('common.area-wize-customers');


    // month base order report
    Route::get('report/monthly-orders/{courier_company}', [App\Http\Controllers\common\ExportOrderController::class, 'monthly_orders'])->name('common.monthly-orders');
    Route::get('report/sslcommerz', [App\Http\Controllers\common\OrderController::class, 'sslcommerz_orders'])->name('common.sslcommerz-orders');
    Route::get('report/sslcommerz-excel', [App\Http\Controllers\common\OrderController::class, 'sslcommerz_excel'])->name('common.sslcommerz-excel');

    Route::get('report/deliverd-order-info', [App\Http\Controllers\common\OrderController::class, 'deliverd_orders'])->name('common.deliverd-order-info');



    // create order
    Route::get('order/create', [App\Http\Controllers\common\OrderCreateController::class, 'index'])->name('common.order.create');
    Route::post('order/create/save-address', '\App\Http\Controllers\common\OrderCreateController@save_address')->name('common.order.save-address');
    Route::get('order/create/order-items', [App\Http\Controllers\common\OrderCreateController::class, 'order_items'])->name('common.order-items');

    //this route is not working now, instead: order/get-product-combinations 
    Route::get('order/get-product-variations/{product}', [App\Http\Controllers\common\OrderCreateController::class, 'variations'])->name('common.product-variations');
    Route::get('order/create/remove-item/{order_item}', '\App\Http\Controllers\common\OrderCreateController@remove_item')->name('common.remove-order-item');
    Route::get('order/create/make-order', [App\Http\Controllers\common\OrderCreateController::class, 'make_order'])->name('common.make-order');
    Route::get('order/refresh-create-order', [App\Http\Controllers\common\OrderCreateController::class, 'clear_order'])->name('common.refresh-create-order');


    Route::get('searach-products', [App\Http\Controllers\common\OrderCreateController::class, 'search_product'])->name('common.searach-products');

    Route::get('send-sms', [App\Http\Controllers\common\OrderCreateController::class, 'sendSMS'])->name('common.send-sms');
    



    // edit order
    Route::get('order/edit-order/{order}', [App\Http\Controllers\common\OrderEditController::class, 'edit_order'])->name('common.edit-order');
    Route::get('order/update-combination/{type}/{order_item}/{product_combination}', [App\Http\Controllers\common\OrderEditController::class, 'update_combination'])->name('common.update-order-combination');
    Route::get('order/update-order-qty/{type}/{order_item}/{qty}', [App\Http\Controllers\common\OrderEditController::class, 'update_order_qty'])->name('common.update-order-item-qty');
    Route::get('order/delete-order-item/{type}/{order_item}', [App\Http\Controllers\common\OrderEditController::class, 'delete_order_item'])->name('common.delete-order-item');

    Route::get('order/get-product-combinations/{product}', [App\Http\Controllers\common\OrderEditController::class, 'combinations'])->name('common.product-combinations');
    Route::post('order/add-item-into-order/{order}', [App\Http\Controllers\common\OrderEditController::class, 'add_product'])->name('common.add-item-into-order');
    Route::post('order/update-order-address/{order}', [App\Http\Controllers\common\OrderEditController::class, 'edit_address'])->name('common.update-order-address');


    Route::get('order/{order_status}', [\App\Http\Controllers\common\OrderController::class,'index'])->name('common.orders');


    Route::get('order/shipping-address/{order}', '\App\Http\Controllers\common\OrderController@shipping_address')->name('common.shipping-address');
    Route::get('order/print-invoice/{order_id}', '\App\Http\Controllers\common\OrderController@order_invoice')->name('common.order.invoice');
    Route::get('order/delivery-process/{order}', '\App\Http\Controllers\common\OrderController@delivery_process')->name('common.order.delivery-process');

    Route::get('order/ask-for-payment/{order}', [\App\Http\Controllers\common\OrderPaymentController::class, 'takeApayment'])->name('common.order.ask-for-payment');
    Route::post('order/create-payment/{order}', '\App\Http\Controllers\common\OrderPaymentController@create_payment')->name('common.order.create-payment');
    Route::get('order/check-order-payment/{order}', '\App\Http\Controllers\common\OrderPaymentController@check_order_payment')->name('common.check-order-payment');

    Route::get('order/prepare-to-ship/{order_ids}', '\App\Http\Controllers\common\OrderController@prepare2ship')->name('common.order.prepare-to-ship');
    Route::post('order/save-top-sheet/', [\App\Http\Controllers\common\OrderController::class, 'saveTopSheet'])->name('common.order.save-top-sheet');


    // showroom
    Route::get('showroom', [App\Http\Controllers\common\ShowroomController::class, 'index'])->name('common.showroom')->middleware(['access:view-outlet']);
    Route::get('showroom/single-item/{show_room}', [App\Http\Controllers\common\ShowroomController::class, 'show'])->name('common.showroom.single-item');
    Route::get('showroom/delete/{show_room}', [App\Http\Controllers\common\ShowroomController::class, 'destroy'])->name('common.showroom.delete');
    Route::post('showroom/create', [App\Http\Controllers\common\ShowroomController::class, 'store'])->name('common.showroom.create');
    Route::post('showroom/update/{show_room}', [App\Http\Controllers\common\ShowroomController::class, 'update'])->name('common.showroom.update');

    // campaign
    Route::get('ad/campaign', [App\Http\Controllers\common\CampaignController::class, 'index'])->name('common.campaign');
    Route::get('ad/campaign/single-item/{campaign}', [App\Http\Controllers\common\CampaignController::class, 'show'])->name('common.campaign.single-item');
    Route::get('ad/campaign/delete/{campaign}', [App\Http\Controllers\common\CampaignController::class, 'destroy'])->name('common.campaign.delete');
    Route::post('ad/campaign/create', [App\Http\Controllers\common\CampaignController::class, 'store'])->name('common.campaign.create');
    Route::post('ad/campaign/update/{campaign}', [App\Http\Controllers\common\CampaignController::class, 'update'])->name('common.campaign.update');

    // quick services
    Route::get('quick-service', [App\Http\Controllers\common\Q_serviceController::class, 'index'])->name('common.quick-service');
    Route::get('quick-service/single-item/{quick_service}', [App\Http\Controllers\common\Q_serviceController::class, 'show'])->name('common.quick-service.single-item');
    Route::get('quick-service/delete/{quick_service}', [App\Http\Controllers\common\Q_serviceController::class, 'destroy'])->name('common.quick-service.delete');
    Route::post('quick-service/create', [App\Http\Controllers\common\Q_serviceController::class, 'store'])->name('common.quick-service.create');
    Route::post('quick-service/update/{quick_service}', [App\Http\Controllers\common\Q_serviceController::class, 'update'])->name('common.quick-service.update');

    // testimonial
    Route::get('testimonial', [App\Http\Controllers\common\TestimonialController::class, 'index'])->name('common.testimonial');
    Route::get('testimonial/single-item/{testimonial}', [App\Http\Controllers\common\TestimonialController::class, 'show'])->name('common.testimonial.single-item');
    Route::get('testimonial/delete/{testimonial}', [App\Http\Controllers\common\TestimonialController::class, 'destroy'])->name('common.testimonial.delete');
    Route::post('testimonial/create', [App\Http\Controllers\common\TestimonialController::class, 'store'])->name('common.testimonial.create');
    Route::post('testimonial/update/{testimonial}', [App\Http\Controllers\common\TestimonialController::class, 'update'])->name('common.testimonial.update');

    // size chirt
    Route::get('catalog/category/size-chirt', [App\Http\Controllers\common\SizeChirtController::class, 'index'])->name('common.size-chirt');
    Route::get('catalog/category/size-chirt/delete/{size_chirt}', [App\Http\Controllers\common\SizeChirtController::class, 'destroy'])->name('common.size-chirt.delete');
    Route::post('catalog/category/size-chirt/create', [App\Http\Controllers\common\SizeChirtController::class, 'store'])->name('common.size-chirt.create');

    Route::get('catalog/category/size-chirt-for-all', function(){
        return view('common.size-chirt.common-size-chirt');
    })->name('common.size-chirt-for-all');

    Route::post('catalog/category/upload-chirt-for-all', [App\Http\Controllers\common\SizeChirtController::class, 'save_common_size_chirt'])->name('common.upload-size-chirt-for-all');

    // seasons
    Route::get('catalog/season', [App\Http\Controllers\common\SeasonController::class, 'index'])->name('common.season');
    Route::get('catalog/season/single-item/{season}', [App\Http\Controllers\common\SeasonController::class, 'show'])->name('common.season.single-item');
    Route::get('catalog/season/delete/{season}', [App\Http\Controllers\common\SeasonController::class, 'destroy'])->name('common.season.delete');
    Route::post('catalog/season/create', [App\Http\Controllers\common\SeasonController::class, 'store'])->name('common.season.create');
    Route::post('catalog/season/update/{season}', [App\Http\Controllers\common\SeasonController::class, 'update'])->name('common.season.update');
    Route::get('catalog/season/search/{season}', [App\Http\Controllers\common\SeasonController::class, 'select_product'])->name('common.season.search');
    Route::get('catalog/season/get-product/{season}/{type}/{id}', [App\Http\Controllers\common\SeasonController::class, 'get_products'])->name('common.season.show-products');

    Route::get('catalog/season/groups/{season}', [App\Http\Controllers\common\SeasonController::class, 'menu_setup'])->name('common.season.groups');

    Route::get('catalog/season/update-group-season/{season}/{group}', [App\Http\Controllers\common\SeasonController::class, 'update_group_season'])->name('common.season.update-group');
    Route::get('catalog/season/update-sub-group-season/{season}/{inner_group}', [App\Http\Controllers\common\SeasonController::class, 'update_inner_group_season'])->name('common.season.update-sub-group');
    Route::get('catalog/season/update-child-group-season/{season}/{child_group}', [App\Http\Controllers\common\SeasonController::class, 'update_child_group_season'])->name('common.season.update-child-group');

    // contact messages
    Route::get('single-contact/{phone}', [App\Http\Controllers\common\ContactController::class, 'single_one'])->name('common.single-contact');

    // courier company
    Route::get('courier/companies/', [App\Http\Controllers\common\CourierController::class, 'index'])->name('common.couriers');
    Route::post('courier/companies/create', [App\Http\Controllers\common\CourierController::class, 'store'])->name('common.couriers.create');
    Route::get('courier/companies/single-company/{courier_company}', [App\Http\Controllers\common\CourierController::class, 'show'])->name('common.single-company');
    Route::post('courier/companies/update/{courier_company}', [App\Http\Controllers\common\CourierController::class, 'update'])->name('common.couriers.update');
    Route::get('courier/companies/company-representatives/{courier_company}', [App\Http\Controllers\common\CourierController::class, 'representatives'])->name('common.company-representatives');
    Route::get('courier/companies/zones/{courier_company}', '\App\Http\Controllers\common\CourierZoneController@company_zones')->name('common.courier.company.zone');
    
   
    // courier representative
    Route::get('courier/company-man/', [App\Http\Controllers\common\CourierManController::class, 'index'])->name('common.couriers.man');
    Route::post('courier/company-man/create', [App\Http\Controllers\common\CourierManController::class, 'store'])->name('common.couriers.man.create');
    Route::get('courier/company-man/single-item/{courier_representative}', [App\Http\Controllers\common\CourierManController::class, 'show'])->name('common.man.single-item');
    Route::post('courier/company-man/update/{courier_representative}', [App\Http\Controllers\common\CourierManController::class, 'update'])->name('common.couriers.man.update');

    //make order ready for shipings
    Route::get('courier/ready-to-ship/', [App\Http\Controllers\common\CourierController::class, 'ready_to_ship'])->name('common.ready-to-ship');


    // order status setup
    Route::get('settings/order-status', [App\Http\Controllers\common\OrderStatusController::class, 'index'])->name('common.order-status');
    Route::get('settings/order-status/single-item/{order_status}', [App\Http\Controllers\common\OrderStatusController::class, 'show'])->name('common.order-status.single-item')->middleware('access:order-setup');
    Route::get('settings/order-status-sorting', [App\Http\Controllers\common\OrderStatusController::class, 'order_status_sorting'])->name('common.order-status-sorting');
    Route::post('settings/order-status/update/{order_status}', [App\Http\Controllers\common\OrderStatusController::class, 'update'])->name('common.order-status.update');
    Route::post('settings/order-status/create', [App\Http\Controllers\common\OrderStatusController::class, 'store'])->name('common.order-status.create');
    Route::get('settings/order-status/delete/{order_status}', [App\Http\Controllers\common\OrderStatusController::class, 'destroy'])->name('common.order-status.delete');
    

    // invoice discount
    Route::get('ad/invoice-discount', [App\Http\Controllers\common\InvoiceDiscountController::class, 'index'])->name('common.invoice-discount');
    Route::get('ad/invoice-discount/single-item/{invoice_discount}', [App\Http\Controllers\common\InvoiceDiscountController::class, 'show'])->name('common.discount-invoice.single-item');
    // Route::get('ad/coupon/delete/{coupon}', [App\Http\Controllers\common\CouponController::class, 'destroy'])->name('common.coupon.delete');
    Route::post('ad/invoice-discount/create', [App\Http\Controllers\common\InvoiceDiscountController::class, 'store'])->name('common.discount-invoice.create');
    Route::post('ad/invoice-discount/update/{invoice_discount}', [App\Http\Controllers\common\InvoiceDiscountController::class, 'update'])->name('common.discount-invoice.update');


    // proudct meta
    Route::get('catalog/product/meta/{product}', [App\Http\Controllers\common\ProductMetaController::class, 'index'])->name('common.product-meta');
    Route::get('catalog/product/meta/single-item/{product_meta}', [App\Http\Controllers\common\ProductMetaController::class, 'show'])->name('common.product-meta.single-item');
    Route::get('catalog/product/meta/delete/{product_meta}', [App\Http\Controllers\common\ProductMetaController::class, 'destroy'])->name('common.product-meta.delete');
    Route::post('catalog/product/meta/create', [App\Http\Controllers\common\ProductMetaController::class, 'store'])->name('common.product-meta.create');
    Route::post('catalog/product/meta/update/{product_meta}', [App\Http\Controllers\common\ProductMetaController::class, 'update'])->name('common.product-meta.update');

    // videos
    Route::get('page-post/videos/', '\App\Http\Controllers\common\VideoController@index')->name('common.videos');
    Route::post('page-post/videos/create', '\App\Http\Controllers\common\VideoController@store')->name('common.videos.create');
    Route::post('page-post/videos/update/{video}', '\App\Http\Controllers\common\VideoController@update')->name('common.videos.update');
    Route::get('page-post/videos/delete/{video}', '\App\Http\Controllers\common\VideoController@destroy')->name('common.videos.delete');
    Route::get('page-post/videos/single-video/{video}', '\App\Http\Controllers\common\VideoController@single_video')->name('common.videos.single-one');
    Route::get('page-post/videos/show-one/{video}', '\App\Http\Controllers\common\VideoController@show')->name('common.videos.show');


    // set role
    Route::get('permissions', [App\Http\Controllers\common\PermissionController::class, 'index'])->name('common.permissions');
    Route::get('label-base-permission/{permission_label}', '\App\Http\Controllers\common\PermissionController@label_permissions')->name('common.label-base-permission');
    Route::post('set-access-type', '\App\Http\Controllers\common\PermissionController@setRole')->name('common.set-access-type');
    Route::get('save-permission-group', '\App\Http\Controllers\common\PermissionController@save_permission_group')->name('common.save-permission-group');


    Route::get('sitemap', [App\Http\Controllers\common\SiteMapController::class,'index'])->name('common.sitemap');

    // career
    Route::get('careers', [App\Http\Controllers\common\CareerController::class, 'index'])->name('common.career');
    Route::get('career/single-item/{career}', [App\Http\Controllers\common\CareerController::class, 'show'])->name('common.career.single-item');
    Route::get('career/delete/{career}', [App\Http\Controllers\common\CareerController::class, 'destroy'])->name('common.career.delete');
    Route::post('career/create', [App\Http\Controllers\common\CareerController::class, 'store'])->name('common.career.create');
    Route::post('career/update/{career}', [App\Http\Controllers\common\CareerController::class, 'update'])->name('common.career.update');
    Route::get('career/applicants/{career}', [App\Http\Controllers\common\CareerController::class, 'applicants'])->name('common.career.applicants');

    // add to cart 
    Route::get('report/addToCart', '\App\Http\Controllers\common\AddToCartController@index')->name('common.reprt.add-to-cart');
    Route::get('report/addToCart/{session_id}', '\App\Http\Controllers\common\AddToCartController@cart_items')->name('common.reprt.cart-items');


    Route::get('report/customer-report', [App\Http\Controllers\common\CustomerController::class, 'customer_order'])->name('common.customer-order');
    Route::get('get-backup', [App\Http\Controllers\common\DatabaseExportController::class, 'index'])->name('common.get-backup');
    Route::post('send-backup', [App\Http\Controllers\common\DatabaseExportController::class, 'exportDatabase'])->name('common.send-backup');


    // meta 
    Route::get('meta', [App\Http\Controllers\common\MetaController::class, 'index'])->name('common.meta');
    Route::post('save-meta', [App\Http\Controllers\common\MetaController::class, 'store'])->name('common.save-meta');
    Route::get('single-meta/{meta}', [App\Http\Controllers\common\MetaController::class, 'show'])->name('common.single-meta');
    Route::post('update-meta/{meta}', [App\Http\Controllers\common\MetaController::class, 'update'])->name('common.update-meta');
    Route::get('delete-meta/{meta}', [App\Http\Controllers\common\MetaController::class, 'destroy'])->name('common.delete-meta');



    Route::get('courier/dhl-setup', [App\Http\Controllers\common\DHLController::class, 'index'])->name('common.dhl-setup');
    Route::post('courier/update-dhl-setup', [App\Http\Controllers\common\DHLController::class, 'update'])->name('common.update-dhl-setup');
    // Route::post('courier/update-dhl-setup-1/{dhlBoxes}', [App\Http\Controllers\common\DHLController::class, 'step_one'])->name('common.update-dhl-setup-1');
    Route::get('courier/update-dhl-single', [App\Http\Controllers\common\DHLController::class, 'single_row_update'])->name('common.single-row-update');

    Route::get('courier/dhl-zone-price-setup', [App\Http\Controllers\common\DHLController::class, 'zone_setup'])->name('common.dhl-zone-price-setup');
    Route::post('courier/save-dhl-zone', [App\Http\Controllers\common\DHLController::class, 'save_zone'])->name('common.save-dhl-zone');
    Route::get('courier/single-dhl-zone/{kg}', [App\Http\Controllers\common\DHLController::class, 'single_zone'])->name('common.single-dhl-zone');
    Route::get('courier/delete-dhl-zone/{kg}', [App\Http\Controllers\common\DHLController::class, 'destroy'])->name('common.delete-dhl-zone');
    Route::post('courier/update-dhl-zone/{kg}', [App\Http\Controllers\common\DHLController::class, 'update_zone'])->name('common.update-dhl-zone');


});

Route::get('no-access', function(){ return view('common.no-access'); } )->name('common.no-access');

