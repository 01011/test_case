<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ApiController extends Controller
{
    public function sedResponse($result, $message) {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);
        
        $creds = $request->only('email', 'password');
        
        if (Auth::attempt($creds)) {
            $user = Auth::user();
            $success['token'] = $user->createToken('127')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sedResponse($success, 'Удачный вход!');
        } else {
            return $this->sendError('Не авторизован', ['error' => 'Не авторизован'], 400);
        }
    }

    /**
     * Вывод заказов через API.
     */
    public function orders(ApiRequest $request)
    {
        $query = new Order;

        try {
            if(isset($request->order)){
                $query = Order::orderBy($request->order, $request->order_direction ?? 'desc');
            }
            if(isset($request->limit)){
                $orders = $query->paginate($request->limit);
            } else {
                $orders = $query->get();
            }

            return $this->sedResponse($orders, 'Успех!');

        } catch (\Exception $exept) {
            return $this->sendError('Внутренняя ошибка сервера.', '', 500);
        }
    }
}
