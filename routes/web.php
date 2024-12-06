<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\RoleMiddleware; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\UserController;

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

Route::group(['prefix' => 'admin'], function () {
    Route::get('/create-subject', [AdminController::class, 'createSubject'])->name('admin.createSubject');
    Route::post('/store', [AdminController::class, 'storeSubject'])->name('admin.storeSubject');
    Route::get('/notification', [AdminController::class, 'notification'])->name('admin.notification');
    Route::get('/notification/class', [AdminController::class, 'classNotification'])->name('admin.notification.class');
    Route::post('/notification/class/store', [AdminController::class, 'storeClassNotification'])->name('admin.notification.class.store');
    Route::get('/notification/class/edit/{id}', [AdminController::class, 'editClassNotification'])->name('admin.notification.class.edit');
    Route::put('/notification/class/update/{id}', [AdminController::class, 'updateClassNotification'])->name('admin.notification.class.update');
    Route::delete('/notification/class/delete/{id}', [AdminController::class, 'deleteClassNotification'])->name('admin.notification.class.delete');
    Route::get('/notification/assignment', [AdminController::class, 'assignmentNotification'])->name('admin.notification.assignment');
    Route::post('/notification/assignment/store', [AdminController::class, 'storeAssignmentNotification'])->name('admin.notification.assignment.store');
    Route::get('/notification/assignment/edit/{id}', [AdminController::class, 'editAssignmentNotification'])->name('admin.notification.assignment.edit');
    Route::put('/notification/assignment/update/{id}', [AdminController::class, 'updateAssignmentNotification'])->name('admin.notification.assignment.update');
    Route::delete('/notification/assignment/delete/{id}', [AdminController::class, 'deleteAssignmentNotification'])->name('admin.notification.assignment.delete');
    Route::get('/notification/exam', [AdminController::class, 'examNotification'])->name('admin.notification.exam');
    Route::post('/notification/exam/store', [AdminController::class, 'storeExamNotification'])->name('admin.notification.exam.store');
    Route::get('/notification/exam/edit/{id}', [AdminController::class, 'editExamNotification'])->name('admin.notification.exam.edit');
    Route::put('/notification/exam/update/{id}', [AdminController::class, 'updateExamNotification'])->name('admin.notification.exam.update');
    Route::delete('/notification/exam/delete/{id}', [AdminController::class, 'deleteExamNotification'])->name('admin.notification.exam.delete');
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