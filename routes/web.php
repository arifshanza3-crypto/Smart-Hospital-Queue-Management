<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QueueReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;

// Test route
Route::get('/test', function () {
    return "Test page working!";
});

// ✅ ADMIN REDIRECT
Route::get('/admin', function () {
    return redirect('/admin/doctor-management');
})->name('admin.dashboard');

// Main website routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/booking', [PageController::class, 'booking'])->name('booking');
Route::get('/Doctors', [PageController::class, 'Doctors'])->name('doctors');
Route::get('/Staff', [PageController::class, 'Staff'])->name('staff.page');

// Token Routes
Route::get('/Token_form', [TokenController::class, 'showForm'])->name('token.form');
Route::post('/token/generate', [TokenController::class, 'generateToken'])->name('token.generate');
Route::get('/Status', [PageController::class, 'Status'])->name('status.page');
Route::get('/patient/token-status', [TokenController::class, 'getTokenStatus'])->name('patient.token-status');

// =============================================
// ✅ AUTHENTICATION ROUTES
// =============================================
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', [PageController::class, 'sign'])->name('signup');
Route::get('/register', [PageController::class, 'sign'])->name('register');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================
// ✅ FORGOT PASSWORD ROUTES
// =============================================
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// =============================================
// ✅ STAFF ROUTES (UPDATED)
// =============================================
Route::prefix('staff')->name('staff.')->group(function () {
    // Staff Dashboard
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    
    // Staff Page (Public View)
    Route::get('/page', [PageController::class, 'Staff'])->name('page');
    
    // ✅ Patient Management (Updated)
    Route::post('/add-patient', [StaffController::class, 'addPatient'])->name('add-patient');
    Route::post('/start-serving', [StaffController::class, 'startServing'])->name('start-serving');
    Route::post('/complete-service', [StaffController::class, 'completeService'])->name('complete-service');
    Route::post('/cancel-token', [StaffController::class, 'cancelToken'])->name('cancel-token');
    Route::post('/call-next', [StaffController::class, 'callNext'])->name('call-next');
    Route::post('/cancel-patient', [StaffController::class, 'cancelPatient'])->name('cancel-patient');
    Route::post('/set-global-time', [StaffController::class, 'setGlobalTime'])->name('set-global-time');
    
    // ✅ Get Patients List (AJAX)
    Route::get('/get-queue', [StaffController::class, 'getQueue'])->name('get-queue');
});

// =============================================
// ✅ ADMIN ROUTES (With Staff Approval)
// =============================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // ✅ Admin Dashboard (Pending Staff Approvals)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctor-management', [AdminController::class, 'dashboard'])->name('doctor-management');
    
    // ✅ Staff Approval Routes
    Route::post('/approve-staff/{id}', [AdminController::class, 'approveStaff'])->name('approve-staff');
    Route::post('/reject-staff/{id}', [AdminController::class, 'rejectStaff'])->name('reject-staff');
    
    // Dashboard & Static Pages
    Route::get('/report', [QueueReportController::class, 'index'])->name('report');
    Route::get('/settings', [PageController::class, 'settings'])->name('settings');
    Route::get('/user-management', [PageController::class, 'user_management'])->name('user-management');
    Route::get('/services-management', [ServiceController::class, 'index'])->name('services-management');

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