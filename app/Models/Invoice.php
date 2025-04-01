<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'amount',
        'status',
        'due_date',
        'payment_date',
        'payment_method',
        
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
