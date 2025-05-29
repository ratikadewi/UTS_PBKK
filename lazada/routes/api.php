<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrdersItemsController;
use App\Http\Controllers\ProductController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    //Akun
    // Route::controller(UserController::class)->group(function(){
    //     Route::get('/user', 'index');
    //     Route::post('/user/store', 'store');
    //     Route::patch('/user/{id}/update', 'update');
    //     Route::get('/user/{id}','show');
    //     Route::delete('/user/{id}', 'destroy');
    // });
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('categori', CategoriController::class);
    Route::apiResource('produk', ProductController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('orderItems', OrdersItemsController::class);


});




