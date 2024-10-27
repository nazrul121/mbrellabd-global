<?php 

use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

        Route::post('register', 'App\Http\Controllers\api\v1\AuthController@register');
        Route::post('login', 'App\Http\Controllers\api\v1\AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    
    });
    
    
    Route::get('countries', [App\Http\Controllers\api\v1\FrontController::class, 'countries']);

    Route::get('top-categories/{countryId}', [App\Http\Controllers\api\v1\ProductController::class, 'top_categories']);
    Route::get('groups/{countryId}', [App\Http\Controllers\api\v1\ProductController::class, 'groups']);
    Route::get('inner-groups/{groupId}', [App\Http\Controllers\api\v1\ProductController::class, 'inner_groups']);
    Route::get('child-groups/{innerGroupId}', [App\Http\Controllers\api\v1\ProductController::class, 'child_groups']);

    Route::get('home-slider', [App\Http\Controllers\api\v1\FrontController::class, 'home_slider']);
    Route::get('seasons', [App\Http\Controllers\api\v1\FrontController::class, 'seasons']);
    Route::get('season-innerGroups/{seasoinId}/{groupId}', [App\Http\Controllers\api\v1\FrontController::class, 'seasonInnerGroups']);
    Route::get('season-childGroups/{seasoinId}/{innerGId}', [App\Http\Controllers\api\v1\FrontController::class, 'seasonChildGroups']);

    Route::get('promotions', [App\Http\Controllers\api\v1\FrontController::class, 'promotions']);
    Route::get('highlights/{countryId}/{number?}', [App\Http\Controllers\api\v1\FrontController::class, 'highlights']);
    

    Route::get('all-products/{countryId}', [App\Http\Controllers\api\v1\ProductController::class, 'all_products']);
    Route::get('group-products/{groupId}/{countryId}/{number?}', [App\Http\Controllers\api\v1\ProductController::class, 'group_products']);
    Route::get('inner-group-products/{groupId}/{countryId}/{number?}', [App\Http\Controllers\api\v1\ProductController::class, 'inner_group_products']);
    Route::get('child-group-products/{groupId}/{countryId}/{number?}', [App\Http\Controllers\api\v1\ProductController::class, 'child_group_products']);
    Route::get('single-product/{productId}/{countryId}/', [App\Http\Controllers\api\v1\ProductController::class, 'single_product']);

});

