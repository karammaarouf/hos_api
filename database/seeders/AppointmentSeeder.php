<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->get();
        $patients = User::where('role', 'patient')->get();
        
        // Create 20 appointments
        for ($i = 0; $i < 20; $i++) {
            $date = fake()->dateTimeBetween('now', '+2 months');
            
            Appointment::create([
                'date' => $date->format('Y-m-d'),
                'time' => fake()->time('H:i'),
                'doctor_id' => $doctors->random()->id,
                'patient_id' => $patients->random()->id,
                'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            ]);
        }
    }
}
