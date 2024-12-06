<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SubjectController;

// Authentication routes
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Lecturer routes
    Route::middleware([RoleMiddleware::class . ':2'])->group(function () {
        Route::get('/lecturer', [LecturerController::class, 'index'])->name('lecturer.index');
    });

    // Student routes
    Route::middleware([RoleMiddleware::class . ':3'])->group(function () {
        Route::get('/student', [StudentController::class, 'index'])->name('student.index');
    });
});

// CRUD User routes
Route::group(['prefix' => 'user'], function () {
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/create/store', [UserController::class, 'store'])->name('user.store');
    Route::delete('/delete/{id}', [UserController::class, 'deleteUser'])->name('user.delete');
    Route::get('/edit/{id}', [UserController::class, 'editUser'])->name('user.edit');
    Route::put('/update/{id}', [UserController::class, 'updateUser'])->name('user.update');
});

Route::group(['prefix' => 'student'], function () {
    Route::get('/index', [StudentController::class, 'index'])->name('student.index');
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('student.notifications');
});

Route::resource('subjects', SubjectController::class);

Route::resource('groups', GroupController::class);

Route::group(['prefix' => 'admin'], function () {
    Route::prefix('notification')->group(function () {
        Route::get('/', [NotificationController::class, 'notification'])
            ->name('admin.notification');
        Route::get('/{type}/create', [NotificationController::class, 'createNotification'])
            ->name('admin.notification.create');
        Route::post('/{type}/store', [NotificationController::class, 'storeNotification'])
            ->name('admin.notification.store');
        Route::get('/{type}/edit/{id}', [NotificationController::class, 'editNotification'])
            ->name('admin.notification.edit');
        Route::put('/{type}/update/{id}', [NotificationController::class, 'updateNotification'])
            ->name('admin.notification.update');
        Route::delete('/{type}/delete/{id}', [NotificationController::class, 'deleteNotification'])
            ->name('admin.notification.delete');
    });
});

Route::group(['prefix' => 'timetable'], function () {
    Route::get('/index', [TimetableController::class, 'index'])->name('tt.index');
    Route::get('/create', [TimetableController::class, 'create'])->name('tt.create');
    Route::post('/create/store', [TimetableController::class, 'store'])->name('tt.store');
    Route::get('/edit/{id}', [TimetableController::class, 'edit'])->name('tt.edit');
    Route::put('/update/{id}', [TimetableController::class, 'update'])->name('tt.update');
    Route::delete('/delete/{id}', [TimetableController::class, 'delete'])->name('tt.delete');
});

Route::get('/google/redirect', [App\Http\Controllers\GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');