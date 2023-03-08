<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::get();
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::get()->keyBy('id');
        return view('order.form', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->safe()->only('phone', 'email', 'address', 'coords');
        $selected_products = $request->safe()->only('selected_products');

        $products_to_save = Order::transformDataToSync($selected_products);
        
        $order = new Order($validated);        
        
        // Собираем сумму заказа заново, не доверяем пользовательскому вводу
        $order->order_sum = Order::calculateOverallPrice($products_to_save['selected_products']);

        $order->save();

        $order->products()->sync($products_to_save['selected_products']);

        $request->session()->flash('message', 'Заказ №' . $order->id . ' успешно создан.');
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $products = Product::get()->keyBy('id');
        return view('order.form', compact('products', 'order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrderRequest $request, Order $order)
    {
        $validated = $request->safe()->only('phone', 'email', 'address', 'coords');
        $selected_products = $request->safe()->only('selected_products');

        $products_to_save = Order::transformDataToSync($selected_products);

        // Собираем сумму заказа заново, не доверяем пользовательскому вводу
        $validated['order_sum'] =  Order::calculateOverallPrice($products_to_save['selected_products']);;
        
        $order->update($validated);

        $order->products()->sync($products_to_save['selected_products']);

        $request->session()->flash('message', 'Заказ №' . $order->id . ' успешно изменен.');
        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order_id = $order->id;
        $order->delete();
        return redirect()->route('orders.index')->with('message', 'Заказ №' . $order_id . ' успешно удален.');
    }
}
