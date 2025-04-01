<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'date',
        'time',
        'doctor_id',
        'patient_id',
        'status',
    ];
    
}
