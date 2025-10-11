<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use App\Models\ActivityLog;
use Carbon\Carbon;

echo "Creating comprehensive test data for Employee Dashboard...\n";

// Get employee users
$employees = User::where('role', 'employee')->get();

if ($employees->count() > 0) {
    foreach ($employees as $employee) {
        echo "\nCreating data for {$employee->name} (ID: {$employee->employee_id})...\n";
        
        // Create attendance records for current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;
        
        for ($day = 1; $day <= min($daysInMonth, Carbon::now()->day); $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            
            // Skip weekends
            if ($date->isWeekend()) continue;
            
            // 85% chance of attendance
            if (rand(1, 100) <= 85) {
                Attendance::updateOrCreate([
                    'user_id' => $employee->id,
                    'date' => $date->format('Y-m-d')
                ], [
                    'time_in' => $date->copy()->setTime(8, rand(0, 30)),
                    'time_out' => $date->copy()->setTime(17, rand(0, 30)),
                    'day_type' => 'regular',
                    'status' => 'approved',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
        
        // Create some overtime requests
        for ($i = 0; $i < rand(2, 5); $i++) {
            $date = Carbon::now()->subDays(rand(1, 30));
            $startTime = $date->copy()->setTime(17, 0); // Start at 5 PM
            $endTime = $startTime->copy()->addHours(rand(1, 4)); // Add 1-4 hours
            $totalHours = $endTime->diffInHours($startTime);
            
            OvertimeRequest::create([
                'user_id' => $employee->id,
                'overtime_date' => $date->format('Y-m-d'),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'total_hours' => $totalHours,
                'reason' => 'Project deadline completion',
                'status' => rand(0, 10) > 3 ? 'approved' : 'pending',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
        
        // Create some leave requests
        for ($i = 0; $i < rand(1, 3); $i++) {
            $startDate = Carbon::now()->subDays(rand(5, 60));
            $endDate = $startDate->copy()->addDays(rand(1, 3));
            
            LeaveRequest::create([
                'user_id' => $employee->id,
                'leave_type' => ['sick', 'vacation', 'personal'][rand(0, 2)],
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'reason' => 'Personal matters',
                'status' => ['approved', 'pending', 'rejected'][rand(0, 2)],
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ]);
        }
        
        // Create activity logs
        $activities = [
            'login' => 'Logged into the system',
            'logout' => 'Logged out from the system',
            'attendance_time_in' => 'Marked time in',
            'attendance_time_out' => 'Marked time out',
            'profile_updated' => 'Updated profile information',
            'leave_requested' => 'Submitted leave request',
            'overtime_requested' => 'Submitted overtime request'
        ];
        
        for ($i = 0; $i < rand(10, 20); $i++) {
            $actionType = array_rand($activities);
            $date = Carbon::now()->subDays(rand(0, 30));
            
            ActivityLog::create([
                'user_id' => $employee->id,
                'action_type' => $actionType,
                'description' => $activities[$actionType],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Test Browser)',
                'properties' => json_encode(['test_data' => true]),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
        
        echo "✓ Created data for {$employee->name}\n";
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Total Attendance Records: " . Attendance::count() . "\n";
    echo "Total Leave Requests: " . LeaveRequest::count() . "\n";
    echo "Total Overtime Requests: " . OvertimeRequest::count() . "\n";
    echo "Total Activity Logs: " . ActivityLog::count() . "\n";
    echo "\nTest data creation completed!\n";
    
} else {
    echo "No employee users found. Please create some employee users first.\n";
}

echo "\nYou can now login with any employee account to see the comprehensive dashboard!\n";