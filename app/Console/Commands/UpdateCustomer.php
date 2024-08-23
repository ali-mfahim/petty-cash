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

                try {

                    if (isset($value->tags) && !empty($value->tags)) {

                        $tagsArray = json_decode($value->tags);
                    }

                    // Convert to the desired format
                    $formattedTagsArray = [];
                    foreach ($tagsArray as $tag) {
                        $splitTags = explode('/', $tag);
                        $formattedTagsArray = array_merge($formattedTagsArray, $splitTags);
                    }

                    $formattedTags = '["' . implode('", "', $formattedTagsArray) . '"]';
                    // dd($updatedTags , $value->tags);
                    // GraphQL mutation to update the customer tags
                    $mutation = <<<GRAPHQL
                        mutation {
                        customerUpdate(input: {
                            id: "$customerId",
                            tags: $formattedTags
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
                    $response = $client->post($store->base_url . $store->api_version . '/graphql.json', [
                        'headers' => [
                            'X-Shopify-Access-Token' => $store->access_token,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'query' => $mutation,
                        ],
                    ]);

                    $responseData = json_decode($response->getBody()->getContents(), true);
                    dd($responseData);
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
