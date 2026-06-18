<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrolmentController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// ─── Auth ───────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ─── Owner ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');

    Route::resource('motorcycles', MotorcycleController::class);

    Route::resource('contracts', ContractController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/contracts/{contract}/generate-key', [ContractController::class, 'generateKey'])
        ->name('contracts.generateKey');
    Route::post('/contracts/{contract}/payments', [PaymentController::class, 'store'])
        ->name('contracts.payments.store');

    Route::resource('contacts', ContactController::class)->only(['index', 'create', 'store', 'destroy']);
});

// ─── Driver ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:driver'])->group(function () {
    Route::get('/driver/dashboard', [DashboardController::class, 'driver'])->name('driver.dashboard');
    Route::get('/driver/enrol', [EnrolmentController::class, 'show'])->name('driver.enrol');
    Route::post('/driver/enrol', [EnrolmentController::class, 'store'])->name('driver.enrol.store');
    Route::get('/driver/loan', fn() => redirect()->route('driver.dashboard'))->name('driver.loan');
    Route::get('/driver/profile', [ProfileController::class, 'show'])->name('driver.profile');
    Route::put('/driver/profile', [ProfileController::class, 'update'])->name('driver.profile.update');
});

// ─── Admin ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::get('/contracts', [Admin\ContractController::class, 'index'])->name('contracts.index');
});
