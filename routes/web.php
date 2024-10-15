<?php

use App\Http\Controllers\OrderController;
use App\Models\ShopifyOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route("dashboard.index")->with("success", "Welcome to Dashboard");
    return view('welcome');
});




Route::get('test', function () {
    return getLogos()->logo_white;
    $sizes = getDefaultSizes();
    if (in_array("M", $sizes)) {
        return "Xl";
    } else {
        return "not mactched";
    }
    $order = ShopifyOrder::where("id", 41)->first();
    dd(json_decode($order->tags));
    dd(getShopifyProduct("gid://shopify/Product/7105260159108"));
});
// Route::get("get-orders", [OrderController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get("storeData", function () {
    return getStoreDetails();
});



Route::get("stats", function () {
    // 0 => Pending , 1 = In Process , 2 = Process Completed , 3 = Skipped	
    $pending = ShopifyOrder::where("status", 0)->count();
    //  $pendingRecords = ShopifyOrder::where("status" , 0)->pluck("shopify_order_id")->toArray();
    $inProcess = ShopifyOrder::where("status", 1)->count();
    //  $inProcessRecords = ShopifyOrder::where("status" , 1)->pluck("shopify_order_id")->toArray();
    $completed = ShopifyOrder::where("status", 2)->count();
    //  $completedRecords = ShopifyOrder::where("status" , 2)->pluck("shopify_order_id")->toArray();
    $skipped = ShopifyOrder::where("status", 3)->count();
    //  $skippedRecords = ShopifyOrder::where("status" , 3)->pluck("shopify_order_id")->toArray();
    return [

        "pending" =>  [
            "count" => $pending ?? "N/A",
            // "orderIds" => $pendingRecords ?? "N/A",
        ],
        "inProcess" =>  [
            "count" => $inProcess ?? "N/A",
            // "orderIds" => $inProcessRecords ?? "N/A",
        ],
        "completed" =>  [
            "count" => $completed ?? "N/A",
            // "orderIds" => $completedRecords ?? "N/A",
        ],
        "skipped" =>  [
            "count" => $skipped ?? "N/A",
            // "orderIds" => $skippedRecords ?? "N/A",
        ],


    ];
});
