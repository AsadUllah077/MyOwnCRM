<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseLedger extends Model
{
    protected $fillable = [
        'total_amount',
        'pending_amount',
        'paid_amount',
        'purchase_number',
    ];
}
