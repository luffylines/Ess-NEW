<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Carbon\Carbon;

// Get some existing users
$users = User::limit(3)->get();

if ($users->count() > 0) {
    echo "Creating test data...\n";
    
    // Create some attendance records for the last 7 days
    for ($i = 0; $i < 7; $i++) {
        $date = Carbon::now()->subDays($i);
        
        foreach ($users as $user) {
            // Create random attendance records
            if (rand(1, 10) > 3) { // 70% chance of attendance
                Attendance::create([
                    'user_id' => $user->id,
                    'check_in' => $date->copy()->setTime(8, rand(0, 30)),
                    'check_out' => $date->copy()->setTime(17, rand(0, 30)),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
    
    // Create some activity logs
    $actions = ['login', 'logout', 'attendance_submitted', 'profile_updated', 'leave_requested'];
    
    for ($i = 0; $i < 20; $i++) {
        $date = Carbon::now()->subDays(rand(0, 6));
        $user = $users->random();
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $actions[array_rand($actions)],
            'description' => 'Test activity log entry',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
    
    echo "Test data created successfully!\n";
    echo "Attendances: " . Attendance::count() . "\n";
    echo "Activity Logs: " . ActivityLog::count() . "\n";
    echo "Users: " . User::count() . "\n";
} else {
    echo "No users found. Please create some users first.\n";
}