<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\CollectionProduct;
use Exception;
use Illuminate\Console\Command;

class MatchProductsBetweenStores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will match products from import_store_id to export_store_id so that we can have gid of second store and we can link the newly collection created';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $products = CollectionProduct::whereHas("collection", function ($query) {
            $query->where("type", 1);
        })->where("matched_store", 0)->limit(10)->get();
        foreach ($products as $i => $v) {
            try {
                if (isset($v->collection->export_store_id) && !empty($v->collection->export_store_id)) {
                    if (isset($v->collection->handle) && !empty($v->collection->handle)) {
                        $check = getShopifyProductByHandle($v);
                        if (isset($check->success) && !empty($check->success)) {
                            if ($check->success == true && !empty($check->data)) {
                                $productData =  (object) $check->data['data']['productByHandle'];
                                if (isset($productData) && !empty($productData)) {
                                    $updateProduct = $v->update([
                                        'new_gid' => $productData->id ?? null,
                                        'new_product_title' => $productData->title ?? null,
                                        'new_product_handle' => $productData->handle ?? null,
                                        'new_product_raw_data' => json_encode($productData),
                                        'matched_store' => 1,
                                    ]);
                                    if ($updateProduct > 0) {
                                        $this->info("1-done");
                                        saveLog("A product match found. ", $v->id, "CollectionProduct", 1, []);
                                    } else {
                                        $v->update([
                                            'matched_store' => 3,
                                        ]);
                                        saveLog("Something went wrong while updating new product. ", $v->id, "CollectionProduct", 2, []);
                                    }
                                } else {
                                    $v->update([
                                        'matched_store' => 3,
                                    ]);
                                    saveLog("Product not found", $v->id, "CollectionProduct", 2, []);
                                }
                            } else {
                                $v->update([
                                    'matched_store' => 3,
                                ]);
                                saveLog("Graphql API error of getting product from handle", $v->id, "CollectionProduct", 2, []);
                            }
                        } else {
                            $v->update([
                                'matched_store' => 3,
                            ]);
                            saveLog("Graphql API error of getting product from handle", $v->id, "CollectionProduct", 2, []);
                        }
                    } else {
                        $v->update([
                            'matched_store' => 3,
                        ]);
                        $this->info("HANDLE NOT FOUND ");
                    }
                } else {
                    $v->update([
                        'matched_store' => 3,
                    ]);
                    $this->info("EXPORT STORE ID NOT FOUND ");
                }
            } catch (Exception $e) {
                saveLog("Error While getting product matched" . $e->getMessage(), $v->id, "CollectionProduct", 2, []);
            }
        }
    }
}
