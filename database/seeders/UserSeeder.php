<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => 'Admin Address',
            'profile_image' => 'patient.png'
        ]);

        // Create doctors
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Doctor $i",
                'email' => "doctor$i@example.com",
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'birth_date' => date('Y-m-d', strtotime("-" . (30 + $i) . " years")),
                'gender' => $i % 2 == 0 ? 'male' : 'female',
                'phone' => "555555555$i",
                'address' => "Doctor Address $i",
                'profile_image' => 'patient.png',
                'department_id' => $i
            ]);
        }

        // Create patients
        $genders = ['male', 'female'];
        
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Patient $i",
                'email' => "patient$i@example.com",
                'password' => Hash::make('12345678'),
                'role' => 'patient',
                'birth_date' => date('Y-m-d', strtotime("-" . (20 + $i) . " years")),
                'gender' => $genders[array_rand($genders)],
                'phone' => "123456789$i",
                'address' => "Patient Address $i",
                'profile_image' => 'patient.png'
            ]);
        }
    }
}
