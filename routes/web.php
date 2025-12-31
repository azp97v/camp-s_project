<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;

// -----------------------------------------------------------------------------
// Web Routes
// -----------------------------------------------------------------------------
// الصفحة الرئيسية / Home page
// This route returns the `index` Blade view used as the site's public landing page.
Route::get('/', function () {
    return view('index');
})->name('home');

// عرض صفحة التسجيل (register form)
Route::get('/register', function () {
    return view('register');
})->middleware('guest')->name('register');

// عرض صفحة تسجيل الدخول (login form)
Route::get('/login', function () {
    return view('login');
})->middleware('guest')->name('login');

// معالجة تسجيل مستخدم جديد
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest')->name('register.store');

// معالجة تسجيل الدخول
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest')->name('login.store');

// Routes protected by authentication
Route::middleware('auth')->group(function () {
    // صفحة الداشبورد
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');

    // صفحة الإعدادات
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -----------------------------------------------------------------
    // Goals Routes (CRUD)
    // Defines resourceful routes for `goals` handled by `GoalController`.
    // These map standard actions: index, create, store, show, edit, update, destroy.
    Route::resource('goals', GoalController::class);

    // -----------------------------------------------------------------
    // Tasks Routes
    // Tasks are primarily nested under goals when creating (store).
    // Other task actions are exposed as top-level resource routes.
    // - POST /goals/{goal}/tasks -> store a new task for a goal
    // - GET /goals/{goal}/tasks -> view all tasks for a specific goal
    Route::get('/goals/{goal}/tasks', [TaskController::class, 'indexByGoal'])->name('goals.tasks.index');
    Route::post('/goals/{goal}/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Resourceful routes for `tasks` (index, show, edit, update, destroy).
    Route::resource('tasks', TaskController::class, ['except' => ['store']]);

    // Custom action routes used by the timer UI for state transitions.
    Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::post('/tasks/{task}/cancel', [TaskController::class, 'cancel'])->name('tasks.cancel');
    Route::post('/tasks/{task}/start', [TaskController::class, 'start'])->name('tasks.start');
    Route::post('/tasks/{task}/stop', [TaskController::class, 'stop'])->name('tasks.stop');
    Route::post('/tasks/{task}/finish', [TaskController::class, 'finish'])->name('tasks.finish');
    Route::post('/tasks/{task}/update-priority', [TaskController::class, 'updatePriority'])->name('tasks.update-priority');

    // تسجيل الخروج
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/auth.php';
