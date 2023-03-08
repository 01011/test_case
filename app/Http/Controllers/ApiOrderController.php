<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Models\Order;

class ApiOrderController extends Controller
{
    /**
     * Вывод заказов через API.
     */
    public function orders(ApiRequest $request)
    {
        $query = new Order;
        $orders = [];

        try {
            if(isset($request->order)){
                $query = Order::orderBy($request->order, $request->order_direction ?? 'desc');
            }
            if(isset($request->limit)){
                $orders = $query->paginate($request->limit);
            } else {
                $orders = $query->get();
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'orders' => $orders,
            ], 200);

        } catch (\Exception $exept) {
             return response()->json([
                'message'       => "Internal server error.",
                'status_code'   => 500,
            ], 500);
        }
    }
}
