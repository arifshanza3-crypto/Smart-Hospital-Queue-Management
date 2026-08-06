<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QueueReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\NotificationController;

// =============================================
// ✅ SETUP ROUTE
// =============================================
Route::get('/setup', function() {
    try {
        \Artisan::call('migrate', ['--force' => true]);
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        \Artisan::call('storage:link');
        return "✅ Setup complete!";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

// =============================================
// ✅ PUBLIC ROUTES (No Auth Required)
// =============================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/booking', [PageController::class, 'booking'])->name('booking');
Route::get('/Doctors', [PageController::class, 'Doctors'])->name('doctors');
Route::get('/Token_form', [TokenController::class, 'showForm'])->name('token.form');
Route::post('/token/generate', [TokenController::class, 'generateToken'])->name('token.generate');
Route::get('/Status', [PageController::class, 'Status'])->name('status.page');
Route::get('/patient/token-status', [TokenController::class, 'getTokenStatus'])->name('patient.token-status');

// =============================================
// ✅ AUTHENTICATION ROUTES
// =============================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
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
// ✅ STAFF ROUTES (Admin & Staff can access)
// =============================================
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff,admin'])->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::post('/add-patient', [StaffController::class, 'addPatient'])->name('add-patient');
    Route::post('/start-serving', [StaffController::class, 'startServing'])->name('start-serving');
    Route::post('/start-service', [StaffController::class, 'startService'])->name('start-service');
    Route::post('/complete-service', [StaffController::class, 'completeService'])->name('complete-service');
    Route::post('/cancel-token', [StaffController::class, 'cancelToken'])->name('cancel-token');
    Route::post('/call-next', [StaffController::class, 'callNext'])->name('call-next');
    Route::post('/cancel-patient', [StaffController::class, 'cancelPatient'])->name('cancel-patient');
    Route::post('/set-global-time', [StaffController::class, 'setGlobalTime'])->name('set-global-time');
    Route::get('/get-queue', [StaffController::class, 'getQueue'])->name('get-queue');
    Route::get('/get-department-queue', [StaffController::class, 'getDepartmentQueue'])->name('get-department-queue');
    Route::get('/get-department-stats', [StaffController::class, 'getDepartmentStats'])->name('get-department-stats');
    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StaffProfileController::class, 'index'])->name('index');
        Route::put('/update', [StaffProfileController::class, 'update'])->name('update');
        Route::put('/password', [StaffProfileController::class, 'updatePassword'])->name('password');
    });
});

// =============================================
// ✅ NOTIFICATION ROUTES (For All Authenticated Users)
// =============================================
Route::prefix('notifications')->name('notifications.')->middleware('auth')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
});

// ✅ Notification Page Route
Route::get('/notifications-page', [NotificationController::class, 'index'])->name('notifications.page')->middleware('auth');

// =============================================
// ✅ ADMIN ROUTES (Only Admin can access)
// =============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctor-management', [AdminController::class, 'dashboard'])->name('doctor-management');
    Route::post('/approve-staff/{id}', [AdminController::class, 'approveStaff'])->name('approve-staff');
    Route::post('/reject-staff/{id}', [AdminController::class, 'rejectStaff'])->name('reject-staff');
    Route::get('/report', [QueueReportController::class, 'index'])->name('report');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/user-management', [UserController::class, 'index'])->name('user-management');
    Route::get('/services-management', [ServiceController::class, 'index'])->name('services-management');

    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::get('/create', [DoctorController::class, 'create'])->name('create');
        Route::post('/', [DoctorController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [DoctorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DoctorController::class, 'update'])->name('update');
        Route::delete('/{id}', [DoctorController::class, 'destroy'])->name('destroy');
    });
    
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

    Route::prefix('queue-reports')->name('queue-reports.')->group(function () {
        Route::get('/', [QueueReportController::class, 'index'])->name('index');
        Route::get('/{id}', [QueueReportController::class, 'show'])->name('show');
        Route::get('/export/csv', [QueueReportController::class, 'export'])->name('export');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update', [SettingController::class, 'update'])->name('update');
    });

    // ✅ Admin Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });
});

// =============================================
// ✅ USER DASHBOARD (For All Authenticated Users)
// =============================================
Route::get('/dashboard', function() {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect('/admin/doctor-management');
    } elseif ($user->role === 'staff') {
        return redirect('/staff/dashboard');
    } else {
        return redirect('/');
    }
})->middleware('auth')->name('dashboard');

// =============================================
// ✅ PROFILE ROUTES (For All Users)
// =============================================
Route::middleware(['auth'])->group(function () {
    // Main Profile Routes (accessible by all authenticated users)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});