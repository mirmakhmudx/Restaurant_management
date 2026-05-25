<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

// ── Guest ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login',    [AuthenticatedSessionController::class, 'store']);
    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

// ── Authenticated ──────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Menu — Manager full CRUD, others read-only ─────
    Route::get('/menu',           [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/search',    [MenuController::class, 'search'])->name('menu.search');

    Route::middleware('role:manager')->group(function () {
        Route::get('/menu/create',            [MenuController::class, 'create'])->name('menu.create');
        Route::post('/menu',                  [MenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/{menuItem}/edit',   [MenuController::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{menuItem}',        [MenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{menuItem}',     [MenuController::class, 'destroy'])->name('menu.destroy');
        Route::patch('/menu/{menuItem}/toggle', [MenuController::class, 'toggleAvailability'])->name('menu.toggle');
    });

    // ── Orders ─────────────────────────────────────────
    Route::middleware('role:manager,waiter,cashier')->group(function () {
        Route::get('/orders', fn() => view('orders.index'))->name('orders.index');
    });

    // ── Kitchen ────────────────────────────────────────
    Route::middleware('role:manager,chef')->group(function () {
        Route::get('/kitchen', fn() => view('kitchen.index'))->name('kitchen.index');
    });

    // ── Billing ────────────────────────────────────────
    Route::middleware('role:manager,cashier')->group(function () {
        Route::get('/billing', fn() => view('billing.index'))->name('billing.index');
    });

    // ── Manager only ───────────────────────────────────
    Route::middleware('role:manager')->group(function () {
        Route::get('/tables',  fn() => view('tables.index'))->name('tables.index');
        Route::get('/reports', fn() => view('reports.index'))->name('reports.index');
        Route::get('/staff',   fn() => view('staff.index'))->name('staff.index');
    });
});
