<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Cardiology', 'code' => 'CARD', 'description' => 'Heart and cardiovascular care'],
            ['name' => 'Neurology', 'code' => 'NEUR', 'description' => 'Brain and nervous system'],
            ['name' => 'Orthopedics', 'code' => 'ORTH', 'description' => 'Bone and joint care'],
            ['name' => 'Pediatrics', 'code' => 'PEDI', 'description' => 'Child healthcare'],
            ['name' => 'Dermatology', 'code' => 'DERM', 'description' => 'Skin care'],
            ['name' => 'Ophthalmology', 'code' => 'OPTH', 'description' => 'Eye care'],
            ['name' => 'Dentistry', 'code' => 'DENT', 'description' => 'Dental care'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['code' => $dept['code']],
                [
                    'name' => $dept['name'],
                    'description' => $dept['description'],
                    'location' => 'Floor ' . rand(1, 5),
                    'head_of_department' => 'Dr. ' . fake()->name(),
                    'capacity' => rand(20, 50),
                    'is_active' => true,
                ]
            );
        }
    }
}
