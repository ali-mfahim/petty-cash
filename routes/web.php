<?php

use App\Http\Controllers\OrderController;
use App\Models\ShopifyOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    $order = ShopifyOrder::where("id", 41)->first();
    dd(json_decode($order->tags));
    dd(getShopifyProduct("gid://shopify/Product/7105260159108"));
});
// Route::get("get-orders", [OrderController::class, 'index']);
