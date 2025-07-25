<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('customer', CustomersController::class);
    Route::apiResource('kategori', CategoriesController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('order', OrdersController::class);
    // ----------------------------------------------------------- //
    Route::apiResource('stock', StockController::class);
    Route::get('/order-items', [OrderItemsController::class, 'index']);
    Route::get('/order-items/{orderId}', [OrderItemsController::class, 'show']);
    Route::post('/order-items/{orderId}', [OrderItemsController::class, 'store']);
    Route::put('/order-items/{id}', [OrderItemsController::class, 'update']);
    Route::delete('/order-items/{id}', [OrderItemsController::class, 'destroy']);
    Route::patch('/order/{id}/updateTotal', [OrdersController::class, 'recalculateTotal']);
    //-----------------------------------------------------------------------------------//
    Route::post('/stock/restock-all', [StockController::class, 'restockAll']);

    Route::get('/dashboard-counts', function () {
        return response()->json([
            'customers' => \App\Models\Customers::count(),
            'barangs' => \App\Models\Product::count(),
            'orders' => \App\Models\Orders::count(),
            'total_pendapatan' => \App\Models\Orders::sum('total_amount'),
        ]);
    });

   
});
