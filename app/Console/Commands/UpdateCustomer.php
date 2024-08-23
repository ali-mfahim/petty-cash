<?php

namespace App\Console\Commands;

use App\Models\Log;
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


        try {

            $client = new Client();
            $store = getStoreDetails();
            $orders = ShopifyOrder::where("status", 0)->limit(50)->get();
            if (isset($orders) && !empty($orders) && count($orders) > 0) {
                foreach ($orders as  $value) {

                    $orderID = $value->id;
                    $customerId = $value->customer_gid;
                    $value->update([
                        'status' => 1
                    ]);
                    if (isset($customerId) && !empty($customerId)) {
                        if (isset($value->tags) && !empty($value->tags)) {
                            $tagsArray = json_decode($value->tags);
                            // Filter out "DefaultTitle"
                            $filteredTagsArray = array_filter($tagsArray, function ($tag) {
                                return $tag !== "DefaultTitle";
                            });

                            $filteredTagsArray2 = array_filter($filteredTagsArray, function ($tag) {
                                return $tag !== "0";
                            });
                            $filteredTagsArray2 = array_unique($filteredTagsArray2);
                        }
                        // Convert to the desired format
                        $formattedTagsArray = [];
                        foreach ($filteredTagsArray2 as $tag) {
                            $splitTags = explode('/', $tag);
                            $formattedTagsArray = array_merge($formattedTagsArray, $splitTags);
                        }

                        $formattedTags = '["' . implode('", "', $formattedTagsArray) . '"]';
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
                        $getOrder = ShopifyOrder::where("id", $orderID)->first();
                        if (isset($responseData['data']['customerUpdate']['customer'])) {
                            if (isset($getOrder) && !empty($getOrder)) {
                                saveLog("Tags have been updated to customer", $getOrder->id, "ShopifyOrder", 1, [
                                    'customer_id' => isset($getOrder->customer_gid) && !empty($getOrder->customer_gid) ? eliminateGid($getOrder->customer_gid) : null,
                                    'order_id' => $getOrder->shopify_order_id ?? null,
                                    'tags' => isset($formattedTags) && !empty($formattedTags) ?  json_encode($formattedTags) : null,
                                ]);
                                $getOrder->update([
                                    'tags' => json_encode($formattedTags),
                                    'status' => 2
                                ]);
                                Log::info("Update Done:" . json_encode($getOrder->id));
                            }
                            // return response()->json(['success' => 'Customer updated', 'data' => $responseData['data']['customerUpdate']['customer']]);
                        } else {
                            $getOrder->update([
                                'tags' => json_encode($formattedTags),
                                'status' => 0
                            ]);
                            $errors = $responseData['data']['customerUpdate']['userErrors'];
                            saveLog("Something went wrong while updating tags to the customer", $getOrder->id, "ShopifyOrder", 2, $errors);
                            return response()->json(['error' => 'Failed to update customer', 'errors' => $errors]);
                        }
                    } else {
                        saveLog("Customer ID not found for this order: $orderID", $orderID, "ShopifyOrder", 2, []);
                    }
                }
            }
        } catch (\Exception $e) {
            $getOrder = ShopifyOrder::where("id", $orderID)->first();
            saveLog("Error occured while updating tags to the customer", $getOrder->id, "ShopifyOrder", 2, $e->getMessage() . " on line " . $e->getLine() . " of file " . $e->getFile());
            dd('Failed to update customer: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update customer: ' . $e->getMessage(),
            ], 500);
        }
    }
}
