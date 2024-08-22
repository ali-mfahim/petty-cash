<?php

namespace App\Http\Controllers;

use App\Models\ShopifyOrder;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $shopUrl;
    protected $accessToken;
    protected $apiVersion;
    protected $client;
    public function __construct()
    {
        $this->shopUrl = config('project.shopify.domain');
        $this->accessToken = config('project.shopify.access_token');
        $this->apiVersion = config('project.shopify.app_version');
        $this->client = new Client();
    }
    public function index2()
    {
        $limit = 250; // Number of orders to fetch (you can adjust this as needed)
        $url = "https://{$this->shopUrl}/admin/api/2023-07/orders.json";

        // to get specidif order
        // $orderId = "5911296278786";
        // $url = "https://{$this->shopUrl}/admin/api/2023-07/orders/{$orderId}.json";
        // to get specidif order


        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $this->accessToken,
                ],
                'query' => [
                    'status' => 'any',
                    'limit' => $limit,
                    // 'tag' => '!updatedFromApi', // Exclude orders with the "updated" tag
                    // 'created_at_max' => '2024-07-01T00:00:00-00:00', // Orders before July 2024
                    // 'order' => 'created_at desc', // Order by creation date descending
                ],
            ]);
            $orders = json_decode($response->getBody()->getContents(), true);
            dd($orders);
            $added = [];

            // to get specidif order
            // foreach ($orders as $order) {
            // to get specidif order
            $tags = [];
            foreach ($orders['orders'] as $order) {
                if (isset($order) && !empty($order)) {
                    $checkOrder = $this->checkOrder($order);
                    if ($checkOrder) {

                        $this->saveOrder($order, null, null, $url);

                        // foreach ($order['line_items'] as $lineItem) {
                        //     $variantSize = $this->determineVariantSize($lineItem);
                        //     if ($variantSize) {
                        //         // $tags[] = [
                        //         //     "id" => $order['id'],
                        //         //     "tag" => str_replace(" ", "", $variantSize),
                        //         // ];
                        //         $tags[] = $variantSize;
                        //     }
                        // }
                        // if (!empty($tags)) {
                        //     $tags = array_unique($tags);
                        //     $tagsString = implode(', ', $tags);
                        //     // $checkOrder == true means the order is not stored in the database and needs to add tags in the store
                        //     $added[] = $this->addTagToOrder($order['id'], $tagsString, $order, $url);
                        // }
                    } else {
                        Log::info("order already exist : " . json_encode($order['id']));
                    }
                }
            }
            return  $added;
            $message = "Tags have been applied to orders " . json_encode($added);
            // Return the orders as JSON or pass them to a view
            return response()->json(['success' => true, "message" => $message], 200);
        } catch (\Exception $e) {
            // Handle the error and return a response
            return response()->json([
                'error' => 'Failed to retrieve orders: ' . $e->getMessage() . " on line no: " . $e->getLine(),
            ], 500);
        }
    }
    public function index()
    {
        $limit = 10;
        $query = <<<GRAPHQL
            {
                orders(first: $limit, query: "status:any created_at:<2024-07-01", sortKey: CREATED_AT, reverse: true) {
                    edges {
                        node {
                            id
                            name
                            createdAt
                            tags
                            lineItems(first: 10) {
                                edges {
                                    node {
                                        sku
                                        title
                                        variant {
                                            id
                                            title
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            GRAPHQL;
        try {
            $response = $this->client->post("https://{$this->shopUrl}/admin/api/2024-07/graphql.json", [
                'headers' => [
                    'X-Shopify-Access-Token' => $this->accessToken,
                    'Content-Type' => 'application/graphql',
                ],
                'body' => $query,
            ]);
            $responseBody = json_decode($response->getBody()->getContents(), true);
            $orders = $responseBody['data']['orders']['edges'] ?? [];
            $added = [];
            foreach ($orders as $edge) {
                $order = $edge['node'];
                if (isset($order) && !empty($order)) {
                    $checkOrder = $this->checkOrder($order);
                    if ($checkOrder) {
                        // $this->saveOrder($order, null, null, $query);
                        $tags = [];
                        foreach ($order['lineItems']['edges'] as $lineItemEdge) {
                            $lineItem = $lineItemEdge['node'];
                            $variantSize = $this->determineVariantSize($lineItem);
                            if ($variantSize) {
                                // $tags[] = $variantSize;
                                $tags[] = [
                                    "id" => $order['id'],
                                    "tag" => str_replace(" ", "", $variantSize),
                                ];
                            }
                        }
                        // if (!empty($tags)) {
                        //     $tags = array_unique($tags);
                        //     $tagsString = implode(', ', $tags);
                        //     $added[] = $this->addTagToOrder($order['id'], $tagsString, $order, $query);
                        // }
                    } else {
                        Log::info("Order already exists: " . json_encode($order['id']));
                    }
                }
            }
            dd($tags);
            // $message = "Tags have been applied to orders " . json_encode($added);
            return response()->json(['success' => true, "message" => $message], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve orders: ' . $e->getMessage() . " on line no: " . $e->getLine(),
            ], 500);
        }
    }

  
    public function test()
    {
        $tags = [];
        $tags[] = "DGGLFJOGGBLX";
        $tags[] = "DGGLFJOGGBLL";
        $tags[] = "DGGLFJOGGBLM";
        $tags[] = "DGGLFJOGGBL32L";
        $tags[] = "DGGLFJOGGBL30S";
        $tags[] = "DGGLFJOGGBL-small";
        $tags[] = "DGGLFJOGGBL-Medium";


        $tagsArray = [];
        foreach ($tags as $value) {
            if (strpos($value, '-') !== false) {
                $tag = explode("-", $value);
                $tag = $tag[1] ?? null;
                if (isset($tag) && !empty($tag)) {
                    $tagsArray[] = strtolower($tag);
                    // return $tag;
                }
            } else {
                // Assume it's the "DGGLFJOGGBL32S" format and return the last 3 characters
                $value = substr($value, -3);

                $tagsArray[] = strtolower($value);
            }
        }
        return $tagsArray;
    }
    
    
}
