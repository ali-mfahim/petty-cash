<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopifyOrder;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                        $column .= '<span class="badge bg-primary" >' .  $model->shopify_order_id ?? '-' . '</span>';
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
                        $column .= '<span class="badge bg-primary" >' . $customer_id . '</span>';
                        $column .= '</a>';
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
                        switch ($model->status) {
                            case "0":
                                return "Pending";
                            case "1":
                                return "In Process";
                            case "2":
                                return "Completed";
                            case "3":
                                return "Skipped";
                        }
                    })
                    ->addColumn('actions', function ($model) {
                        return "-";
                    })
                    ->rawColumns(['customer_id', 'order_id', 'status', 'tags', 'actions'])
                    ->make(true);
            }

            return view("admin.pages.reports.reportDetails", $data);
        }
    }
}