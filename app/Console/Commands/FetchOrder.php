<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is the command to fetch orders from shopify store and update the order in the store';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $store = getStoreDetails();
        $accessToken = $store->access_token;
        $url = $store->base_url . $store->api_version . "/graphql.json";


        $client = new Client();
        $limit = 2;
        $query = <<<GRAPHQL
        {
            orders(first: $limit, query: "status:any created_at:<2024-07-01 tag_not:fetched", sortKey: CREATED_AT, reverse: true) {
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
                        customer {
                            id
                        }
                    }
                }
            }
        }
        GRAPHQL;
        try {
            $response = $client->post($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/graphql',
                ],
                'body' => $query,
            ]);
            $responseBody = json_decode($response->getBody()->getContents(), true);
            $orders = $responseBody['data']['orders']['edges'] ?? [];
            $added = [];
            $ids = [];
            $tagsArray = [];
            foreach ($orders as $edge) {
                $order = $edge['node'];
                if (isset($order) && !empty($order)) {
                    $checkOrder = checkOrder($order);
                    $ids[] =  eliminateGid($order['id']);
                    if ($checkOrder) {
                        $tags = [];
                        foreach ($order['lineItems']['edges'] as $lineItemEdge) {
                            $lineItem = $lineItemEdge['node'];
                            $variantSize = determineVariantSize($lineItem);
                            if ($variantSize) {
                                $tags[] = str_replace(" ", "", $variantSize);
                            }
                        }
                        array_unique($tags);
                        $added[] = updateOrder($order['id'], $tags ?? null, $order, $query);
                    } else {
                        saveLog("Order already exists:", eliminateGid($order['id']), null, '3');
                    }
                }
            }

            $message = count($ids) . " Orders have been fetched";
            saveLog($message, json_encode($ids), null, '1');
            return $message;
            return response()->json(['success' => true, "message" => $message], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve orders: ' . $e->getMessage() . " on line no: " . $e->getLine(),
            ], 500);
        }
    }
}
