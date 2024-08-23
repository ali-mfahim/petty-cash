<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class RemoveTagFromOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $store = getStoreDetails();
        $accessToken = $store->access_token;
        $url = $store->base_url . $store->api_version . "/graphql.json";


        $client = new Client();
        $query = <<<GRAPHQL
        {
        orders(first: 200, query: "status:any created_at:<2024-07-01 tag:fetched") {
            edges {
            node {
                id
                tags
            }
            }
        }
        }
        GRAPHQL;

        // Execute the query and get the orders
        $response = $client->post($url, [
            'body' => json_encode(['query' => $query]),
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $accessToken,
            ],
        ]);

        $orders = json_decode($response->getBody()->getContents(), true)['data']['orders']['edges'];
        $idsArray = [];
        foreach ($orders as $order) {
            $orderId = $order['node']['id'];
            $idsArray[] = $orderId;
            Log::info("Order id remove tag from: " . json_encode($orderId));
            $tags = $order['node']['tags'];

            // Remove the "fetched" tag
            $updatedTagsArray = array_filter($tags, function ($tag) {
                return $tag !== 'fetched';
            });

            // Convert back to the correct format
            $formattedTags = '["' . implode('", "', $updatedTagsArray) . '"]';
            // dd($formattedTags, $tags);
            // Prepare the mutation query
            $mutation = <<<GRAPHQL
            mutation {
                orderUpdate(input: {
                    id: "$orderId",
                    tags: $formattedTags
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

            // Execute the mutation to update the order
            $client->post($url, [
                'body' => json_encode(['query' => $mutation]),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $accessToken,
                ],
            ]);
        }
        dd($idsArray);
    }
}
