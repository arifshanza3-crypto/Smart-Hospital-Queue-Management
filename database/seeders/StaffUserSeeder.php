<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class StaffUserSeeder extends Seeder
{
    public function run()
    {
        // Delete old staff
        User::where('employee_id', 'EMP001')->delete();

        // Create new staff
        User::create([
            'name' => 'Staff User',
            'full_name' => 'Staff User',
            'email' => 'staff@hospital.com',
            'employee_id' => 'EMP001',
            'password' => bcrypt('password123'),
            'role' => 'staff',
            'status' => 'active',
            'department' => 'Cardiology'
        ]);

        $this->command->info('✅ Staff user created successfully!');
        $this->command->info('Employee ID: EMP001');
        $this->command->info('Password: password123');
    }
}