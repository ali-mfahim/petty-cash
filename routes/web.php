<?php

use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\OrderController;
use App\Models\Log;
use App\Models\ShopifyOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = getCollectionProductIds(1);

    dd($products);



    // $array = [
    //     "4670",
    //     "5526",
    //     "8427",
    //     "8475",
    //     "9026",
    //     "9227",
    //     "9378",
    //     "9429",
    //     "12003",
    //     "12426",
    //     "12477",
    //     "12628",
    //     "13129",
    //     "13630",
    //     "13631",
    //     "13632",
    //     "13733",
    //     "14092",
    //     "14097",
    //     "14598",
    //     "15099",
    //     "15500",
    //     "16251",
    //     "16252",
    //     "16507",
    //     "16540",
    //     "17291",
    //     "17642",
    //     "18393",
    //     "20344",
    //     "20994",
    //     "22345",
    //     "22696",
    //     "22847",
    //     "23848",
    //     "24099",
    //     "24150",
    //     "24151",
    //     "24602",
    //     "24603",
    //     "24954",
    //     "25455",
    //     "28856",
    //     "28957",
    //     "31407",
    //     "31652",
    //     "31658",
    //     "32558",
    //     "32708",
    //     "32959",
    //     "33110",
    //     "33411",
    //     "33812",
    //     "34363",
    //     "35164",
    //     "35415",
    //     "36166",
    //     "37517",
    //     "38368",
    //     "40818",
    //     "42017",
    //     "42368",
    //     "42369",
    //     "42884",
    //     "43285",
    //     "44636",
    //     "45637",
    //     "45888",
    //     "46589",
    //     "46590",
    //     "47641",
    //     "48062",
    //     "49013",
    //     "49042",
    //     "50293",
    //     "52044",
    //     "52845",
    //     "53696",
    //     "53747",
    //     "54098",
    //     "54749",
    //     "55650",
    //     "55801",
    //     "55852",
    //     "55953"
    //     ];
    // $orders = ShopifyOrder::whereIn("id" , $array)->get();
    // $logs = Log::whereIn("model_id" , $array)->where("status" , 2)->limit(10)->get(["id" , "description"]);
    // dd($logs);
    return redirect()->route("dashboard.index")->with("success", "Welcome to Dashboard");
    return view('welcome');
});

Route::get("collection-products/{collection_id}/{cursor?}", [CollectionController::class, 'collectionProducts'])->name("collections.collectionProducts");



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
