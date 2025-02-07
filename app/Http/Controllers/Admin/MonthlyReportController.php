<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Payment Form List";
        $data['monthlyData'] = MonthlyCalculation::select(
            'month_year',
            DB::raw('MAX(id) as id'),
            DB::raw("COUNT('*') as total_entries"),
            DB::raw('MAX(user_id) as user_id'),
            DB::raw('MAX(date) as date'),
            DB::raw('MAX(created_at) as created_at'),
        )
            ->groupBy('month_year')
            ->orderBy('month_year', 'desc')
            ->get();
        return view("admin.pages.monthly-reports.index", $data);
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
    public function detail($month , $year) {
        $month_year = $month . "/" . $year;
        $monthlyCalculations = MonthlyCalculation::where("month_year" , $month_year)->get();
        dd($monthlyCalculations);

    }
}
