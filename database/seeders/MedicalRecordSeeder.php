<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    public function run()
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        
        // Get all users
        $patients = User::where('role', 'patient')->get();
        
        foreach ($patients as $patient) {
            MedicalRecord::create([
                'patient_id' => $patient->id,
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'diagnosis' => 'Initial diagnosis for patient ' . $patient->name,
                'medications' => 'No current medications',
                'allergies' => 'No known allergies',
                'medical_history' => 'No significant medical history',
                'vital_signs' => 'BP: 120/80, HR: 72, Temp: 37.0°C',
                'notes' => 'Regular checkup required'
            ]);
        }
    }
}