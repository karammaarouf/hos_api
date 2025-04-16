<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'blood_type',
        'diagnosis',
        'medications',
        'allergies',
        'medical_history',
        'vital_signs',
        'notes'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}