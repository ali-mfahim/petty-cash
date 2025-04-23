<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MonthlyReportExport;
use App\Http\Controllers\Controller;
use App\Models\MonthlyCalculation;
use App\Models\PaymentForm;
use App\Models\UserMonthlyReportStatus;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = getUser()->id;
        $role = getMyRole($userId);
        if ($role == "Super Admin") {
            $data['monthlyData'] = PaymentForm::selectRaw("DATE_FORMAT(date, '%m/%Y') as month_year, COUNT(*) as total_records, MAX(date) as max_date")
                ->whereNotNull('date')
                ->groupByRaw("DATE_FORMAT(date, '%m/%Y')")
                ->orderBy('max_date', 'desc')
                ->get();
        } else {
            $data['monthlyData'] = PaymentForm::selectRaw("DATE_FORMAT(date, '%m/%Y') as month_year, COUNT(*) as total_records, MAX(date) as max_date")
                ->whereNotNull('date')
                ->whereNull('deleted_at')
                ->where(function ($query) use ($userId) {
                    $query
                        ->where('paid_by', $userId)
                        ->orWhereJsonContains('divided_in', (string) $userId); // Ensure it checks JSON array
                })
                ->groupByRaw("DATE_FORMAT(date, '%m/%Y')")
                ->orderBy('max_date', 'desc')
                ->get();
        }
        $data['title'] = "Monthly Reports";
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
    public function detail(Request $request, $month, $year, $user_id)
    {

        $month_year = $month . '/' . $year;
        $role = getMyRole($user_id);
        if ($role != "Super Admin") {
            $title = "Report of " . getUserName(getUser($user_id)) . " for the month of "   . formatMonthYear($month_year);
            $view = "admin.pages.entries.show";
        } else {
            $title = "Admin Report of "  . formatMonthYear($month_year);
            $view = "admin.pages.entries.admin-show";
        }

        if (isset($request->download) && !empty($request->download) && $request->download == true) {
            $data  = $this->json($request, $month, $year, $user_id, $request->download, $title);
            return $data;
        }


        return view($view, compact("title", "month_year", "month", "year", "user_id"));
    }
    public function json(Request $request, $month, $year, $user_id, $download = false, $title = null)
    {

        $user = getUser($user_id);
        $role = getMyRole($user_id);
        $month_year = $month . '/' . $year;
        if ($role == "Super Admin") {
            $record = PaymentForm::whereYear('date', $year)->whereMonth('date', $month);
            Log::info("RECORD FETCHED");
        } else {
            $record = PaymentForm::whereYear('date', $year)->whereMonth('date', $month)
                ->where(function ($query) use ($user_id) {
                    return $query->whereJsonContains("divided_in", $user_id)->orWhere("paid_by", $user_id);
                });
        }
        if ($download == true) {
            $record = $record->orderBy("date", "asc");
        } else {
            $record = $record->orderBy("id", "desc");
        }
        $records = $record->get();
        if ($download == true) {
            $file_name = trim(getUserName(getUser($user_id))) . ' ' . $month . ' ' . $year . ' petty cash';
            $file_name = strtolower($file_name);
            $file_name = str_replace(' ', '-', $file_name);
            $file_name = rtrim($file_name, '-'); // in case there's a trailing dash
            $file_name = ltrim($file_name, '-'); // this removes the dash at the beginning if any
            $file_name .= '.xlsx';

            return Excel::download(new MonthlyReportExport($records->toArray(), $title, $user_id, $role), $file_name);
        }
        if ($request->ajax()) {
            // $records = PaymentForm::whereYear('date', $year)->whereMonth('date', $month)->orderBy("id", "desc")->select("*");
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
    public function getUserReportStatus(Request $request)
    {
        try {
            $user = getUser($request->user_id);
            $month = $request->month;
            $year =  $request->year;
            $month_year = $month . '/' . $year;

            $users = getUsersOfThisMonth($month_year);
            $view = view("admin.pages.entries.components.updateUserReportStatusModalContent", compact("users", "month_year", "user", "month", "year"))->render();
            $data = [
                "user" => $user ?? null,
                "month_year" => $month_year ?? null,
                "users" => $users ?? null,
                "view" => $view ?? null,
            ];
            return jsonResponse(true, $data, "API RESPONSE", 200);
        } catch (Exception $e) {
            $error = "Error occured on :" . $e->getFile() . " line no: " . $e->getLine() .  '  message is ' . $e->getMessage();
            return jsonResponse(false, [], $error, 200);
        }
    }
    public function updateUserReportStatus(Request $request)
    {
        $request->validate([
            "status" => "required",
            "transaction_status" => "required",
            "transaction_user_id" => "nullable",
            "amount" => "required",

        ], [
            "status" => "Status is required",
            "transaction_status" => "Transaction Status is required",
            "transaction_user_id" => "Transaction person is required",
            "amount" => "Amount is required",
        ]);
        try {
            $user = getUser($request->user_id);
            $month = $request->month;
            $year =  $request->year;
            $month_year = $month . '/' . $year;

            $check = UserMonthlyReportStatus::where("user_id", $user->id)->where("month", $month)->where("year", $year)->first();
            if (isset($check) && !empty($check)) {
                if ($check->status == 2) {
                    // do nothing
                    return jsonResponse(true, [], "Record already updated", 200);
                }
            } else {
                $create = UserMonthlyReportStatus::create([
                    "month" => $month ?? null,
                    "year" => $year ?? null,
                    "month_year" => $month_year ?? null,
                    "user_id" => $request->user_id ?? null,
                    "transaction_user_id" => $request->transaction_user_id ?? null,
                    "transaction_type" => $request->transaction_status ?? null,
                    "status" => $request->status ?? null,
                    "amount" => $request->amount ?? 0,
                ]);
                if ($create->id) {
                    return jsonResponse(true, $create, "Status Updated", 200);
                }
            }
        } catch (Exception $e) {
            $error = "Error occured on :" . $e->getFile() . " line no: " . $e->getLine() .  '  message is ' . $e->getMessage();
            return jsonResponse(false, [], $error, 200);
        }
    }
}
