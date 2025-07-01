<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{


     protected $fillable = [
        'customer_id',
        'sale_date',
        'sale_number',
        'total_amount',
        'paid_amount',
        'payment_status',
        'notes',
        'discount',
        'created_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function saleDetails()
    {
        return $this->hasMany(saleDetail::class);
    }
}
