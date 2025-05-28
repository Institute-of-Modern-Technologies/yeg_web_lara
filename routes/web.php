<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);


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
