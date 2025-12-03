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
use App\Http\Controllers\GuestPageController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;
// Admin: Store locations and allowed networks
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('stores', \App\Http\Controllers\Admin\StoreController::class)->except(['show']);
    Route::post('stores/{store}/toggle-status', [\App\Http\Controllers\Admin\StoreController::class, 'toggleStatus'])->name('stores.toggle-status');
    
    Route::resource('networks', \App\Http\Controllers\Admin\AllowedNetworkController::class)->except(['show']);
    Route::post('networks/{network}/toggle-status', [\App\Http\Controllers\Admin\AllowedNetworkController::class, 'toggleStatus'])->name('networks.toggle-status');
});

// Debug route to check IP detection
Route::get('/debug-ip', function() {
    return response()->json([
        'detected_ip' => request()->ip(),
        'all_ips' => [
            'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? null,
            'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? null,
            'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? null,
            'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? null,
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
        ],
        'database_allowed_ips' => \App\Models\AllowedNetwork::where('active', true)->pluck('ip_ranges')->flatten()->toArray()
    ]);
});

// Mobile compatibility test route
Route::get('/mobile-test', function() {
    $userAgent = request()->header('User-Agent', '');
    $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry/i', $userAgent);
    
    return response()->json([
        'is_mobile' => $isMobile,
        'user_agent' => $userAgent,
        'device_info' => [
            'is_ios' => preg_match('/iPhone|iPad|iPod/i', $userAgent),
            'is_android' => preg_match('/Android/i', $userAgent),
            'is_brave' => strpos($userAgent, 'Brave') !== false,
            'browser_capabilities' => [
                'supports_javascript' => true, // We can't really detect this server-side
                'screen_width' => 'unknown', // Would need client-side detection
            ]
        ],
        'headers' => request()->headers->all()
    ]);
});

// Mobile test page
Route::get('/mobile-test-page', function() {
    return view('mobile-test');
});

// Geo-fencing API: validate employee location before allowing attendance actions
Route::middleware(['auth'])->post('/api/attendance/check-location', [\App\Http\Controllers\AttendanceController::class, 'checkLocation']);

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])
    ->middleware(['auth'])
    ->name('profile.updateEmail');

Route::patch('/profile/update-password', [ProfileController::class, 'updatePassword'])
    ->middleware(['auth'])
    ->name('profile.updatePassword');

    
Route::middleware('guest')->group(function () {
    Route::get('/about', [GuestPageController::class, 'about'])->name('about');
    Route::get('/guest/attendance', [GuestPageController::class, 'attendance'])->name('guest.attendance');
    Route::get('/guest/reports', [GuestPageController::class, 'reports'])->name('guest.reports');
    Route::get('/guest/tasks', [GuestPageController::class, 'tasks'])->name('guest.tasks');
    //contact page and feedback submission
    Route::get('/contact', [GuestPageController::class, 'contact'])->name('contact');
    Route::post('/submit-feedback', [FeedbackController::class, 'submit'])->name('submitFeedback');
    Route::get('/terms', [GuestPageController::class, 'terms'])->name('terms');
    Route::get('/system-info', [GuestPageController::class, 'systemInfo'])->name('system-info');
});

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
    

    //overtime show
    Route::resource('overtime', OvertimeController::class)->middleware('auth');
    
    // Fix negative overtime hours (admin only)
    Route::post('/overtime/fix-negative-hours', [OvertimeController::class, 'fixNegativeHours'])
        ->middleware(['auth', 'role:hr,manager'])
        ->name('overtime.fixNegativeHours');
    
    
    // HR/Manager create attendance for employee
    Route::post('/hr/create-for-employee', [AttendanceController::class, 'createForEmployee'])
        ->middleware(['auth', 'role:hr,manager'])
        ->name('hr.createForEmployee');

    // HR/Manager attendance management routes
    Route::middleware(['role:hr,manager'])->group(function () {
        Route::get('/hr/pending-approvals', [AttendanceController::class, 'pendingApprovals'])->name('hr.pending-approvals');
        Route::post('/hr/approve/{id}', [AttendanceController::class, 'approve'])->name('hr.approve');
        Route::post('/hr/reject/{id}', [AttendanceController::class, 'reject'])->name('hr.reject');
        Route::get('/hr/management', [AttendanceController::class, 'managementDashboard'])->name('hr.management');
        Route::post('/hr/mark', [AttendanceController::class, 'markAttendance'])->name('hr.mark');
        Route::put('/hr/edit-employee/{id}', [AttendanceController::class, 'editEmployeeAttendance'])->name('hr.edit-employee');
        Route::get('/hr/create-for-employee', [AttendanceController::class, 'showCreateForEmployeeForm'])->name('hr.create-for-employee.form');
        Route::post('/hr/create-for-employee', [AttendanceController::class, 'createForEmployee'])->name('hr.createForEmployee');
        
        // Form pages for mark attendance actions
        Route::get('/hr/mark-present', [AttendanceController::class, 'showMarkPresentForm'])->name('hr.mark-present.form');
        Route::get('/hr/mark-absent', [AttendanceController::class, 'showMarkAbsentForm'])->name('hr.mark-absent.form');
        Route::get('/hr/edit-times/{attendance}', [AttendanceController::class, 'showEditTimesForm'])->name('hr.edit-times.form');
    });
    
    //generate Schedule
    // Route to show the generate shift schedule form
    Route::get('/attendance/generateShiftSchedule', [AttendanceController::class, 'showGenerateShiftScheduleForm'])->name('attendance.showGenerateShiftSchedule');
    
    // Route to handle the form submission for generating shift schedule
    Route::post('/attendance/generateShiftSchedule', [AttendanceController::class, 'generateShiftSchedule'])->name('attendance.generateShiftSchedule');

    // Work Schedule Management
    Route::middleware(['auth'])->group(function () {
        // Employee routes - view own schedules
        Route::get('/my-schedules', [\App\Http\Controllers\WorkScheduleController::class, 'mySchedules'])->name('schedules.my');
        Route::post('/schedules/{schedule}/acknowledge', [\App\Http\Controllers\WorkScheduleController::class, 'acknowledge'])->name('schedules.acknowledge');
        
        // Manager/HR routes - manage all schedules
        Route::middleware(['role:manager,hr,admin'])->group(function () {
            Route::resource('schedules', \App\Http\Controllers\WorkScheduleController::class);
            Route::post('/schedules/bulk-create', [\App\Http\Controllers\WorkScheduleController::class, 'bulkCreate'])->name('schedules.bulk-create');
        });
    });    // Attendance request page for employee
    Route::get('/attendance/requests', [AttendanceController::class, 'requestForm'])->name('attendance.requests');
    // Submit attendance request
    Route::post('/attendance/requests', [AttendanceController::class, 'submitRequest'])->name('attendance.requests.submit');
    // Payslip listing page
    Route::get('/payslips', [PayslipController::class, 'index'])->name('payslip.index');
    Route::get('/payslips/{payslip}', [PayslipController::class, 'show'])->name('payslips.show');
    Route::get('/payslips/{payslip}/download', [PayslipController::class, 'download'])->name('payslips.download');
    Route::get('/payslips/debug/test-download', [PayslipController::class, 'testDownload'])->name('payslips.debug');

    // Leave requests for employees
    Route::resource('leave', App\Http\Controllers\LeaveController::class);

});

// HR/Manager Payroll Routes
Route::middleware(['auth', 'role:hr,manager,admin'])->group(function () {
    Route::get('/hr/payroll', [PayslipController::class, 'payrollIndex'])->name('hr.payroll.index');
    Route::post('/hr/payroll/generate', [PayslipController::class, 'generatePayroll'])->name('hr.payroll.generate');
    Route::post('/hr/payroll/generate-all', [PayslipController::class, 'generateAllPayrolls'])->name('hr.payroll.generate-all');
    Route::post('/hr/payroll/{payroll}/approve', [PayslipController::class, 'approvePayroll'])->name('hr.payroll.approve');
    Route::post('/hr/payroll/{payroll}/recalculate', [PayslipController::class, 'recalculatePayroll'])->name('hr.payroll.recalculate');
    Route::post('/hr/payroll/bulk-approve', [PayslipController::class, 'bulkApprove'])->name('hr.payroll.bulk-approve');
    Route::get('/hr/payroll/{payroll}', [PayslipController::class, 'showPayroll'])->name('hr.payroll.show');
    Route::get('/hr/payroll/{payroll}/edit', [PayslipController::class, 'editPayroll'])->name('hr.payroll.edit');
    Route::put('/hr/payroll/{payroll}', [PayslipController::class, 'updatePayroll'])->name('hr.payroll.update');
    Route::delete('/hr/payroll/{payroll}', [PayslipController::class, 'deletePayroll'])->name('hr.payroll.delete');
    Route::get('/hr/payslips', [PayslipController::class, 'payslipManagement'])->name('hr.payslips.index');
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
    Route::post('/admin/employees/{id}/resend', [EmployeeController::class, 'resendInvitation'])
     ->name('admin.employees.resend');


    // Activity Logs
    Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('/admin/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('admin.activity-logs.show');
    Route::get('/admin/activity-logs/export/pdf/{id?}', [ActivityLogController::class, 'exportPdf'])->name('admin.activity-logs.export.pdf');
    Route::get('/admin/activity-logs/export/csv/{id?}', [ActivityLogController::class, 'exportCsv'])->name('admin.activity-logs.export.csv');
    
    // SMS Configuration
    Route::get('/admin/sms', [\App\Http\Controllers\Admin\SmsController::class, 'index'])->name('admin.sms.index');
    Route::post('/admin/sms/test', [\App\Http\Controllers\Admin\SmsController::class, 'test'])->name('admin.sms.test');
    Route::get('/admin/sms/check-devices', [\App\Http\Controllers\Admin\SmsController::class, 'checkDevices'])->name('admin.sms.check-devices');
    Route::get('/admin/sms/test-adb', [\App\Http\Controllers\Admin\SmsController::class, 'testAdb'])->name('admin.sms.test-adb');
});

// Public routes for employee profile completion
Route::get('/employee/complete/{token}', [EmployeeController::class, 'completeForm'])->name('employees.complete');
Route::post('/employee/complete/{token}', [EmployeeController::class, 'completeStore'])->name('employees.complete.store');


Route::middleware(['web', 'auth', 'hr'])->group(function () {
    Route::get('/hr/pending', [HrAttendanceController::class, 'pendingAttendance'])->name('hr.pending');
    Route::get('/hr/approve', function () {
        return redirect()->route('hr.pending');
    });

    Route::post('/hr/approve', [HrAttendanceController::class, 'approveAttendance'])->name('hr.approve.attendance');
    Route::get('/hr/attendance', [HrAttendanceController::class, 'monitorAttendance'])->name('hr.attendance');
    Route::get('/hr/monitor', [HrAttendanceController::class, 'monitorAttendance'])->name('hr.monitor');
    Route::post('/hr/approve/{id}', [HrAttendanceController::class, 'approveRecord'])->name('hr.approve');
    Route::post('/hr/reject/{id}', [HrAttendanceController::class, 'rejectRecord'])->name('hr.reject');
    Route::delete('/hr/attendance/{id}', [HrAttendanceController::class, 'deleteRecord'])->name('hr.attendance.delete');
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
