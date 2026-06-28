<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Illuminate\Support\Str;

class DoctorsTableSeeder extends Seeder
{
    public function run()
    {
        $doctors = [
            [
                'name' => 'Dr. John Smith',
                'specialization' => 'Cardiologist',
                'email' => 'john.smith@hospital.com',
                'phone' => '+1234567890',
                'fee' => 150.00,
                'availability' => 'Mon-Fri 9AM-5PM',
                'status' => 'active'
            ],
            [
                'name' => 'Dr. Sarah Johnson',
                'specialization' => 'Neurologist',
                'email' => 'sarah.johnson@hospital.com',
                'phone' => '+1234567891',
                'fee' => 180.00,
                'availability' => 'Mon-Wed 10AM-6PM',
                'status' => 'active'
            ],
            [
                'name' => 'Dr. Michael Brown',
                'specialization' => 'Pediatrician',
                'email' => 'michael.brown@hospital.com',
                'phone' => '+1234567892',
                'fee' => 120.00,
                'availability' => 'Mon-Fri 8AM-4PM',
                'status' => 'active'
            ],
            [
                'name' => 'Dr. Emily Davis',
                'specialization' => 'Dermatologist',
                'email' => 'emily.davis@hospital.com',
                'phone' => '+1234567893',
                'fee' => 130.00,
                'availability' => 'Tue-Sat 9AM-5PM',
                'status' => 'inactive'
            ],
            [
                'name' => 'Dr. Robert Wilson',
                'specialization' => 'Orthopedic',
                'email' => 'robert.wilson@hospital.com',
                'phone' => '+1234567894',
                'fee' => 140.00,
                'availability' => 'Mon-Fri 9AM-6PM',
                'status' => 'active'
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::updateOrCreate(
                ['email' => $doctor['email']],
                [
                    'name' => $doctor['name'],
                    'specialization' => $doctor['specialization'],
                    'phone' => $doctor['phone'],
                    'fee' => $doctor['fee'],
                    'availability' => $doctor['availability'],
                    'status' => $doctor['status'],
                    'slug' => Str::slug($doctor['name'])
                ]
            );
        }
        
        $this->command->info('Doctors seeded successfully!');
    }
}