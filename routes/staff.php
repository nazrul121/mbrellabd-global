<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'staff','middleware'=>['staff','auth'],'namespace'=>'staff'],function() {
    Route::get('dashboard', [App\Http\Controllers\staff\DashboardController::class,'index'])->name('staff.dashboard');
    Route::get('profile',[App\Http\Controllers\staff\DashboardController::class,'profile'])->name('staff.profile');
    Route::post('profile/update',[App\Http\Controllers\staff\DashboardController::class,'update'])->name('staff.update-profile');
});
