<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateStaffUser extends Command
{
    protected $signature = 'staff:create';
    protected $description = 'Create a staff user';

    public function handle()
    {
        $name = $this->ask('Enter staff name', 'Staff User');
        $email = $this->ask('Enter staff email', 'staff@hospital.com');
        $employeeId = $this->ask('Enter employee ID', 'EMP001');
        $password = $this->secret('Enter password', 'password123');

        $user = User::create([
            'name' => $name,
            'full_name' => $name,
            'email' => $email,
            'employee_id' => $employeeId,
            'password' => bcrypt($password),
            'role' => 'staff',
            'status' => 'active',
            'department' => 'Cardiology'
        ]);

        $this->info('✅ Staff user created successfully!');
        $this->info('Name: ' . $user->name);
        $this->info('Employee ID: ' . $user->employee_id);
        $this->info('Email: ' . $user->email);
        $this->info('Password: ' . $password);
    }
}