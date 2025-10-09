<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

echo "Creating test activity logs...\n";

// Get existing users
$users = User::all();

if ($users->count() > 0) {
    $actions = [
        'login' => 'Logged into the system',
        'logout' => 'Logged out from the system',
        'attendance_time_in' => 'Marked time in',
        'attendance_time_out' => 'Marked time out',
        'profile_updated' => 'Updated profile information',
        'employee_created' => 'Created new employee',
        'leave_requested' => 'Requested leave',
        'overtime_requested' => 'Requested overtime'
    ];

    // Create activity logs for the last 7 days
    for ($i = 0; $i < 30; $i++) {
        $user = $users->random();
        $action = array_rand($actions);
        $description = $actions[$action];
        
        $date = Carbon::now()->subHours(rand(1, 168)); // Random time in last week
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action_type' => $action,
            'description' => $description,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Test Browser)',
            'properties' => json_encode([
                'test_data' => true,
                'timestamp' => $date->toISOString()
            ]),
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
    
    echo "Created 30 test activity log entries!\n";
    echo "Total activity logs now: " . ActivityLog::count() . "\n";
    echo "Recent activities:\n";
    
    $recent = ActivityLog::with('user')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    foreach ($recent as $activity) {
        echo "- {$activity->user->name}: {$activity->description} ({$activity->created_at->diffForHumans()})\n";
    }
    
} else {
    echo "No users found! Please run the seeders first.\n";
}