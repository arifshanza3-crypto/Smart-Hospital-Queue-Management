<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Run seeders in correct order
        $this->call(ServicesTableSeeder::class);
        // $this->call(DoctorsTableSeeder::class); // Comment out if doctor table doesn't exist
    }
}