<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)->group(function () {
    Route::get('/pizzas', 'index');
    Route::get('/ingredients', 'ingredients');
    Route::post('/orders', 'createOrder');
});
