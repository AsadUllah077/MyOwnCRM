<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     protected $fillable=[
        'name',
        'email',
        'number',
        'address'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }

     public function categories(){
        return $this->hasMany(Category::class);
    }

    public function sales(){
        $this->hasMany(Sale::class);
    }
}
