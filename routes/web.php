<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffOrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\ProfileController;

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

// Shared — accessible by everyone
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/password', [ProfileController::class, 'passwordPage'])->name('profile.password.page');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');




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
            Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
            Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy']);
            Route::get('reports',                [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/generate',       [ReportController::class, 'generate'])->name('reports.generate');
            Route::get('reports/export/pdf',     [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
            Route::get('reports/export/excel',   [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        });
    });

    /// -------------------------------------------------------------------------
        // Staff Routes
        // -------------------------------------------------------------------------
        Route::prefix('staff')->name('staff.')->middleware(['staff'])->group(function () {
            Route::get('/',                [StaffDashboardController::class, 'index'])->name('dashboard');
            Route::get('/sale/create',     [StaffDashboardController::class, 'createSale'])->name('sale.create');
            Route::post('/sale',           [StaffDashboardController::class, 'recordSale'])->name('sale.store');
            Route::get('/items',           [ItemController::class, 'index'])->name('items.index');
            Route::get('/orders',          [StaffOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/create',   [StaffOrderController::class, 'create'])->name('orders.create');
            Route::post('/orders',         [StaffOrderController::class, 'store'])->name('orders.store');
            Route::get('/orders/{order}',  [StaffOrderController::class, 'show'])->name('orders.show');
        });
        });
