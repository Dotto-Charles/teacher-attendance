
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
use App\Http\Controllers\District\DistrictAssignmentController;
use App\Http\Controllers\District\DistrictReportController;
use App\Http\Controllers\District\DistrictWardController;
use App\Http\Controllers\Ward\WardOfficerController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
 use App\Http\Controllers\Admin\AdminController;

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
| CONTACT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/contact', function () {
    return view('contact');
})->name('contact.index');

Route::post('/contact', function () {
    // Handle contact form submission
    // For now, just redirect back with success message
    return back()->with('success', 'Ujumbe wako umetumwa! Tutawasiliana nawe hivi karibuni.');
})->name('contact.store');

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
    Route::middleware(['role:teacher,head_teacher'])->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/check', [AttendanceController::class, 'check'])->name('attendance.check');
    });

        Route::middleware(['auth'])->group(function () {
    // Teacher check-in via AJAX
    Route::post('/teacher/checkin', [TeacherDashboardController::class, 'checkIn'])
        ->name('teacher.checkin');
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
    Route::middleware(['role:head_teacher'])->group(function () {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    });

    /*
    | ADMIN
    */
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/users', [AdminController::class, 'users'])
            ->name('users.index');

        Route::get('/users/pending', [AdminController::class, 'pendingUsers'])
            ->name('users.pending');

        Route::get('/users/roles', [AdminController::class, 'roles'])
            ->name('users.roles');

        Route::put('/users/{user}/approve', [AdminController::class, 'approve'])
            ->name('users.approve');

        Route::put('/users/{user}/block', [AdminController::class, 'block'])
            ->name('users.block');

        Route::put('/users/{user}/role', [AdminController::class, 'changeRole'])
            ->name('users.role');

        Route::put('/users/{user}', [AdminController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [AdminController::class, 'destroy'])
            ->name('users.destroy');

        Route::get('/reports', [AdminController::class, 'reports'])
            ->name('reports');

        Route::get('/activity', [AdminController::class, 'activity'])
            ->name('activity');

            Route::put('/users/{user}/password', [AdminController::class, 'changePassword'])
    ->name('users.password');

Route::put('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])
    ->name('users.reset.password');

    Route::get('/schools', [AdminController::class, 'schools'])
    ->name('schools.index');

Route::get('/schools/{school}', [AdminController::class, 'showSchool'])
    ->name('schools.show');

Route::put('/schools/{school}', [AdminController::class, 'updateSchool'])
    ->name('schools.update');

Route::delete('/schools/{school}', [AdminController::class, 'deleteSchool'])
    ->name('schools.delete');

});
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


use App\Http\Controllers\HeadTeacher\HeadTeacherController;
 
Route::middleware(['auth', 'role:head_teacher'])
    ->prefix('headteacher')
    ->name('headteacher.')
    ->group(function () {
 
        // Dashboard
        Route::get('/dashboard',  [HeadTeacherController::class, 'dashboard'])->name('dashboard');
 
        // Teachers
        Route::get('/teachers',   [HeadTeacherController::class, 'teachers'])->name('teachers');
 
        // Attendance
        Route::get('/attendance', [HeadTeacherController::class, 'attendance'])->name('attendance');
 
        // Approvals
        Route::get('/approvals',                      [HeadTeacherController::class, 'approvals'])->name('approvals');
        Route::patch('/approvals/{user}/approve',     [HeadTeacherController::class, 'approve'])->name('approve');
        Route::patch('/approvals/{user}/reject',      [HeadTeacherController::class, 'reject'])->name('reject');
 
        // Reports
        Route::get('/reports',            [HeadTeacherController::class, 'reports'])->name('reports');
        Route::get('/reports/export/csv', [HeadTeacherController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('/reports/export/pdf', [HeadTeacherController::class, 'exportPdf'])->name('reports.export.pdf');
 
        // Check-in (GPS)
        Route::post('/checkin', [HeadTeacherController::class, 'checkIn'])->name('checkin');
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

            
        // ── Assignments & Transfers ───────────────────────────────────
        Route::get('/assignments', [DistrictAssignmentController::class, 'index'])
            ->name('assignments.index');
 
        // Ward Officer
        Route::post('/assignments/assign-ward-officer', [DistrictAssignmentController::class, 'assignWardOfficer'])
            ->name('assignments.assign-ward-officer');
 
        Route::delete('/assignments/ward-officers/{user}/remove', [DistrictAssignmentController::class, 'removeWardOfficer'])
            ->name('assignments.remove-ward-officer');
 
        // Head Teacher
        Route::post('/assignments/assign-head-teacher', [DistrictAssignmentController::class, 'assignHeadTeacher'])
            ->name('assignments.assign-head-teacher');
 
        Route::delete('/assignments/head-teachers/{user}/remove', [DistrictAssignmentController::class, 'removeHeadTeacher'])
            ->name('assignments.remove-head-teacher');
 
        // Ward pages
        Route::get('/wards', [DistrictWardController::class, 'index'])
            ->name('wards.index');
 
        // Transfers
        Route::post('/assignments/transfers', [DistrictAssignmentController::class, 'requestTransfer'])
            ->name('assignments.request-transfer');
 
        Route::patch('/assignments/transfers/{transfer}/approve', [DistrictAssignmentController::class, 'approveTransfer'])
            ->name('assignments.approve-transfer');
 
        Route::patch('/assignments/transfers/{transfer}/reject', [DistrictAssignmentController::class, 'rejectTransfer'])
            ->name('assignments.reject-transfer');

             // INDEX (kuonyesha page ya reports)
    Route::get('/reports', [DistrictReportController::class, 'index'])
        ->name('reports.index');

    // EXPORT CSV
    Route::get('/reports/export/csv', [DistrictReportController::class, 'exportCsv'])
        ->name('reports.export.csv');

    // EXPORT PDF
    Route::get('/reports/export/pdf', [DistrictReportController::class, 'exportPdf'])
        ->name('reports.export.pdf');


        // AJAX - shule za kata
        Route::get('/schools-by-ward', function (\Illuminate\Http\Request $r) {
            return \App\Models\School::where('ward_id', $r->ward_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        })->name('schools.by.ward');

    });
    

Route::middleware(['auth', 'role:ward_officer'])
    ->prefix('ward')
    ->name('ward.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [WardOfficerController::class, 'dashboard'])
            ->name('dashboard');

        // Attendance
        Route::get('/attendance', [WardOfficerController::class, 'attendanceIndex'])
            ->name('attendance.index');

        // Schools
        Route::get('/schools', [WardOfficerController::class, 'schoolsIndex'])
            ->name('schools.index');

        // Teachers
        Route::get('/teachers', [WardOfficerController::class, 'teachersIndex'])
            ->name('teachers.index');

        // Approvals
        Route::get('/approvals', [WardOfficerController::class, 'approvalsIndex'])
            ->name('approvals.index');

        Route::patch('/approvals/{user}/approve', [WardOfficerController::class, 'approveTeacher'])
            ->name('approvals.approve');

        Route::patch('/approvals/{user}/reject', [WardOfficerController::class, 'rejectTeacher'])
            ->name('approvals.reject');

        // Transfers
        Route::get('/transfers', [WardOfficerController::class, 'transfersIndex'])
            ->name('transfers.index');

        Route::post('/transfers/request', [WardOfficerController::class, 'requestTransfer'])
            ->name('transfers.request');

        // Reports
        Route::get('/reports', [WardOfficerController::class, 'reportsIndex'])
            ->name('reports.index');

        Route::get('/reports/export/csv', [WardOfficerController::class, 'exportCsv'])
            ->name('reports.export.csv');

            Route::get('/attendance/export/csv', [WardOfficerController::class, 'exportAttendanceCsv'])
    ->name('attendance.export.csv');

    Route::get('/schools/{school}', [WardOfficerController::class, 'schoolShow'])
    ->name('schools.show');

    Route::get('/schools/{school}/export/pdf', [WardOfficerController::class, 'exportSchoolPdf'])
    ->name('schools.export.pdf');

    Route::get('/teachers/{user}/history', [WardOfficerController::class, 'teacherHistory'])
    ->name('teachers.history');

   

Route::get('/wards/{councilId}', [LocationController::class, 'wards']);
    });

    Route::get('/about-developer', function () {
    return view('about-developer');
});