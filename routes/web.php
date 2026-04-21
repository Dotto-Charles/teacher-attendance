<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\TeacherRegistrationController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE (ONLY TEACHER CHECK-IN)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:teacher'])->group(function () {

        Route::get('/attendance', [AttendanceController::class, 'index'])
            ->name('attendance.index');

        Route::post('/attendance/check', [AttendanceController::class, 'check'])
            ->name('attendance.check');
    });

    /*
    |--------------------------------------------------------------------------
    | REPORT (TEACHER + HEADTEACHER)
    |--------------------------------------------------------------------------
    */
    Route::get('/attendance/reports', [AttendanceController::class, 'report'])
        ->name('attendance.report');

    Route::get('/attendance/export/pdf', [AttendanceController::class, 'exportPdf'])
        ->name('attendance.export.pdf');

    /*
    |--------------------------------------------------------------------------
    | SCHOOL REGISTRATION
    |--------------------------------------------------------------------------
    */
    Route::get('/schools/register', [SchoolController::class, 'create'])
        ->name('schools.create');

    Route::post('/schools/register', [SchoolController::class, 'store'])
        ->name('schools.store');

    /*
    |--------------------------------------------------------------------------
    | TEACHER SCHOOL REGISTER / TRANSFER
    |--------------------------------------------------------------------------
    */
    Route::get('/teacher/register-school', [TeacherRegistrationController::class, 'create'])
        ->name('teacher.register.school');

    Route::post('/teacher/register-school', [TeacherRegistrationController::class, 'store'])
        ->name('teacher.register.store');

    /*
    |--------------------------------------------------------------------------
    | APPROVALS (HEADTEACHER ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:head_teacher'])->group(function () {

        Route::get('/approvals', [ApprovalController::class, 'index'])
            ->name('approvals.index');

        Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])
            ->name('approvals.approve');

        Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])
            ->name('approvals.reject');
    });

});

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/api/wards/{council}', [LocationController::class, 'wards']);
Route::get('/api/schools/{ward}', [LocationController::class, 'schools']);