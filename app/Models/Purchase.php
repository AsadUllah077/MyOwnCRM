<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'purchase_number',
        'total_amount',
        'paid_amount',
        'payment_status',
        'notes',
        'discount',
        'created_by',
    ];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetails::class);
    }
}
