<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Collection;
use App\Models\CollectionProduct;
use App\Models\Log;
use Illuminate\Support\Facades\Log as FacadesLog;

class FetchCollectionProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch products for all collections and store them in the database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $smartCollections = Collection::where("is_product_imported", 0)->where("type", 1)->where("is_smart", 1)->get();
        if (isset($smartCollections) && !empty($smartCollections) && count($smartCollections)) {
            foreach ($smartCollections as $i => $v) {
                saveLog("Marked this collection as imported because of smart collection: ", $v->id, "Collection", 1, []);
                $v->update(['is_product_imported' => 1,]);
            }
        }
        $collections = Collection::where("is_product_imported", 0)->where("type", 1)->where("is_smart", 0)->limit(5)->get();
        if (isset($collections) && !empty($collections) && count($collections)  > 0) {
            foreach ($collections as $collection) {
                $this->info("Fetching products for collection: {$collection->id} - {$collection->title}");
                $collection_id = $collection->id;
                $update = $collection->update(['status' => 1]);
                $collection = getCollection($collection_id);
                $this->fetchProductsForCollection($collection);
                $update = $collection->update(['is_product_imported' => 1]);
            }
        } else {
            saveLog("No New Collection found for product import", null, "Collection", 3, []);
        }
    }

    /**
     * Fetch products for a specific collection and handle pagination.
     *
     * @param \App\Models\Collection $collection
     * @return void
     */
    protected function fetchProductsForCollection(Collection $collection)
    {

        $store = getStoreDetails($collection->import_store_id, "any");

        $client = new Client();
        $accessToken = $store->access_token;
        $endpoint = $store->base_url . $store->api_version . "/graphql.json";

        $cursor = null; // Starting cursor

        do {
            FacadesLog::info("CURSOR:" . $cursor);
            $query = <<<'GRAPHQL'
            query ($collectionId: ID!, $cursor: String) {
              collection(id: $collectionId) {
                title
                products(first: 250, after: $cursor) {
                  edges {
                    node {
                      id
                      title
                      handle
                    }
                  }
                  pageInfo {
                    hasNextPage
                    endCursor
                  }
                }
              }
            }
            GRAPHQL;

            $variables = [
                'collectionId' => $collection->gid,
                'cursor' => $cursor,
            ];

            $response = $client->post($endpoint, [
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                $collectionResponse = $data['data']['collection'];
                $collectionProducts = $data['data']['collection']['products'];

                saveLog(count($collectionProducts['edges']) . " PRODUCTS OF COLLECTION: ", null, null, null, json_encode($collectionProducts));
                if (isset($collectionProducts) && !empty($collectionProducts) && count($collectionProducts)) {
                    foreach ($collectionProducts['edges'] as $i =>  $v) {
                        $product = (object) $v['node'];
                        $checkOld = CollectionProduct::where("product_gid", $product->id)->where("collection_id", $collection->id)->first();
                        if (!$checkOld) {
                            $cpCreate = CollectionProduct::create([
                                "store_id" => $collection->import_store_id ?? null,
                                "collection_id" => $collection->id ?? null,
                                "product_gid" => $product->id ?? null,
                                "title" => $product->title ?? null,
                                "handle" => $product->handle ?? null,
                            ]);
                            if ($cpCreate->id) {
                                $this->info($i . " ✅");
                                saveLog("Collection Product Imported: " . json_encode($product), $cpCreate->id, "CollectionProduct", 1, json_encode($product));
                            } else {
                                saveLog("Something went wrong", null, "CollectionProduct", 2, []);
                            }
                        } else {
                            saveLog("PRODUCT ALREADY EXIST: " . $checkOld->product_gid, $checkOld->id, "CollectionProduct", 3, []);
                        }
                    }
                } else {
                    saveLog("No Product found for this collection: " . $collection->id, $collection->id, "Collection", 3, []);
                }


                $pageInfo = $data['data']['collection']['products']['pageInfo'];
                $cursor = $pageInfo['hasNextPage'] ? $pageInfo['endCursor'] : null;
            } else {
                $this->error('Error fetching collection products for collection' . $collection->id . ': ' . $response->getBody()->getContents());
                break;
            }
        } while ($cursor); // Continue fetching if there is a next page
    }
}
