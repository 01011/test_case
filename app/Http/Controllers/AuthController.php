<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index() {
        if(Auth::check()){
            return redirect()->route('orders.index');
        } else {
            return redirect('login');
        }
    }

    public function login() {
        return view('login');
    }

    public function userLogin(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $creds = $request->only('email', 'password');

        if(Auth::attempt($creds)){
            $request->session()->regenerate();
            return redirect()->route('orders.index');
        } else {
            return back()->with('fail', 'Не правильное имя пользователя или пароль.');
        }
    }

    public function logOut(Request $request) {
        Auth::logout();
        return redirect('/');
    }
}
