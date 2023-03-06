<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_name', 'price'];
    
    public function orders() {
        return $this->belongsToMany(Order::class, 'order_product');
    }

    use HasFactory;
}
