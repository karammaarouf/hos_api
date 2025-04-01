<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'location',
        'head_of_department',
        'capacity',
        'is_active'

    ];
    public function users(){
        return $this->hasMany(User::class);
    }
}
