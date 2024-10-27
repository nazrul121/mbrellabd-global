<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'superAdmin', 'middleware' => ['superAdmin', 'auth'], 'namespace' => 'superAdmin'], function () {
    Route::get('dashboard', [App\Http\Controllers\superAdmin\DashboardController::class, 'index'])->name('superAdmin.dashboard');
    Route::get('profile', [App\Http\Controllers\superAdmin\DashboardController::class, 'profile'])->name('superAdmin.profile');
    Route::post('profile/update', [App\Http\Controllers\superAdmin\DashboardController::class, 'update'])->name('superAdmin.update-profile');
});
