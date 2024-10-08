<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerForm;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->input) && !empty($request->input)) {
            $search = $request->input;
            $data = CustomerForm::where("email", "LIKE", "%$search%")->orWhere("first_name", "LIKE", "%$search%")->orWhere("last_name", "LIKE", "%$search%")->orWhere("phone_number", "LIKE", "%$search%")->get();
            if (isset($data) && !empty($data)) {
                foreach ($data as  $value) {
                    $route =  route('coorporate-forms.show', $value->id);
                    $li = "";
                    $li .= '<li class="auto-suggestion" style="border-bottom: 1px solid #5e5e5e;">';
                    $li .= '<a class="d-flex align-items-center justify-content-between w-100" href="' . $route . '">';
                    $li .= '<div class="d-flex justify-content-start align-items-center">';
                    $li .= '<span class="badge bg-success" style="margin-right: 10px;font-size: 10px;">Coorporate Form</span> ';
                    $li .= getUserName($value);
                    $li .= '</div>';
                    $li .= '</a>';
                    $li .= '</li>';
                    $searchResults[] = $li;
                }
            }

            $data = Customer::where("email", "LIKE", "%$search%")->orWhere("first_name", "LIKE", "%$search%")->orWhere("last_name", "LIKE", "%$search%")->orWhere("phone_number", "LIKE", "%$search%")->get();
            if (isset($data) && !empty($data)) {
                foreach ($data as  $value) {
                    $route =  route('customers.customerDetail', $value->id);
                    $li = "";
                    $li .= '<li class="auto-suggestion" style="border-bottom: 1px solid #5e5e5e;">';
                    $li .= '<a class="d-flex align-items-center justify-content-between w-100" href="' . $route . '">';
                    $li .= '<div class="d-flex justify-content-start align-items-center">';
                    $li .= '<span class="badge bg-danger" style="margin-right: 10px;font-size: 10px;">Customer</span> ';
                    $li .= getUserName($value);
                    $li .= '</div>';
                    $li .= '</a>';
                    $li .= '</li>';
                    $searchResults[] = $li;
                }
            }
        }

        return jsonResponse(true, $searchResults, 'Search Results!', 200);
    }
}
