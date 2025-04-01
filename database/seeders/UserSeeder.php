<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 doctors
        for ($i = 0; $i < 10; $i++) {
            User::firstOrCreate(
                ['email' => 'doctor' . ($i + 1) . '@hospital.com'],
                [
                    'name' => 'Dr. ' . fake()->name(),
                    'password' => Hash::make('12345678'),
                    'phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'role' => 'doctor',
                ]
            );
        }

        // Create 20 patients
        for ($i = 0; $i < 20; $i++) {
            User::firstOrCreate(
                ['email' => 'patient' . ($i + 1) . '@example.com'],
                [
                    'name' => fake()->name(),
                    'password' => Hash::make('12345678'),
                    'phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'role' => 'patient',
                ]
            );
        }

        // Assign departments to doctors after both departments and doctors are created
        $doctors = User::where('role', 'doctor')->get();
        $departments = Department::all();
        
        foreach ($doctors as $doctor) {
            $doctor->update([
                'department_id' => $departments->random()->id
            ]);
        }
    }
}
