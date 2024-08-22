<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    dd(getShopifyProduct("gid://shopify/Product/7105260159108"));
});
// Route::get("get-orders", [OrderController::class, 'index']);
