<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\UserStoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Guest (non authentifié)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Routes Authentifiées
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/', DashboardController::class)->name('dashboard');

    // Backlog Kanban
    Route::get('/backlog', [UserStoryController::class, 'index'])->name('backlog');
    Route::post('/user-stories', [UserStoryController::class, 'store'])->name('user-stories.store');
    Route::put('/user-stories/{userStory}', [UserStoryController::class, 'update'])->name('user-stories.update');
    Route::patch('/user-stories/{userStory}/move', [UserStoryController::class, 'move'])->name('user-stories.move');
    Route::delete('/user-stories/{userStory}', [UserStoryController::class, 'destroy'])->name('user-stories.destroy');

    // Streams detail
    Route::get('/streams/{stream:slug}', [StreamController::class, 'show'])->name('streams.show');

    /*
    |----------------------------------------------------------------------
    | Admin Panel — réservé aux admins (RBAC)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
