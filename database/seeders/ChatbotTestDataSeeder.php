<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payslip;
use App\Models\WorkSchedule;
use App\Models\Holiday;
use App\Models\Payroll;
use App\Models\Store;
use Carbon\Carbon;

class ChatbotTestDataSeeder extends Seeder
{
    public function run()
    {
        // Get the employee (assuming employee2 or the last created user)
        $user = User::where('email', 'like', 'employee%')->first() ?? User::latest()->first();

        if (!$user) {
            $this->command->error('No user found to attach data to!');
            return;
        }

        $this->command->info("Seeding data for user: {$user->name} ({$user->email})");

        // 1. Create a Store (if not exists)
        $store = Store::firstOrCreate(
            ['name' => 'Main Branch'],
            [
                'lat' => 14.5995,
                'lng' => 120.9842
            ]
        );

        // 2. Create a Payroll record (Parent of Payslip)
        $payroll = Payroll::updateOrCreate(
            [
                'user_id' => $user->id,
                'pay_period_year' => Carbon::now()->year,
                'pay_period_month' => Carbon::now()->month,
            ],
            [
                'pay_period_start' => Carbon::now()->startOfMonth(),
                'pay_period_end' => Carbon::now()->endOfMonth(),
                'basic_pay' => 20000,
                'total_overtime_pay' => 1500,
                'gross_pay' => 21500,
                'total_deductions' => 1500,
                'net_pay' => 20000,
                'status' => 'paid'
            ]
        );

        // 3. Create a Payslip
        Payslip::updateOrCreate(
            [
                'payslip_number' => 'PS-' . Carbon::now()->format('Ym') . '-001',
            ],
            [
                'payroll_id' => $payroll->id,
                'user_id' => $user->id,
                'pay_period_year' => Carbon::now()->year,
                'pay_period_month' => Carbon::now()->month,
                'pay_period_start' => Carbon::now()->startOfMonth(),
                'pay_period_end' => Carbon::now()->endOfMonth(),
                'generated_date' => Carbon::now(), // Today
                'employee_name' => $user->name,
                'employee_id' => $user->employee_id ?? 'EMP001',
                'basic_pay' => 20000.00,
                'total_overtime_pay' => 1500.00,
                'gross_pay' => 21500.00,
                'total_deductions' => 1500.00,
                'net_pay' => 20000.00,
                'status' => 'generated',
                'is_downloaded' => false
            ]
        );

        // Get an admin user for 'assigned_by'
        $admin = User::where('role', 'admin')->first() ?? $user;

        // 4. Create a Work Schedule (Tomorrow)
        WorkSchedule::updateOrCreate(
            [
                'employee_id' => $user->id,
                'schedule_date' => Carbon::tomorrow()->format('Y-m-d'),
            ],
            [
                'store_id' => $store->id,
                'assigned_by' => $admin->id,
                'shift_start' => Carbon::tomorrow()->setTime(8, 0), // 8:00 AM
                'shift_end' => Carbon::tomorrow()->setTime(17, 0),   // 5:00 PM
                'shift_type' => 'regular',
                'status' => 'assigned'
            ]
        );
        
        // 5. Create a Work Schedule (Today)
        WorkSchedule::updateOrCreate(
            [
                'employee_id' => $user->id,
                'schedule_date' => Carbon::today()->format('Y-m-d'),
            ],
            [
                'store_id' => $store->id,
                'assigned_by' => $admin->id,
                'shift_start' => Carbon::today()->setTime(9, 0), // 9:00 AM
                'shift_end' => Carbon::today()->setTime(18, 0),   // 6:00 PM
                'shift_type' => 'regular',
                'status' => 'assigned'
            ]
        );

        // 6. Create a Holiday (Next Week)
        Holiday::updateOrCreate(
            [
                'date' => Carbon::today()->addDays(7)->format('Y-m-d'),
            ],
            [
                'name' => 'Company Anniversary',
                'type' => 'regular',
                'is_active' => true,
                'country' => 'PH'
            ]
        );

        $this->command->info('✅ Test data created successfully!');
    }
}
