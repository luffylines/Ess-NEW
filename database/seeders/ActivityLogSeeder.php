<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            // Create a sample user if none exist
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
            $users = collect([$user]);
        }

        $actionTypes = ['login', 'logout', 'create', 'update', 'delete', 'view', 'export'];
        $descriptions = [
            'login' => 'Logged In to the system',
            'logout' => 'Logged out from the system',
            'create' => 'Created a new record',
            'update' => 'Updated existing record',
            'delete' => 'Deleted a record',
            'view' => 'Viewed system information',
            'export' => 'Exported data to PDF/CSV',
        ];

        $ipAddresses = ['127.0.0.1', '192.168.1.100', '10.0.0.15', '172.16.0.50'];
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        ];

        // Create sample activity logs
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $actionType = $actionTypes[array_rand($actionTypes)];
            $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            ActivityLog::create([
                'user_id' => $user->id,
                'action_type' => $actionType,
                'description' => $descriptions[$actionType],
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'properties' => $this->generateProperties($actionType),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Add a recent login entry
        ActivityLog::create([
            'user_id' => $users->first()->id,
            'action_type' => 'login',
            'description' => 'Logged In to the system',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'properties' => [
                'login_method' => 'email',
                'remember_me' => false,
            ],
            'created_at' => Carbon::now()->subMinutes(5),
            'updated_at' => Carbon::now()->subMinutes(5),
        ]);
    }

    private function generateProperties($actionType)
    {
        switch ($actionType) {
            case 'login':
                return [
                    'login_method' => rand(0, 1) ? 'email' : 'google',
                    'remember_me' => rand(0, 1) ? true : false,
                ];
            case 'export':
                return [
                    'format' => rand(0, 1) ? 'pdf' : 'csv',
                    'records_count' => rand(1, 100),
                ];
            case 'create':
            case 'update':
            case 'delete':
                return [
                    'model' => ['User', 'Attendance', 'LeaveRequest', 'OvertimeRequest'][rand(0, 3)],
                    'record_id' => rand(1, 50),
                ];
            default:
                return [];
        }
    }
}
