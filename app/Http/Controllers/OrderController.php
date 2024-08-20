<?php

namespace App\Http\Controllers;

use App\Models\ShopifyOrder;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $shopUrl;
    protected $accessToken;
    protected $client;

    public function __construct()
    {
        $this->shopUrl = config('project.shopify.domain');
        $this->accessToken = config('project.shopify.access_token');
        $this->client = new Client();
    }

    public function index()
    {
        $limit = 10; // Number of orders to fetch (you can adjust this as needed)
        $url = "https://{$this->shopUrl}/admin/api/2023-07/orders.json";
        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $this->accessToken,
                ],
                'query' => [
                    'limit' => $limit,
                ],
            ]);
            $orders = json_decode($response->getBody()->getContents(), true);
            $added = [];
            foreach ($orders['orders'] as $order) {
                if (isset($order) && !empty($order)) {
                    $checkOrder = $this->checkOrder($order);
                    if ($checkOrder) {
                        $tags = [];
                        foreach ($order['line_items'] as $lineItem) {
                            $variantSize = $this->determineVariantSize($lineItem);
                            if ($variantSize) {
                                $tags[] = str_replace(" ", "", $variantSize);
                            }
                        }
                        if (!empty($tags)) {
                            $tags = array_unique($tags);
                            $tagsString = implode(', ', $tags);
                            // $checkOrder == true means the order is not stored in the database and needs to add tags in the store
                            $added[] = $this->addTagToOrder($order['id'], $tagsString, $order);
                        }
                    }
                }
            }
            $message = "Tags have been applied to orders " . json_encode($added);
            // Return the orders as JSON or pass them to a view
            return response()->json(['success' => true, "message" => $message], 200);
        } catch (\Exception $e) {
            // Handle the error and return a response
            return response()->json([
                'error' => 'Failed to retrieve orders: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function checkOrder($order)
    {
        $order = ShopifyOrder::where("shopify_order_id",  $order['id'])->first();
        if (!isset($order) || empty($order)) {
            return true;
        } else {
            return false;
        }
    }
    public function saveOrder($order = null, $tags = null, $response_data = null)
    {
        $order = ShopifyOrder::create([
            "shopify_order_id" => $order['id'] ?? null,
            "raw_data" => isset($order) && !empty($order) ?  json_encode($order) : null,
            "response_data" => isset($response_data) && !empty($response_data) ?  json_encode($response_data) : null,
        ]);
    }
    protected function determineVariantSize($lineItem)
    {
        if (isset($lineItem['sku']) && !empty($lineItem['sku'])) {
            $tag = explode("-", $lineItem['sku']);
            $tag = $tag[1] ?? null;
            if (isset($tag) && !empty($tag)) {
                return $tag;
            }
        } else {
            return null;
        }
    }
    protected function addTagToOrder($orderId, $tags, $order)
    {
        $url = "https://{$this->shopUrl}/admin/api/2023-07/orders/{$orderId}.json";

        try {
            $response = $this->client->put($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'order' => [
                        'id' => $orderId,
                        'tags' => $tags,
                    ],
                ],
            ]);
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 300) {
                // Success: handle the response
                $responseBody = $response->getBody()->getContents();
                // Optionally, you can decode and process the response body
                $data = json_decode($responseBody, true);
                if (isset($data) && !empty($data)) {
                    $this->saveOrder($order, $tags, $data);

                    
                }
                // Return or log the successful response
                return  response()->json(['success' => 'Order Updated']);
            } else {
                // Failure: handle the error
                $responseBody = $response->getBody()->getContents();
                $error = json_decode($responseBody, true);

                // Log or return the error
                return response()->json([
                    'error' => 'Failed to update order',
                    'status_code' => $statusCode,
                    'response_body' => $error,
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Handle the error
            return null;
        }
    }
}
