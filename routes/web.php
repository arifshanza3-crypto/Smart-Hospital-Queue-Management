<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QueueReportController;
use App\Http\Controllers\AuthController;

// Test route
Route::get('/test', function () {
    return "Test page working!";
});

// Main website routes
Route::get('/', [PageController::class, 'home']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/services', [PageController::class, 'services']);
Route::get('/contact', [PageController::class, 'contact']);
Route::get('/booking', [PageController::class, 'booking']);
Route::get('/Doctors', [PageController::class, 'Doctors']);
Route::get('/Status', [PageController::class, 'Status']);
Route::get('/Token_form', [PageController::class, 'Token_form']);
Route::get('/Staff', [PageController::class, 'Staff']);

// Authentication Routes
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', [PageController::class, 'sign'])->name('signup');
Route::get('/register', [PageController::class, 'sign'])->name('register');
Route::post('/signup', [AuthController::class, 'signup'])->name('register.post');  // ← YEH CHANGE KIYA
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Static Pages
    Route::get('/report', [QueueReportController::class, 'index'])->name('report');
    Route::get('/settings', [PageController::class, 'settings'])->name('settings');
    Route::get('/user-management', [PageController::class, 'user_management'])->name('user-management');
    Route::get('/services-management', [ServiceController::class, 'index'])->name('services-management');
    Route::get('/doctor-management', [DoctorController::class, 'index'])->name('doctor-management');

    // Doctor Management Routes
    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::get('/create', [DoctorController::class, 'create'])->name('create');
        Route::post('/', [DoctorController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [DoctorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DoctorController::class, 'update'])->name('update');
        Route::delete('/{id}', [DoctorController::class, 'destroy'])->name('destroy');
    });
    
    // Service Management Routes
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status/{status}', [ServiceController::class, 'updateStatus'])->name('update-status');
        Route::get('/active/list', [ServiceController::class, 'getActiveServices'])->name('active');
        Route::get('/search', [ServiceController::class, 'search'])->name('search');
        Route::post('/bulk-delete', [ServiceController::class, 'bulkDelete'])->name('bulk-delete');
    });
    
    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status/{status}', [UserController::class, 'updateStatus'])->name('update-status');
        Route::get('/search', [UserController::class, 'search'])->name('search');
    });

    // Queue Reports Routes
    Route::prefix('queue-reports')->name('queue-reports.')->group(function () {
        Route::get('/', [QueueReportController::class, 'index'])->name('index');
        Route::get('/{id}', [QueueReportController::class, 'show'])->name('show');
        Route::get('/export/csv', [QueueReportController::class, 'export'])->name('export');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
        Route::put('/update', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
    });
});