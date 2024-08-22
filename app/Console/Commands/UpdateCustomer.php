<?php

namespace App\Console\Commands;

use App\Models\ShopifyOrder;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class UpdateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is the command to update customers based on their ordered products';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $client = new Client();
        $store = getStoreDetails();
        $orders = ShopifyOrder::where("status", 0)->limit(20)->get();

        if (isset($orders) && !empty($orders) && count($orders) > 0) {
            foreach ($orders as $index => $value) {

                $customerId = $value->customer_gid;

                // GraphQL query to get the current customer tags
                $query = <<<GRAPHQL
                {
                    customer(id: "$customerId") {
                        id
                        tags
                    }
                }
                GRAPHQL;

                try {
                    // Step 1: Retrieve the customer information with current tags
                    $response = $client->post($store->base_url . $store->api_version . '/graphql.json', [
                        'headers' => [
                            'X-Shopify-Access-Token' => $store->access_token,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'query' => $query,
                        ],
                    ]);
                    return $response;
                    $customerData = json_decode($response->getBody()->getContents(), true);
                    dd($customerData);
                    $currentTags = $customerData['data']['customer']['tags'] ?? '';

                    // Prepare the tags to be updated
                    $tagsArray = array_filter(array_map('trim', explode(',', $currentTags)));
                    $updatedTags = implode(', ', $tagsArray);

                    // GraphQL mutation to update the customer tags
                    $mutation = <<<GRAPHQL
                        mutation {
                        customerUpdate(input: {
                            id: "$customerId",
                            tags: "$updatedTags"
                        }) {
                            customer {
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

                    // Step 2: Update the customer with the new tags
                    $response = $client->post($store->base_url . 'admin/api/' . $store->api_version . '/graphql.json', [
                        'headers' => [
                            'X-Shopify-Access-Token' => $store->access_token,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'query' => $mutation,
                        ],
                    ]);

                    $responseData = json_decode($response->getBody()->getContents(), true);

                    if (isset($responseData['data']['customerUpdate']['customer'])) {
                        return response()->json(['success' => 'Customer updated', 'data' => $responseData['data']['customerUpdate']['customer']]);
                    } else {
                        $errors = $responseData['data']['customerUpdate']['userErrors'];
                        return response()->json(['error' => 'Failed to update customer', 'errors' => $errors]);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'error' => 'Failed to update customer: ' . $e->getMessage(),
                    ], 500);
                }
            }
        }
    }
}
