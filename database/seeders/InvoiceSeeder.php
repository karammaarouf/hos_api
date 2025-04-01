<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Appointment;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::all();
        
        foreach ($appointments as $appointment) {
            $paymentDate = fake()->boolean(70) ? 
                fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d') : 
                null;

            Invoice::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'amount' => fake()->randomFloat(2, 100, 1000),
                'status' => fake()->randomElement(['pending', 'paid', 'overdue']),
                'description' => 'Medical consultation and services',
                'due_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'payment_date' => $paymentDate,
                'payment_method' => fake()->randomElement(['cash', 'credit_card', 'insurance']),
            ]);
        }
    }
}
