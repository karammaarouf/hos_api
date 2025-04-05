<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'description',
        'price',
        'stock_quantity',
        'manufacturer',
        'expiry_date',
        'requires_prescription',
        'status' // active, out-of-stock, discontinued
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'requires_prescription' => 'boolean',
        'expiry_date' => 'date'
    ];
}