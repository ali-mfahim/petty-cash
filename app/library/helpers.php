<?php

use App\Models\Log as ModelsLog;
use App\Models\ShopifyOrder;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


function jsonResponse($success = null, $data = null, $message = null, $code = null)
{
    return (object) ['success' => $success, 'data' => $data ?? null, 'message' => $message];
}

function eliminateGid($id)
{
    $lastValue = Str::afterLast($id, '/');
    return $lastValue;
}


function getStoreDetails()
{
    return (object) [
        "base_url" => config("project.shopify.base_url"),
        "domain" => config("project.shopify.domain"),
        "access_token" => config("project.shopify.access_token"),
        "app_key" => config("project.shopify.app_key"),
        "app_secret" => config("project.shopify.app_secret"),
        "api_version" => config("project.shopify.app_version"),
        "store_currency" => config("project.shopify.store_currency"),
        "meta_namespace" => config("project.shopify.meta.namespace"),
        "meta_key" => config("project.shopify.meta.key"),
    ];
}

function getShopifyProduct($product_id = null)
{
    try {
        $project = getStoreDetails();
        $url = $project->base_url . $project->api_version . "/graphql.json";
        $query = 'query GetProductById($id: ID!) {
            product(id: $id) {
              id
              title
              description
              variants(first: 100) {
                edges {
                  node {
                    id
                    price
                    inventoryQuantity 
                    sku
                    image {
                      id
                    }
                    inventoryItem {
                        id
                    }
                  }
                }
              }
            }
          }';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $project->access_token,
        ])->post($url, [
            'query' => $query,
            'variables' => [
                'id' => $product_id, // gid of product
            ],
        ]);
        $response = $response->json();
        dd($response);
        if (isset($response['data']['product']) && !empty($response['data']['product'])) {
            $product =  (object) $response['data']['product'];
        }
        if (isset($product) && !empty($product) && $product->id) {
            return jsonResponse(true, $product, "Product with ID " . $product->id . " exist on shopify", 200);
        } else {
            return jsonResponse(false,  null, "Something went wrong while fetching existing product with ID " . $product_id . " from shopify", 200);
        }
    } catch (Exception $exist_product) {
        $array["model_id"] = $product_id;
        $array["model_name"] = "SHOPIFY";
        $array["message"] = "ERROR WHILE CHECKING PRODUCT EXISTANCE IN SHOPIFY STORE ----- " . $exist_product->getMessage();
        $arra["status"]  = "2";
        storeLog($array);
        return jsonResponse(false,  null, $array["message"], 200);
    }
}


function checkOrder($order)
{

    if (isset($order['id']) && !empty($order['id'])) {
        $order_id = eliminateGid($order['id']);
        $order = ShopifyOrder::where("shopify_order_id",  $order_id)->first();
        if (!isset($order) || empty($order)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function saveOrder($order = null, $tags = null, $response_data = null, $url = null)
{
    $order = ShopifyOrder::create([
        "url" => $url ?? null,
        "shopify_order_id" => isset($order['id']) && !empty($order['id']) ?  eliminateGid($order['id']) :  null,
        "shopify_order_gid" => $order['id'] ?? null,
        "customer_gid" => $order['customer']['id'] ?? null,
        "raw_data" => isset($order) && !empty($order) ?  json_encode($order) : null,
        "tags" => isset($tags)  && !empty($tags) ? json_encode($tags)  : null,
        "response_data" => isset($response_data) && !empty($response_data) ?  json_encode($response_data) : null,
        "status" => 0,
    ]);
    return $order;
}

function determineVariantSize($lineItem)
{
    if (isset($lineItem['sku']) && !empty($lineItem['sku'])) {
        $tagValue = $lineItem['sku'];
        if (strpos($tagValue, '-') !== false) {
            $tag = explode("-", $tagValue);
            $tag = $tag[1] ?? null;
            if (isset($tag) && !empty($tag)) {
                return $tag;
            }
        } else {
            $lastThreeChars  = substr($tagValue, -3);
            // Check if the first character of the last three characters is a digit
            if (is_numeric($lastThreeChars[0])) {
                // The first character is a digit
                return strtolower($lastThreeChars);
            } else {
                // The first character is not a digit, return null or handle accordingly
                return $lineItem['variant']['title'] ?? "0";
            }
            return  strtolower($lastThreeChars);
        }
    } else {
        return null;
    }
}


// this is the function to update order and assign a tag "fetched"
function updateOrder($orderId, $tags, $order, $url)
{
    $client = new Client();
    $store = getStoreDetails();
    $query = <<<GRAPHQL
    mutation {
      orderUpdate(input: {
        id: "$orderId",
        tags: ["fetched"]
      }) {
        order {
          id
          tags
        }
        userErrors {
          field
          message
        }
      }
    }
    GRAPHQL;
    try {
        $url = $store->base_url . $store->api_version . '/graphql.json';
        $response = $client->post($url, [
            'headers' => [
                'X-Shopify-Access-Token' => $store->access_token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'query' => $query,
            ],
        ]);
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) {
            $responseBody = $response->getBody()->getContents();
            $data = json_decode($responseBody, true);
            if (isset($data['data']['orderUpdate']['order'])) {
                $saveOrder = saveOrder($order, $tags, $data, $url);
                saveLog("Order Fetched", $saveOrder->id, "ShopifyOrder", 1);
                return response()->json(['success' => 'Order Updated']);
            } else {
                $errors = $data['data']['orderUpdate']['userErrors'];
                return response()->json(['error' => 'Failed to update order', 'errors' => $errors]);
            }
        } else {
            // Failure: handle the error
            $responseBody = $response->getBody()->getContents();
            $error = json_decode($responseBody, true);
            saveLog("Error Occured while fetching the order:" . $error, eliminateGid($orderId), "This is not from any table", 2);
            return response()->json([
                'error' => 'Failed to update order',
                'status_code' => $statusCode,
                'response_body' => $error,
            ]);
        }
    } catch (\Exception $e) {
        saveLog("Error Occured: " . $e->getMessage() . " on line no:" . $e->getLine() . " of file : " . $e->getFile(), null, null, 2);
        return response()->json(['error' => $e->getMessage()]);
    }
}


function saveLog($description = null, $model_id = null, $model_name  = null, $status = null)
{
    return ModelsLog::create([
        "model_id" => $model_id ?? null,
        "model_name" => $model_name ?? null,
        "description" => $description ?? null,
        "status" => $status ?? null,
    ]);
}
