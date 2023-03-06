<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'phone', 'email', 'address', 'order_sum'
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'order_product');
    }

    use HasFactory;
}
