<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("customer-list");
        $data['title'] = "Customers List";
        if ($request->ajax()) {
            $users = Customer::orderBy("id", "desc")->select("*");
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn("name", function ($model) {
                    return view('admin.pages.customers.components.userView', ['record' => $model])->render();
                })

                ->addColumn("forms", function ($model) {
                    return '<span class="badge bg-primary">' . count($model->forms) . '</span>';
                })

                ->addColumn("phone", function ($model) {
                    $column = "";
                    $column .= isset($model->country->phonecode) && !empty($model->country->phonecode) ? "+" . $model->country->phonecode . "-" : '';
                    $column .=  $model->phone_number ?? '';
                    return $column;
                })
                ->addColumn('actions', function ($model) {
                    $record = $model;
                    return view("admin.pages.customers.components.action", compact("record"));
                })
                ->rawColumns(['name', 'forms', 'actions'])
                ->make(true);
        }
        return view("admin.pages.customers.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("customer-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("customer-create");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize("customer-view");
        $customer = Customer::with("forms")->where("id", $id)->first();
        if (isset($customer) && !empty($customer)) {
            $view = view("admin.pages.customers.components.show", compact("customer"))->render();
            return jsonResponse(true, $view, "Customer Details", 200);
        } else {
            return jsonResponse(false, [], "Failed to get customer details", 200);
        }
    }
    public function customerDetail($id)
    {
        $this->authorize("customer-view");
        $title = "Customer Detail";
        $customer = Customer::with("forms")->where("id", $id)->first();
        if (isset($customer) && !empty($customer)) {
            return view("admin.pages.customers.show", compact("customer", "title"));
        } else {
            return redirect()->route("customers.index")->with("error", "Customer Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize("customer-edit");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("customer-edit");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize("customer-delete");
    }
}
