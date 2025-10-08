<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HrAttendanceController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Test route for theme toggle
Route::get('/test-theme', function () {
    return view('test-theme');
})->name('test.theme');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])
    ->middleware(['auth'])
    ->name('profile.updateEmail');

Route::patch('/profile/update-password', [ProfileController::class, 'updatePassword'])
    ->middleware(['auth'])
    ->name('profile.updatePassword');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    // My Attendance page for employee
    Route::get('/attendance/my', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    Route::get('/attendance', [AttendanceController::class, 'attendanceForm'])->name('attendance.form');
    Route::post('/attendance', [AttendanceController::class, 'submitAttendance'])->name('attendance.submit');
    Route::get('/attendance/edit/{id}', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/attendance/delete/{id}', [AttendanceController::class, 'deleteConfirm'])->name('attendance.delete');
    Route::get('/attendance/pdf', [AttendanceController::class, 'generatePDF'])->name('attendance.pdf');
    Route::get('/attendance/search', [AttendanceController::class, 'search'])->name('attendance.search');
    //generate Schedule
    // Route to show the generate shift schedule form
Route::get('/attendance/generateShiftSchedule', [AttendanceController::class, 'showGenerateShiftScheduleForm'])->name('attendance.showGenerateShiftSchedule');

// Route to handle the form submission for generating shift schedule
Route::post('/attendance/generateShiftSchedule', [AttendanceController::class, 'generateShiftSchedule'])->name('attendance.generateShiftSchedule');

    // Attendance request page for employee
    Route::get('/attendance/requests', [AttendanceController::class, 'requestForm'])->name('attendance.requests');
    // Submit attendance request
    Route::post('/attendance/requests', [AttendanceController::class, 'submitRequest'])->name('attendance.requests.submit');
    // Payslip listing page
    Route::get('/payslips', [PayslipController::class, 'index'])->name('payslip.index');

    // Overtime requests for employees
    Route::resource('overtime', OvertimeController::class);
    
    // Leave requests for employees
    Route::resource('leave', App\Http\Controllers\LeaveController::class);

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin', [EmployeeController::class, 'index'])->name('admin.index');
    Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/admin/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/admin/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

    Route::view('/admin/loans/sss', 'loans.sss')->name('admin.loans.sss');
    Route::view('/admin/loans/pagibig', 'loans.pagibig')->name('admin.loans.pagibig');
    Route::view('/admin/loans/company', 'loans.company')->name('admin.loans.company');
    
    // Activity Logs
    Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('/admin/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('admin.activity-logs.show');
    Route::get('/admin/activity-logs/export/pdf/{id?}', [ActivityLogController::class, 'exportPdf'])->name('admin.activity-logs.export.pdf');
    Route::get('/admin/activity-logs/export/csv/{id?}', [ActivityLogController::class, 'exportCsv'])->name('admin.activity-logs.export.csv');
});

// Public routes for employee profile completion
Route::get('/employee/complete/{token}', [EmployeeController::class, 'completeForm'])->name('employees.complete');
Route::post('/employee/complete/{token}', [EmployeeController::class, 'completeStore'])->name('employees.complete.store');

// Test route for demonstration (remove in production)
Route::get('/test-employee-invite', function() {
    $testUser = \App\Models\User::create([
        'name' => 'Test Employee',
        'email' => 'test@example.com',
        'role' => 'employee',
        'password' => \Illuminate\Support\Facades\Hash::make('temppass'),
        'remember_token' => \Illuminate\Support\Str::random(60),
    ]);
    
    $inviteLink = route('employees.complete', ['token' => $testUser->remember_token]);
    
    return "Test employee created! Complete profile at: <a href='{$inviteLink}'>{$inviteLink}</a>";
})->name('test.employee.invite');

Route::middleware(['web', 'auth', 'hr'])->group(function () {
    Route::get('/hr/pending', [HrAttendanceController::class, 'pendingAttendance'])->name('hr.pending');
    Route::get('/hr/approve', function () {
        return redirect()->route('hr.pending');
    });

    Route::post('/hr/approve', [HrAttendanceController::class, 'approveAttendance'])->name('hr.approve');
    Route::get('/hr/attendance', [HrAttendanceController::class, 'monitorAttendance'])->name('hr.attendance');
    Route::get('/hr/monitor', [HrAttendanceController::class, 'monitorAttendance'])->name('hr.monitor');
    Route::get('/hr/approveleave', [HrAttendanceController::class, 'showApproveLeave'])->name('hr.approveleave.show');
    Route::post('/hr/approveleave', [HrAttendanceController::class, 'approveleave'])->name('hr.approveleave');
    Route::get('/hr/approveOvertime', [HrAttendanceController::class, 'showApproveOvertime'])->name('hr.approveOvertime.show');
    Route::post('/hr/approveOvertime', [HrAttendanceController::class, 'approveOvertime'])->name('hr.approveOvertime');
    Route::get('/hr/reports', [HrAttendanceController::class, 'monthlyReport'])->name('hr.reports');
    Route::get('/hr/reports/export', [HrAttendanceController::class, 'exportMonthlyReport'])->name('hr.reports.export');
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.check');


require __DIR__.'/auth.php';
