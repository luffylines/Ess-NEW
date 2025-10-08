<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'employee_id' => 'admin01',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Test Employee',
            'email' => 'employee@example.com',
            'employee_id' => 'emp01',
            'role' => 'employee',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Test HR',
            'email' => 'hr@example.com',
            'employee_id' => 'hr01',
            'role' => 'hr',
            'password' => bcrypt('password'),
        ]);
    }
}
