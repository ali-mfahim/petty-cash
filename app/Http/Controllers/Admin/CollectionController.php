<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionProduct;
use App\Models\Store;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function getCollections($store_id, $export_store_id,  $cursor = null)
    {

        try {
            $client = new Client();
            $store = getStoreDetails($store_id);

            $accessToken = $store->access_token;
            $url = $store->base_url . $store->api_version . "/graphql.json";
            $query = '
            {
                collections(first: 250' . ($cursor ? ', after: "' . $cursor . '"' : '') . ') {
                    edges {
                        node {
                            id
                            title
                            handle
                            description
                            image {
                                src
                                altText
                            }
                            updatedAt
                            sortOrder
                        }
                    }
                    pageInfo {
                        hasNextPage
                        endCursor
                    }
                }
            }';

            $response = $client->post($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'query' => $query,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            $collections = $body['data']['collections']['edges'] ?? [];

            // Check if there are more collections to fetch
            $pageInfo = $body['data']['collections']['pageInfo'] ?? [];
            $hasNextPage = $pageInfo['hasNextPage'] ?? false;

            $endCursor = $pageInfo['endCursor'] ?? null;
            foreach ($collections as  $value) {
                $collection = (object) $value['node'] ?? null;
                if (isset($collection) && !empty($collection)) {
                    $checkOld  = Collection::where("gid", $collection->id)->first();
                    if (!isset($checkOld) || empty($checkOld)) {
                        $import = Collection::create([
                            "import_store_id" =>  $store->store_id ?? null,
                            "export_store_id" =>  $export_store_id ?? null,
                            "gid" => $collection->id ?? null,
                            "handle" => $collection->handle ?? null,
                            "title" => $collection->title ?? null,
                            "description" => isset($collection->description) && !empty($collection->description) ? json_encode($collection->description)  : null,
                            "type" => "1", // importing new collections from store
                            "image" => isset($collection->image) && !empty($collection->image) ? json_encode($collection->image) : null,
                            "c_updated_at" => $collection->updatedAt ?? null,
                            "sort_order" => $collection->sortOrder ?? null,
                            "raw_data" => json_encode($collection),
                            "status" => 0,
                        ]);
                        if ($import->id) {
                            saveLog("A new collection imported from store " . $store->domain, $import->id, "Collection", 1, []);
                        } else {
                            saveLog("Something went wrong while fetching new collection from store " . $store->domain, null, "Collection", 2, []);
                        }
                    } else {
                        saveLog("Collection you tried to import is already exist with title ." . $checkOld->title . " from store " . $store->domain, $checkOld->id, "Collection", 2, []);
                    }
                }
            }
            if ($hasNextPage == true && $endCursor != "") {
                return redirect()->route("collections.getCollections", [$store_id, $export_store_id, $endCursor]);
                return [
                    'hasNextPage' => $hasNextPage,
                    'endCursor' => $endCursor
                ];
            }
            return "success";

            return [
                'collections_total' => count($collections),
                'collections' => $collections,
                'hasNextPage' => $hasNextPage,
                'endCursor' => $endCursor
            ];
        } catch (Exception $e) {
            saveLog("ERROR WHILE IMPORTING NEW COLLECTION: " . $e->getMessage() . $store->domain, null, "Collection", 2, []);
        }
    }
    public function collectionProducts(Request $request, $collection_id, $cursor = null)
    {
        if (!isset($request->page) || empty($request->page)) {
            $page = 1;
        } else {
            $page = $request->page;
        }
        $collection = Collection::where("id", $collection_id)->first();
        $store = getStoreDetails($collection->import_store_id, "any");
        $client = new Client();
        $accessToken = $store->access_token;
        $endpoint = $store->base_url . $store->api_version . "/graphql.json";
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
            $data =  json_decode($response->getBody()->getContents(), true);
            $collectionResponse = $data['data']['collection'];
            $collectionProducts = $data['data']['collection']['products'];

            saveLog(count($collectionProducts['edges']) . " PRODUCTS OF COLLECTION: ", null, null, null, json_encode($collectionProducts));
            foreach ($collectionProducts['edges'] as $i => $v) {
                $product = (object) $v['node'];
                $checkOld = CollectionProduct::where("product_gid", $product->id)->first();
                if (!isset($checkOld) ||  empty($checkOld)) {

                    $cpCreate = CollectionProduct::create([
                        "store_id" => $collection->import_store_id ?? null,
                        "collection_id" => $collection->id ?? null,
                        "product_gid" => $product->id ?? null,
                        "title" => $product->title ?? null,
                        "handle" => $product->handle ?? null,
                    ]);
                    if ($cpCreate->id) {
                        saveLog("Collection Product Imported: " . json_encode($product), $cpCreate->id, "CollectionProduct", 1, json_encode($product));
                    } else {
                        saveLog("Something went wrong", null, "CollectionProduct", 2, []);
                    }
                } else {
                    saveLog("PRODUCT ALREADY EXIST: " . $checkOld->product_gid, $checkOld->id, "CollectionProduct", 3, []);
                }
            }
            $pageInfo = $data['data']['collection']['products']['pageInfo'];
            if ($pageInfo['hasNextPage'] == true && $pageInfo['endCursor'] != "") {
                $page = $page + 1;
                return redirect()->route("collections.collectionProducts", [
                    $collection_id,
                    $pageInfo['endCursor']
                ]);
            } else {
                return "success";
                // return jsonResponse(true, $page . " data has been fetched", "SUCCESS", 200);
            }
        } else {
            throw new \Exception('Error fetching collection products: ' . $response->getBody()->getContents());
        }
    }


    public function fetchProductByHandle($handle, $store)
    {
        $accessToken = $store->access_token;
        $endpoint = $store->base_url . $store->api_version . "/graphql.json";

        // GraphQL query
        $query = <<<'GRAPHQL'
        query ($handle: String!) {
          productByHandle(handle: $handle) {
            id
            title
            handle
            descriptionHtml
            images(first: 5) {
              edges {
                node {
                  src
                  altText
                }
              }
            }
          }
        }
        GRAPHQL;

        // Variables for the query
        $variables = [
            'handle' => $handle,
        ];

        // Create Guzzle client
        $client = new Client();

        // Make the HTTP request using Guzzle
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

        // Check if the response was successful (status code 200)
        if ($response->getStatusCode() === 200) {
            // Return the response body as a JSON array
            return json_decode($response->getBody()->getContents(), true);
        } else {
            // Handle errors (non-200 status code)
            throw new \Exception('Error fetching product by handle: ' . $response->getBody()->getContents());
        }
    }

    public function importCollectionModalContent(Request $request)
    {
        $data['stores'] = Store::all();
        $data['store'] = Store::where("id", $request->store_id)->first();
        $data['view'] = view("admin.pages.stores.components.importCollectionModalContent", $data)->render();
        return jsonResponse(true, $data, "Content", 200);
    }
}
