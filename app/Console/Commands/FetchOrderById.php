<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchOrderById extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-order-by-id {id : The ID of the order to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch a specific order from Shopify store by its ID and update it.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the order ID from the command argument
        $orderId = $this->argument('id');

        if (empty($orderId)) {
            $this->error('Order ID is required.');
            return;
        }

        // Retrieve store details
        $store = getStoreDetails();
        $accessToken = $store->access_token;
        $url = $store->base_url . $store->api_version . "/graphql.json";

        $client = new Client();
        $query = <<<GRAPHQL
        query OrderById(\$id: ID!) {
            order(id: \$id) {
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
        GRAPHQL;

        // Define the query variables
        $variables = [
            'id' => $orderId,
        ];

        try {
            // Make the API request
            $response = $client->post($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ],
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            $order = $responseBody['data']['order'] ?? null;

            if ($order) {
                $this->info('Order fetched successfully:');
                $this->line('Order ID: ' . $order['id']);
                $this->line('Order Name: ' . $order['name']);
                $this->line('Created At: ' . $order['createdAt']);
                $this->line('Tags: ' . implode(', ', $order['tags'] ?? []));
                // Process order line items and customer if needed
            } else {
                $this->error('Order not found.');
            }

        } catch (\Exception $e) {
            Log::error('Failed to retrieve order', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            $this->error('Failed to retrieve order: ' . $e->getMessage() . " on line no: " . $e->getLine());
        }
    }
}
