<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    protected $fillable =['purchase_id','product_id','category_id','price','qty'];

    
}
