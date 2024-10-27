<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'admin','middleware'=>['admin','auth'],'namespace'=>'admin'],function() {
    Route::get('dashboard', [App\Http\Controllers\admin\DashboardController::class,'index'])->name('admin.dashbaord');
    Route::get('profile',[App\Http\Controllers\admin\DashboardController::class,'profile'])->name('admin.profile');
    Route::post('profile/update',[App\Http\Controllers\admin\DashboardController::class,'update'])->name('admin.update-profile');
});
