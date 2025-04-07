<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the profile images directory exists
        $profilesPath = storage_path('app/public/profiles');
        if (!File::exists($profilesPath)) {
            File::makeDirectory($profilesPath, 0755, true);
        }

        // Define doctor images array
        $doctorImages = [];
        for ($i = 1; $i <= 9; $i++) {
            $doctorImages[] = 'doc' . $i . '.png';
        }

        // Copy doctor images to profiles directory
        foreach ($doctorImages as $image) {
            $sourcePath = public_path('profiles/' . $image);
            $destinationPath = storage_path('app/public/profiles/' . $image);
            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $destinationPath);
            }
        }

        // Create 10 doctors with random images
        for ($i = 0; $i < 10; $i++) {
            $doctor = User::firstOrCreate(
                ['email' => 'doctor' . ($i + 1) . '@hospital.com'],
                [
                    'name' => 'Dr. ' . fake()->name(),
                    'password' => Hash::make('12345678'),
                    'phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'role' => 'doctor',
                    'profile_image' => 'profiles/' . $doctorImages[array_rand($doctorImages)]
                ]
            );
        }

        // Create 20 patients with default image
        for ($i = 0; $i < 20; $i++) {
            $patient = User::firstOrCreate(
                ['email' => 'patient' . ($i + 1) . '@example.com'],
                [
                    'name' => fake()->name(),
                    'password' => Hash::make('12345678'),
                    'phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'role' => 'patient',
                    'profile_image' => 'profiles/patient.png'
                ]
            );
        }

        // Assign departments to doctors
        $doctors = User::where('role', 'doctor')->get();
        $departments = Department::all();
        
        foreach ($doctors as $doctor) {
            $doctor->update([
                'department_id' => $departments->random()->id
            ]);
        }
    }
}
