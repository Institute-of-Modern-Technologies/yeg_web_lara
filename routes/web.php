<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

// Dashboard shortcut route - redirects to appropriate dashboard based on user type
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect('/login');
    }
    
    $userType = \App\Models\UserType::find($user->user_type_id);
    
    if ($userType) {
        switch ($userType->slug) {
            case 'super_admin':
                return redirect('/admin/dashboard');
            case 'school_admin':
                return redirect('/school/dashboard');
            case 'student':
                return redirect('/student/dashboard');
            default:
                return redirect('/');
        }
    }
    
    return redirect('/');
})->name('dashboard');


// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'user.type:super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // User Management Routes
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Hero Section Management Routes
    Route::resource('hero-sections', '\App\Http\Controllers\HeroSectionController')->names([
        'index' => 'admin.hero-sections.index',
        'create' => 'admin.hero-sections.create',
        'store' => 'admin.hero-sections.store',
        'edit' => 'admin.hero-sections.edit',
        'update' => 'admin.hero-sections.update',
        'destroy' => 'admin.hero-sections.destroy'
    ]);
    Route::post('/hero-sections/update-order', [
        '\App\Http\Controllers\HeroSectionController', 'updateOrder'
    ])->name('admin.hero-sections.update-order');
    
    // Event Management Routes
    Route::resource('events', '\App\Http\Controllers\EventController')->names([
        'index' => 'admin.events.index',
        'create' => 'admin.events.create',
        'store' => 'admin.events.store',
        'edit' => 'admin.events.edit',
        'update' => 'admin.events.update',
        'destroy' => 'admin.events.destroy'
    ]);
    Route::post('/events/update-order', [
        '\App\Http\Controllers\EventController', 'updateOrder'
    ])->name('admin.events.update-order');
    
    // Happening Management Routes
    Route::resource('happenings', '\App\Http\Controllers\HappeningController')->names([
        'index' => 'admin.happenings.index',
        'create' => 'admin.happenings.create',
        'store' => 'admin.happenings.store',
        'edit' => 'admin.happenings.edit',
        'update' => 'admin.happenings.update',
        'destroy' => 'admin.happenings.destroy'
    ]);
    Route::post('/happenings/update-order', [
        '\App\Http\Controllers\HappeningController', 'updateOrder'
    ])->name('admin.happenings.update-order');
});

// School Admin Routes
Route::middleware(['auth', 'user.type:school_admin'])->prefix('school')->group(function () {
    Route::get('/dashboard', function () {
        return view('school.dashboard');
    })->name('school.dashboard');
});

// Student Routes
Route::middleware(['auth', 'user.type:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});
