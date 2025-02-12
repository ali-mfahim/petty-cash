<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyCalculation;
use App\Models\PaymentForm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PaymentFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = "Month Wise Form Entries";
        $data['monthlyData'] = PaymentForm::selectRaw("DATE_FORMAT(date, '%m/%Y') as month_year, COUNT(*) as total_records, MAX(date) as max_date")
            ->whereNotNull('date')
            ->groupByRaw("DATE_FORMAT(date, '%m/%Y')")
            ->orderBy('max_date', 'desc')
            ->get();

        return view("admin.pages.entries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,  string $id)
    {
        $monthlyData  = MonthlyCalculation::where("id", $id)->first();
        if (isset($monthlyData) && !empty($monthlyData)) {
            $title = "Report of " . formatMonthYear($monthlyData->month_year);
            $user_id = $monthlyData->user_id;
            return view("admin.pages.entries.show", compact("monthlyData", "title"));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function detail($month, $year, $user_id)
    {
        $month_year = $month . '/' . $year;
        $role = getMyRole($user_id);
        if ($role != "Super Admin") {
            $title = "Report of " . getUserName(getUser($user_id)) . " for the month of "   . formatMonthYear($month_year);
            $view = "admin.pages.entries.show";
        } else {
            $title = "Report of "  . formatMonthYear($month_year);
            $view = "admin.pages.entries.admin-show";
        }
        return view($view, compact("title", "month_year", "month", "year", "user_id"));
    }
    public function json(Request $request, $month, $year, $user_id)
    {
        if ($request->ajax()) {
            $user = getUser($user_id);
            $role = getMyRole($user_id);
            $month_year = $month . '/' . $year;
            // if ($role == "Super Admin") {
            //     $records = PaymentForm::whereYear('date', $year)->whereMonth('date', $month)->orderBy("id", "desc")->get();
            // } else {
            //     $records = PaymentForm::whereYear('date', $year)->whereMonth('date', $month)
                  
            //         ->orderBy("id", "desc")->get();

            // }

            $records = PaymentForm::whereYear('date', $year)->whereMonth('date', $month)->orderBy("id", "desc")->select("*");
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn("food_item", function ($model) {
                    $title = "";
                    if (isset($model->title) && !empty($model->title)) {
                        $title .= '<h4>';
                        $title .= $model->title;
                        $title .= '</h4>';
                    }
                    return $title;
                })
                ->addColumn("paid_by", function ($model) {
                    return isset($model->paidBy->name) && !empty($model->paidBy->name) ?  objectWithHtml($model->paidBy->name) : '-';
                })
                ->addColumn("divided_in", function ($model) {
                    $data = $model->divided_in;
                    $data = json_decode($model->divided_in);
                    $span = '<ul>';
                    foreach ($data as   $value) {
                        $class = "";
                        if ($model->paid_by == $value) {
                            $class = 'text-success';
                        } else {
                            $class = 'text-danger';
                        }
                        $span .= '<li>';
                        $span .= '<span class="' . $class . '">';
                        $span .= getUserName(getUser($value));
                        $span .= '</span>';
                        $span .= '</li>';
                    }
                    $span .= '</ul>';
                    return $span;
                })
                ->addColumn("total_amount", function ($model) {
                    return "Rs." . number_format($model->total_amount, 2)  ?? 0;
                })
                ->addColumn("amount", function ($model) {
                    return "Rs." . number_format($model->per_head_amount, 2) ?? 0;
                })
                ->addColumn("entry_date", function ($model) {
                    return isset($model->date) && !empty($model->date) ? formatDate($model->date) : '-';
                })
                ->addColumn("created_at", function ($model) {
                    return isset($model->created_at) && !empty($model->created_at) ? formatDate($model->created_at) : '-';
                })
                ->rawColumns(['paid_by', 'total_amount', 'divided_in', 'transaction_type', 'amount', 'date', 'food_item'])
                ->make(true);
        }
    }
    public function dashboardData(Request $request)
    {
        $type = $request->type;
        $date = Carbon::now();
        $dates = getMonthDates($date->format("m"), $date->format("Y"));
        $array = [];
        if (!empty($dates)) {
            foreach ($dates as $i => $v) {
                $array[$i] = 2;
                $data[] = getDateCalculation($v, $request->user_id, $type);
            }
            return $data;
        }
        return $array;
    }
}
