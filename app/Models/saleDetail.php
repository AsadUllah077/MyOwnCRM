<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class saleDetail extends Model
{
        protected $fillable =['sale_id','product_id','category_id','price','qty'];
}
