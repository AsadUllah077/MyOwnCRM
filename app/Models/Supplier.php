<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
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

    public function purchase(){
        $this->belongsTo(Purchase::class);
    }

}
