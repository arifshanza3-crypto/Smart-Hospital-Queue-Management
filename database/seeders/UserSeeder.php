<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Delete old user
        User::where('email', 'user@hospital.com')->delete();

        // Create new user
        User::create([
            'name' => 'Normal User',
            'full_name' => 'Normal User',
            'email' => 'user@hospital.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'status' => 'active'
        ]);

        $this->command->info('✅ User created successfully!');
        $this->command->info('Email: user@hospital.com');
        $this->command->info('Password: password123');
    }
}