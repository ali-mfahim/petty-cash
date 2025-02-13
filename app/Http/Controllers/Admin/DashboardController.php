<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $this->authorize("dashboard-view");
        $data['title'] = "Dashboard";
        $data['users'] = User::count();
        return view("admin.pages.dashboard.index", $data);
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
    public function show(string $id)
    {
        //
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

    public function dashboardData(Request $request)
    {
        $date = Carbon::now();
        $dates = getMonthDates($date->format("m"), $date->format("Y"));
        $paid = [];
        $unPaid = [];
        $expense = [];
        if (!empty($dates)) {
            foreach ($dates as $i => $v) {

                $paid[$v] = getDateCalculation($v, $request->user_id, "paid")['petty'];

                $unPaid[$v] = getDateCalculation($v, $request->user_id, "unPaid")['petty'];

                $expense[$v] = getDateCalculation($v, $request->user_id, "expense")['expense'];

                
            }
            return jsonResponse(true,  [
                "paid" => $paid,
                "paidSum" => round(array_sum($paid)),

                "unPaid" => $unPaid,
                "unPaidSum" => round(array_sum($unPaid)),

                "expense" => $expense,
                "expenseSum" => round(array_sum($expense)),

            ],  "Graph Data", 200);
        }
    }
}
