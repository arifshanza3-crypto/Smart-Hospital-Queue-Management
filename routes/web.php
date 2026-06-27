<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QueueReportController;
use App\Http\Controllers\AuthController;
<<<<<<< HEAD
use App\Http\Controllers\StaffController;

// Test route
Route::get('/test', function () {
    return "Test page working!";
});
=======
use App\Http\Controllers\TokenController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminController;

// Test route
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02

// Main website routes
Route::get('/', [PageController::class, 'home']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/services', [PageController::class, 'services']);
Route::get('/contact', [PageController::class, 'contact']);
Route::get('/booking', [PageController::class, 'booking']);
Route::get('/Doctors', [PageController::class, 'Doctors']);
<<<<<<< HEAD
Route::get('/Status', [PageController::class, 'Status']);
Route::get('/Token_form', [PageController::class, 'Token_form']);
Route::get('/Staff', [PageController::class, 'Staff']);

=======
Route::get('/Staff', [PageController::class, 'Staff']);

// Token Routes (No Auth Required)
Route::get('/Token_form', [PageController::class, 'Token_form']);
Route::post('/token/generate', [TokenController::class, 'generateToken'])->name('token.generate');
Route::get('/Status', [PageController::class, 'Status']);
Route::get('/patient/token-status', [PatientController::class, 'getTokenStatus'])->name('patient.token-status');

>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
// Authentication Routes
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', [PageController::class, 'sign'])->name('signup');
Route::get('/register', [PageController::class, 'sign'])->name('register');
<<<<<<< HEAD
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Staff Routes
Route::get('/Staff', [StaffController::class, 'dashboard'])->name('staff.dashboard');
Route::post('/staff/add-patient', [StaffController::class, 'addPatient'])->name('staff.add-patient');
Route::patch('/staff/patient/{id}/serve', [StaffController::class, 'serve'])->name('staff.serve');
Route::patch('/staff/patient/{id}/complete', [StaffController::class, 'complete'])->name('staff.complete');
Route::patch('/staff/patient/{id}/cancel', [StaffController::class, 'cancel'])->name('staff.cancel');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Static Pages
=======
Route::post('/signup', [AuthController::class, 'signup'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// FORGOT PASSWORD ROUTES
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
 
Route::get('/admin-test-page', function () {
    return "Admin test page working!";
});
// ✅ ADMIN ROUTES (FIXED)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Admin Dashboard
    Route::get('/doctor-management', [AdminController::class, 'dashboard'])->name('doctor-management');
    Route::post('/approve-staff/{id}', [AdminController::class, 'approveStaff'])->name('approve-staff');
    Route::post('/reject-staff/{id}', [AdminController::class, 'rejectStaff'])->name('reject-staff');
    
    // Report & Settings
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
    Route::get('/report', [QueueReportController::class, 'index'])->name('report');
    Route::get('/settings', [PageController::class, 'settings'])->name('settings');
    Route::get('/user-management', [PageController::class, 'user_management'])->name('user-management');
    Route::get('/services-management', [ServiceController::class, 'index'])->name('services-management');
<<<<<<< HEAD
    Route::get('/doctor-management', [DoctorController::class, 'index'])->name('doctor-management');

    // Doctor Management Routes
=======

    // Doctor Management Routes (CRUD)
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
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

<<<<<<< HEAD
    // Settings Routes (Add this inside admin group)
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
    Route::put('/update', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
    // Backup and restore routes REMOVED
});
=======
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
        Route::put('/update', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
    });
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
});