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
        Log::info("FETCHING ORDERS");
        $store = getStoreDetails();

        if ($store) {
            $accessToken = $store->access_token;
            $url = $store->base_url . $store->api_version . "/graphql.json";
            $client = new Client();
            $limit = 50;
            $query = <<<GRAPHQL
                {
                    orders(first: $limit, query: "status:any created_at:<2024-07-01  tag_not:fetched", sortKey: CREATED_AT, reverse: true) {
                        edges {
                            node {
                                id
                                name
                                createdAt
                                tags
                                lineItems(first: 100) {
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
                                if (isset($variantSize) && !empty($variantSize) && $variantSize != false) {
                                    $tags[] = str_replace(" ", "", $variantSize);
                                }
                            }
                            if (isset($tags) && !empty($tags)) {
                                Log::info("TAGS FETCHED:" . json_encode($tags));
                            }
                            $added[] = updateOrder($order['id'],  $tags ?? null, $order, $query);
                        } else {
                            saveLog("Order already exists:", eliminateGid($order['id']), null, '3');
                        }
                    }
                }

                $message = count($ids) . " Orders have been fetched";
                saveLog($message, json_encode($ids), null, '1');
                $this->info($message);
                Log::info("COMMAND EXECUTION 'fetch-order'  DONE: " . json_encode($message));
            } catch (\Exception $e) {
                saveLog($e->getMessage() . " - lineNO : " . $e->getLine() . "-  filename :" . $e->getFile(), null, null, 2, []);
                return response()->json([
                    'error' => 'Failed to retrieve orders: ' . $e->getMessage() . " on line no: " . $e->getLine(),
                ], 500);
            }
        } else {
            saveLog(
                "There is no active store or app found while running command: 'fetch-order' ",
                null,
                "ShopifyOrder",
                3,
                []
            );
        }
    }
}
