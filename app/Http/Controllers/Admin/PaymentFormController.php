<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyCalculation;
use App\Models\PaymentForm;
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
        $data['title'] = "Monthly User Report";
        $data['monthlyData'] = PaymentForm::selectRaw("DATE_FORMAT(date, '%m/%Y') as month_year, COUNT(*) as total_records, MAX(date) as max_date")
            ->whereNotNull('date')
            ->groupByRaw("DATE_FORMAT(date, '%m/%Y')")
            ->orderBy('max_date', 'desc')
            ->get();

        return view("admin.pages.payment-forms.index", $data);
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
            return view("admin.pages.payment-forms.show", compact("monthlyData", "title"));
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
            $title = "Report of" . getUserName(getUser($user_id)) . " for the month of "   . formatMonthYear($month_year);
        } else {
            $title = "Report of"  . formatMonthYear($month_year);
        }
        return view("admin.pages.payment-forms.show", compact("title", "month_year", "month", "year", "user_id"));
    }
    public function json(Request $request, $month, $year, $user_id)
    {

        if ($request->ajax()) {
            $user = getUser($user_id);
            $role = getMyRole($user_id);
            $month_year = $month . '/' . $year;
            $record = new MonthlyCalculation();
            if ($role != "Super Admin") {
                $record =  $record->where("user_id", $user_id);
            }
            $records = $record->where("month_year", $month_year)->orderBy("id", "desc")->select("*");
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn("date", function ($model) {
                    return isset($model->date) && !empty($model->date) ? formatDate($model->date) : '-';
                })
                ->addColumn("paid_by", function ($model) {
                    return isset($model->form->paidBy->name) && !empty($model->form->paidBy->name) ?  objectWithHtml($model->form->paidBy->name) : '-';
                })
                ->addColumn("transaction_type", function ($model) {
                    if (isset($model->transaction_type) && !empty($model->transaction_type)) {
                        $img = '';
                        $trans_type = '';

                        if ($model->transaction_type == 1) {
                            $img = asset('icons/trending-up.svg');
                            $trans_type = '<span class="badge bg-warning" style="color:black">
                                                   Credit
                                               </span>';
                        } elseif ($model->transaction_type == 2) {
                            $img = asset('icons/trending-down.svg');
                            $trans_type = '<span class="badge bg-success" style="color:black">
                                                   Contribute
                                               </span>';
                        }
                        return $trans_type;
                    } else {
                        return "-";
                    }
                })
                ->addColumn("divided_in", function ($model) {
                    $data = $model->form->divided_in;
                    $data = json_decode($model->form->divided_in);
                    $span = '<ul>';
                    foreach ($data as $value) {
                        $color = "";
                        if ($model->form->paid_by == $value) {
                            $color = 'border-bottom:1px solid green';
                        } else {
                            $color = 'border-bottom:1px solid red';
                        }
                        $span .= '<li>';
                        $span .= '<span style="' . $color . '">';
                        $span .= getUserName(getUser($value));
                        $span .= '</span>';
                        $span .= '</li>';
                    }
                    $span .= "</ul>";
                    return $span;
                })
                ->addColumn("total_amount", function ($model) {
                    return "Rs." . number_format($model->form->total_amount, 2)  ?? 0;
                })
                ->addColumn("amount", function ($model) {
                    return "Rs." . number_format($model->amount, 2) ?? 0;
                })
                ->addColumn("food_item", function ($model) {
                    $title = "";
                    if (isset($model->form->title) && !empty($model->form->title)) {
                        $title .= '<h4>';
                        $title .= $model->form->title;
                        $title .= '</h4>';
                    }
                    return $title;
                })
                ->rawColumns(['paid_by', 'total_amount', 'divided_in', 'transaction_type', 'amount', 'date', 'food_item'])
                ->make(true);
        }
    }
}
