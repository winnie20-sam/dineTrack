<?php

use App\Http\Controllers\Staff\StaffDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\SaleController;

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // -------------------------------------------------------------------------
    // Admin Routes
    // -------------------------------------------------------------------------
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('businesses', BusinessController::class);
            Route::resource('staff',      StaffController::class);
            Route::resource('items',      ItemController::class);
            Route::resource('sales',      SaleController::class)->only(['index', 'create', 'store']);
        });
    });

    // -------------------------------------------------------------------------
    // Staff Routes
    // -------------------------------------------------------------------------
    Route::prefix('staff')->name('staff.')->middleware(['staff'])->group(function () {
        Route::get('/',      [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/sale/create', [StaffDashboardController::class, 'createSale'])->name('sale.create');
        Route::post('/sale', [StaffDashboardController::class, 'recordSale'])->name('sale.store');
        Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    });

});
