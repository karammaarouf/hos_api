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
        'department_id',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id')->withDefault([
            'name' => 'Deleted Patient',
            'email' => null
        ]);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id')->withDefault([
            'name' => 'Deleted Department'
        ]);
    }
}
