<?php
use Illuminate\Support\Facades\Route;

Route::group([ 'prefix' => '{locale?}/customer','middleware'=>['customer','auth'],'namespace'=>'customer'],function() {
    Route::get('dashboard', [App\Http\Controllers\customer\DashboardController::class,'index'])->name('customer.dashbaord');
    Route::get('profile', [App\Http\Controllers\customer\DashboardController::class,'index'])->name('customer.profile');
    Route::get('account-info', [App\Http\Controllers\customer\DashboardController::class,'my_info'])->name('customer.account-info');
    Route::post('account-info/save', [App\Http\Controllers\customer\DashboardController::class,'update'])->name('customer.update.account');

    Route::post('save-customer', [App\Http\Controllers\customer\DashboardController::class,'save_customer'])->name('customer.save-info');

    Route::get('orders', [App\Http\Controllers\customer\OrderController::class,'index'])->name('customer.orders');
    Route::get('payments', [App\Http\Controllers\customer\OrderController::class,'payments'])->name('customer.payments');

    Route::get('addresses', [App\Http\Controllers\customer\AddressController::class,'index'])->name('customer.address');
    Route::post('addresses/save', [App\Http\Controllers\customer\AddressController::class,'store'])->name('customer.save-address');
    Route::post('addresses/update/{shipping_address}', [App\Http\Controllers\customer\AddressController::class,'update'])->name('customer.update-address');
    Route::get('addresses/delete/{shipping_address}', [App\Http\Controllers\customer\AddressController::class,'destroy'])->name('customer.destroy-address');
    Route::get('addresses/single-item/{shipping_address}', [App\Http\Controllers\customer\AddressController::class,'show'])->name('customer.single-address');

});

