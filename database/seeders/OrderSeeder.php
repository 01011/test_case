<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Order;
use \App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(1000)->create();
        Order::factory()->count(1000)->create();

        $products = Product::all();
        $records = [];

        Order::all()->each(function($order) use ($products){
            $records = $products->random(rand(1, 5))->pluck('id')->toArray();
            foreach($records as $key => $val) {
                unset($records[$key]);
                $records[$val] = ['quantity' => rand(1, 5)];
            }

            $order->products()->attach($records);

            $order->order_sum = Order::calculateOverallPrice($records);

            if($order->order_sum < 3000){
                $records = $products->random(rand(3, 5))->pluck('id')->toArray();
                foreach($records as $key => $val) {
                    unset($records[$key]);
                    $records[$val] = ['quantity' => rand(3, 5)];
                }

                $order->products()->attach($records);
                $order->order_sum = Order::calculateOverallPrice($records);
            }

            $order->save();
        });
    }
}
