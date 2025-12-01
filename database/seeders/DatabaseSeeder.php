<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\AllowedNetwork;
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
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'employee_id' => 'admin01',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
    
        User::factory()->create([
            'name' => 'Christian Aring',
            'email' => 'christianaring6@gmail.com',
            'employee_id' => 'emp01',
            'role' => 'employee',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'HR',
            'email' => 'luffylines@example.com',
            'employee_id' => 'hr01',
            'role' => 'hr',
            'password' => bcrypt('password'),
        ]);

        // Create default store with your coordinates
        Store::create([
            'name' => 'Main Store',
            'lat' => 14.652848,
            'lng' => 121.045295,
            'radius_meters' => 50,
            'active' => true,
        ]);

        // Create allowed network with your IP
        AllowedNetwork::create([
            'name' => 'Office Network',
            'ip_ranges' => ['136.158.37.82'],
            'active' => true,
        ]);
    }
}
