<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Cardiology Consultation',
                'description' => 'Complete heart checkup including ECG, echocardiogram, and specialist consultation for heart-related issues.',
                'department' => 'Cardiology',
                'price' => 150.00,
                'duration' => 45,
                'icon' => 'fas fa-heartbeat',
                'status' => 'active',
                'display_order' => 1
            ],
            [
                'name' => 'Neurology Consultation',
                'description' => 'Expert consultation for neurological disorders including migraines, epilepsy, and memory issues.',
                'department' => 'Neurology',
                'price' => 180.00,
                'duration' => 60,
                'icon' => 'fas fa-brain',
                'status' => 'active',
                'display_order' => 2
            ],
            [
                'name' => 'General Physician',
                'description' => 'General health checkup and consultation for common illnesses and routine medical care.',
                'department' => 'General Medicine',
                'price' => 80.00,
                'duration' => 30,
                'icon' => 'fas fa-user-md',
                'status' => 'active',
                'display_order' => 3
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Specialized healthcare for children from birth to adolescence including vaccinations.',
                'department' => 'Pediatrics',
                'price' => 100.00,
                'duration' => 45,
                'icon' => 'fas fa-child',
                'status' => 'active',
                'display_order' => 4
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Treatment for bone, joint, and muscle problems including fractures and arthritis.',
                'department' => 'Orthopedics',
                'price' => 120.00,
                'duration' => 45,
                'icon' => 'fas fa-bone',
                'status' => 'active',
                'display_order' => 5
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, and nail treatments including acne, eczema, and cosmetic procedures.',
                'department' => 'Dermatology',
                'price' => 90.00,
                'duration' => 30,
                'icon' => 'fas fa-allergies',
                'status' => 'inactive',
                'display_order' => 6
            ],
            [
                'name' => 'Dental Care',
                'description' => 'Complete dental checkup, cleaning, filling, and other dental procedures.',
                'department' => 'Dentistry',
                'price' => 85.00,
                'duration' => 40,
                'icon' => 'fas fa-tooth',
                'status' => 'active',
                'display_order' => 7
            ],
            [
                'name' => 'Eye Care (Ophthalmology)',
                'description' => 'Eye examinations, vision tests, and treatment for eye diseases like cataracts.',
                'department' => 'Ophthalmology',
                'price' => 95.00,
                'duration' => 35,
                'icon' => 'fas fa-eye',
                'status' => 'active',
                'display_order' => 8
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => Str::slug($service['name'])],
                [
                    'name' => $service['name'],
                    'description' => $service['description'],
                    'department' => $service['department'],
                    'price' => $service['price'],
                    'duration' => $service['duration'],
                    'icon' => $service['icon'],
                    'status' => $service['status'],
                    'display_order' => $service['display_order']
                ]
            );
        }
        
        $this->command->info('Services seeded successfully!');
    }
}