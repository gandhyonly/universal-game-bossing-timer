<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - accessible to all authenticated users (admin, editor, and viewer)
    Route::get('/dashboard', [TimerController::class, 'index'])->name('dashboard');

    // Timer creation and deletion - admin only
    Route::middleware('admin')->group(function () {
        Route::get('/timers/create', [TimerController::class, 'create'])->name('timers.create');
        Route::post('/timers', [TimerController::class, 'store'])->name('timers.store');
        Route::delete('/timers/{timer}', [TimerController::class, 'destroy'])->name('timers.destroy');
    });

    // Timer updates - admin and editor
    Route::middleware('editor')->group(function () {
        Route::get('/timers/{timer}/edit', [TimerController::class, 'edit'])->name('timers.edit');
        Route::put('/timers/{timer}', [TimerController::class, 'update'])->name('timers.update');
        Route::post('/timers/{timer}/update-spawn', [TimerController::class, 'updateSpawnTime'])->name('timers.update-spawn');
        Route::post('/timers/{timer}/reset-spawn', [TimerController::class, 'resetSpawnTime'])->name('timers.reset-spawn');
    });

    // User management - admin only
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    });

    // Profile - accessible to all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
