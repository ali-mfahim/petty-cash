<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopifyOrder;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("order-report");
        $data['title'] = "Order Report";
        $data['report'] = ShopifyOrder::select(
            'store_id',
            'app_id', // Add app_id here
            DB::raw('count(*) as total'),
            DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending'),
            DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as in_process'),
            DB::raw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completed'),
            DB::raw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as skipped')
        )
            ->groupBy('store_id', 'app_id') // Group by app_id as well
            ->with('store')
            ->with('app')
            ->get();
        return view("admin.pages.reports.index", $data);
    }
    public function details(Request $request,  $slug)
    {
        $this->authorize("order-list");
        $data['store'] = Store::where("slug", $slug)->first();
        if (isset($data['store']) && !empty($data['store'])) {
            $data['title'] = "Report detail of " . $data['store']->name ?? '';
            if ($request->ajax()) {
                $orders = ShopifyOrder::where("store_id", $data['store']->id)->select("*");
                return DataTables::of($orders)
                    ->addIndexColumn()
                    ->addColumn("order_id", function ($model) {
                        $column = "";
                        $column .= '<a href="javascript:;">';
                        $column .= '<span class="badge bg-danger" >' .  $model->shopify_order_id ?? '-' . '</span>';
                        $column .= '</a>';
                        return $column;
                    })
                    ->addColumn("customer_id", function ($model) {
                        $column = "";
                        $customer_id = "";
                        if (isset($model->customer_gid) && !empty($model->customer_gid)) {
                            $customer_id = eliminateGid($model->customer_gid);
                        }
                        $column .= '<a href="javascript:;">';
                        $column .= '<span class="badge bg-danger" >' . $customer_id ?? '-' . '</span>';
                        $column .= '</a>';
                        if (isset($model->customer_gid) && !empty($model->customer_gid)) {
                            $column .= '<a href="javascript:;" class="text-white view_customer_btn" data-order-id="' . $model->id . '" style="text-decoration:underline;margin-left:20px;">View</a>';
                        }


                        return $column;
                    })
                    ->addColumn("tags", function ($model) {
                        $column = "";
                        if (isset($model->tags) && !empty($model->tags)) {
                            $column .= "<ul>";
                            $tags = json_decode($model->tags);
                            if (isset($tags) && !empty($tags)) {
                                foreach ($tags as $tag) {
                                    $column .= "<li>";
                                    $column .= $tag;
                                    $column .= "</li>";
                                }
                            }
                            $column .= "</ul>";
                            return $column;
                        }
                    })
                    ->addColumn("updated_at", function ($model) {
                        return formatDateTime($model->updated_at);
                    })
                    ->addColumn("status", function ($model) {
                        $column = "";
                        switch ($model->status) {
                            case "0":
                                $column .= '<a href="javascript:;">';
                                $column .= '<span class="badge bg-warning" >Pending</span>';
                                $column .= '</a>';
                                return $column;
                            case "1":
                                $column .= '<a href="javascript:;">';
                                $column .= '<span class="badge bg-danger" >In Process</span>';
                                $column .= '</a>';
                                return $column;

                            case "2":
                                $column .= '<a href="javascript:;">';
                                $column .= '<span class="badge bg-success" >Completed</span>';
                                $column .= '</a>';
                                return $column;
                            case "3":
                                $column .= '<a href="javascript:;">';
                                $column .= '<span class="badge bg-warning" >Skipped</span>';
                                $column .= '</a>';
                                return $column;
                        }
                    })
                    ->addColumn('actions', function ($model) {
                        return "-";
                    })
                    ->filter(function ($instance) use ($request) {
                        if ($request->get('status') == 0 || $request->get('status') > 0) {
                            $status = $request->get('status');
                            $instance->where('status', $status);
                        }
                        if (!empty($request->get('keyword'))) {
                            $instance->where('shopify_order_id', "LIKE", "%$request->keyword%")->orWhere("customer_gid", "LIKE", "%$request->keyword%");
                        }
                        if (!empty($request->get('search'))) {
                            $instance->where('shopify_order_id', "LIKE", "%$request->search%")->orWhere("customer_gid", "LIKE", "%$request->search%");
                        }
                    })
                    ->rawColumns(['customer_id', 'order_id', 'status', 'tags', 'actions'])
                    ->make(true);
            }

            return view("admin.pages.reports.reportDetails", $data);
        }
    }

    public function viewCustomer(Request $request)
    {

        try {
            if (isset($request->order_id) && !empty($request->order_id)) {
                $order = ShopifyOrder::where("id", $request->order_id)->first();
                if (isset($order) && !empty($order)) {
                    $store = getStoreDetails($order->store_id, "any");
                    if ($store != false) {
                        $customer_id = $order->customer_gid;
                        if (isset($customer_id) && !empty($customer_id)) {
                            $customerGid = $customer_id;
                            $query = <<<GRAPHQL
                            query {
                                customer(id: "$customerGid") {
                                    id
                                    firstName
                                    lastName
                                    email
                                    phone
                                    numberOfOrders
                                    amountSpent {
                                    amount
                                    currencyCode
                                    }
                                    createdAt
                                    updatedAt
                                    note
                                    verifiedEmail
                                    validEmailAddress
                                    tags
                                    lifetimeDuration
                                    defaultAddress {
                                    formattedArea
                                    address1
                                    }
                                    addresses {
                                    address1
                                    }
                                    image {
                                    src
                                    }
                                    canDelete
                                }
                            }
                            GRAPHQL;

                            // Log the request
                            Log::info('Shopify GraphQL Request:', [
                                'url' => $store->base_url . $store->api_version . '/graphql.json',
                                'query' => $query,
                                'variables' => ['customerGid' => $customerGid]
                            ]);

                            $response = Http::withHeaders([
                                'X-Shopify-Access-Token' => $store->access_token,
                                'Content-Type' => 'application/json'
                            ])->post($store->base_url . $store->api_version . '/graphql.json', [
                                'query' => $query,
                            ]);

                            if ($response->successful()) {
                                $customer = $response->json('data.customer');
                                if (isset($customer)) {
                                    $view = view("admin.pages.reports.components.viewCustomerModalContent", compact("customer"))->render();
                                    return jsonResponse(true, $view, "Customer Details", 200);
                                } else {
                                    $errors = $response->json('errors');
                                    Log::warning('No customer data returned', ['errors' => $errors]);
                                    return jsonResponse(false, [], "No customer data found", 200);
                                }
                            } else {
                                Log::error('Shopify API Error:', [
                                    'status' => $response->status(),
                                    'body' => $response->body(),
                                    'query' => $query,
                                ]);
                                return jsonResponse(false, [], "Failed to get customer details", 200);
                            }
                        } else {
                            return jsonResponse(false, [], "Customer ID not found", 200);
                        }
                    } else {
                        return jsonResponse(false, [], "Store or app not found", 200);
                    }
                } else {
                    return jsonResponse(false, [], "Order not found", 200);
                }
            } else {
                return jsonResponse(false, [], "Invalid Request Data", 200);
            }
        } catch (\Throwable $e) {
            Log::error('Exception caught:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);
            return jsonResponse(false, [], "An error occurred: " . $e->getMessage(), 500);
        }
    }
}
