<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\UserStoryController;
use App\Http\Controllers\UserStoryListController;
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

    // Personas
    Route::get('/personas', [PersonaController::class, 'index'])->name('personas.index');
    Route::post('/personas', [PersonaController::class, 'store'])->name('personas.store');
    Route::put('/personas/{persona}', [PersonaController::class, 'update'])->name('personas.update');
    Route::delete('/personas/{persona}', [PersonaController::class, 'destroy'])->name('personas.destroy');

    // User Stories (vue liste)
    Route::get('/user-stories', [UserStoryListController::class, 'index'])->name('user-stories.index');

    // Réglages
    Route::get('/settings', function () {
        return \Inertia\Inertia::render('Settings/Index');
    })->name('settings.index');

    // Sprints / Roadmap
    Route::get('/sprints', [SprintController::class, 'index'])->name('sprints.index');
    Route::post('/sprints', [SprintController::class, 'store'])->name('sprints.store');
    Route::put('/sprints/{sprint}', [SprintController::class, 'update'])->name('sprints.update');
    Route::delete('/sprints/{sprint}', [SprintController::class, 'destroy'])->name('sprints.destroy');

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
