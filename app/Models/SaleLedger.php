<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleLedger extends Model
{
     protected $fillable = [
        'total_amount',
        'pending_amount',
        'paid_amount',
        'sale_number',
    ];
}
