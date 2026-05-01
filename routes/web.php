```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\TeacherRegistrationController;
use App\Http\Controllers\WardOfficerAssignmentController;
use App\Http\Controllers\District\DistrictDashboardController;
use App\Http\Controllers\District\DistrictTeacherController;
use App\Http\Controllers\District\DistrictSchoolController;
use App\Http\Controllers\District\DistrictAttendanceController;

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
| DASHBOARD (inaelekeza kwa role sahihi)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    | PROFILE
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    | ATTENDANCE (teacher, head_teacher, ward_officer)
    */
    Route::middleware(['role:teacher,head_teacher,ward_officer'])->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/check', [AttendanceController::class, 'check'])->name('attendance.check');
    });

    /*
    | REPORTS
    */
    Route::get('/attendance/reports', [AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance/export/pdf', [AttendanceController::class, 'exportPdf'])->name('attendance.export.pdf');

    /*
    | SCHOOL REGISTRATION
    */
    Route::get('/schools/register', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/schools/register', [SchoolController::class, 'store'])->name('schools.store');

    /*
    | TEACHER SCHOOL REGISTER / TRANSFER
    */
    Route::get('/teacher/register-school', [TeacherRegistrationController::class, 'create'])->name('teacher.register.school');
    Route::post('/teacher/register-school', [TeacherRegistrationController::class, 'store'])->name('teacher.register.store');

    /*
    | APPROVALS (head_teacher, ward_officer)
    */
    Route::middleware(['role:head_teacher,ward_officer'])->group(function () {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    });

    /*
    | ADMIN
    */
    Route::get('/admin/assign-ward-officer', [WardOfficerAssignmentController::class, 'index'])->name('admin.assign.ward');
    Route::post('/admin/assign-ward-officer', [WardOfficerAssignmentController::class, 'store'])->name('admin.assign.ward.store');

    /*
    | AJAX HELPER - Schools by Ward
    */
    Route::get('/get-schools/{ward}', function (\App\Models\Ward $ward) {
        return $ward->schools()->orderBy('name')->get(['id', 'name']);
    })->name('schools.by.ward');

    Route::get('/search-users', function (\Illuminate\Http\Request $r) {
        return \App\Models\User::where('first_name', 'like', "%{$r->q}%")
            ->orWhere('last_name', 'like', "%{$r->q}%")
            ->limit(10)->get(['id', 'first_name', 'last_name', 'check_number']);
    })->name('users.search');

});

/*
|--------------------------------------------------------------------------
| DISTRICT OFFICER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:district_officer'])
    ->prefix('district')
    ->name('district.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DistrictDashboardController::class, 'index'])
            ->name('dashboard');

        // Walimu
        Route::get('/teachers', [DistrictTeacherController::class, 'index'])
            ->name('teachers.index');
        Route::patch('/teachers/{user}/approve', [DistrictTeacherController::class, 'approve'])
            ->name('teachers.approve');
        Route::patch('/teachers/{user}/reject', [DistrictTeacherController::class, 'reject'])
            ->name('teachers.reject');

        // Schools
        Route::get('/schools', [DistrictSchoolController::class, 'index'])
            ->name('schools.index');

            Route::get('/schools/{school}', [DistrictSchoolController::class, 'show'])
    ->name('schools.show');

    Route::post('/schools', [DistrictSchoolController::class, 'store'])
    ->name('schools.store');

    Route::patch('/schools/{school}/toggle', [DistrictSchoolController::class, 'toggle'])
    ->name('schools.toggle');

    Route::put('/schools/{school}', [DistrictSchoolController::class, 'update'])
    ->name('schools.update');

    Route::get('/attendance', [DistrictAttendanceController::class, 'index'])
            ->name('attendance.index');

        Route::get('/attendance/export/csv', [DistrictAttendanceController::class, 'exportCsv'])
            ->name('attendance.export.csv');

        // AJAX - shule za kata
        Route::get('/schools-by-ward', function (\Illuminate\Http\Request $r) {
            return \App\Models\School::where('ward_id', $r->ward_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        })->name('schools.by.ward');

    });

