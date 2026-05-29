<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login',    [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',   [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register',[RegisteredUserController::class, 'store']);
});
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menu
    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
    Route::middleware('role:manager')->group(function () {
        Route::get('menu/create',              [MenuController::class, 'create'])->name('menu.create');
        Route::post('menu',                    [MenuController::class, 'store'])->name('menu.store');
        Route::get('menu/{menuItem}/edit',     [MenuController::class, 'edit'])->name('menu.edit');
        Route::put('menu/{menuItem}',          [MenuController::class, 'update'])->name('menu.update');
        Route::delete('menu/{menuItem}',       [MenuController::class, 'destroy'])->name('menu.destroy');
        Route::patch('menu/{menuItem}/toggle', [MenuController::class, 'toggleAvailability'])->name('menu.toggle');
    });

    // Tables
    Route::get('tables', [TableController::class, 'index'])->name('tables.index');
    Route::patch('tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.status');
    Route::middleware('role:manager')->group(function () {
        Route::get('tables/create',       [TableController::class, 'create'])->name('tables.create');
        Route::post('tables',             [TableController::class, 'store'])->name('tables.store');
        Route::get('tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
        Route::put('tables/{table}',      [TableController::class, 'update'])->name('tables.update');
        Route::delete('tables/{table}',   [TableController::class, 'destroy'])->name('tables.destroy');
    });

    // Orders
    Route::get('orders',                  [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create',           [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders',                 [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}',          [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::middleware('role:manager')->group(function () {
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });

    // Kitchen
    Route::middleware('role:manager,chef,waiter')->group(function () {
        Route::get('kitchen',                      [KitchenController::class, 'index'])->name('kitchen.index');
        Route::patch('kitchen/{order}/status',     [KitchenController::class, 'updateStatus'])->name('kitchen.status');
    });

    // Billing
    Route::middleware('role:manager,cashier')->group(function () {
        Route::get('billing',               [BillingController::class, 'index'])->name('billing.index');
        Route::get('billing/order/{order}', [BillingController::class, 'create'])->name('billing.create');
        Route::post('billing',              [BillingController::class, 'store'])->name('billing.store');
        Route::get('billing/{bill}',        [BillingController::class, 'show'])->name('billing.show');
    });

    // Reports + Staff
    Route::middleware('role:manager')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('staff',                    [StaffController::class, 'index'])->name('staff.index');
        Route::patch('staff/{user}/toggle',    [StaffController::class, 'toggleActive'])->name('staff.toggle');
        Route::patch('staff/{user}/role',      [StaffController::class, 'updateRole'])->name('staff.role');
    });
});
