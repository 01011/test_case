<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'phone', 'email', 'address', 'coords', 'order_sum'
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity');
    }

    /**  
     * Преобразует данные для сохранения в методе sync.
     */
    public static function transformDataToSync($data) {
        $products_to_save = array_map(function($element){
            $new_structure = [];
            foreach($element as $key => $val){
                $new_structure[$key] = ['quantity' => $val[0]];
            }
            return $new_structure;
        }, $data);

        return $products_to_save;
    }

    /** 
    * Пересчитывает общую сумму заказа. ID продуктов и количество берется из запроса (поступает на вход), цена из базы.
    * Возвращает общую сумму заказаю 
    */
    public static function calculateOverallPrice($data){
        $products = DB::table('products')->select('id', 'price')->whereIn('id', array_keys($data))->get();
        $order_sum = [];
        foreach ($products as $product){
            $order_sum[] = $data[$product->id]['quantity'] * $product->price;
        }
        return array_sum((array)$order_sum);
    }

    use HasFactory;
}
