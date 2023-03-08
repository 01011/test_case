<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/user-login', [AuthController::class, 'userLogin'])->name('user-login');
Route::get('/log-out', [AuthController::class, 'logOut'])->name('log-out');

Route::group(['middleware' => 'auth'], function(){
    Route::resources([
        'products' => ProductController::class,
        'orders' => OrderController::class,
    ]);
});
