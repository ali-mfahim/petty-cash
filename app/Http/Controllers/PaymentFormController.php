<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomNotFoundException;
use App\Models\MonthlyCalculation;
use App\Models\PaymentForm;
use App\Models\PaymentLink;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PaymentFormController extends Controller
{
    public function index(Request $request, $slug)
    {
        if (isset($slug) && !empty($slug)) {
            $findLink = PaymentLink::where("slug", $slug)->first();
            if (isset($findLink) && !empty($findLink)) {
                if ($findLink->status == 0 || $findLink->status != 1) {
                    throw new CustomNotFoundException('The link has been expired. Please generate a new one from your profile');
                }
                $slug = base64_decode($slug);
                $explode = explode("~", $slug);
                if (isset($explode[1]) && !empty($explode[1])) {
                    $decodedSlug = $explode[1];
                    $user = getUser($decodedSlug, "slug");
                    if (!isset($user) || empty($user)) {
                        throw new CustomNotFoundException('User Not Fouund');
                    }
                    $logos = getLogos();
                    $users = User::where("status", 1)->get();
                    $defaultKeywords = getDefaultKeywords();
                    return view("front.paymentForms.create", compact("user", "logos", "users", "findLink", "defaultKeywords"));
                } else {
                    throw new CustomNotFoundException('Invalid Form Link');
                }
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
    public function thankyou(Request $request, $slug)
    {
        $logos = getLogos();
        $link = PaymentLink::where("slug", $slug)->where('status', 1)->first();
        if (isset($link) && !empty($link)) {
            $session = Session::get("thankyou_" . $link->id);
            if (isset($session) && !empty($session)) {
                Session::forget("thankyou_" . $link->id);
                return view("front.paymentForms.thankyou", compact("session", "link", "logos"));
            } else {
                return redirect()->to($link->link);
            }
        } else {
            throw new CustomNotFoundException("Link has been expired");
        }
    }

    public function submit(Request $request)
    {
        if (isset($request->paid_by) && !empty($request->paid_by)) {
            $paidUser = User::where("id", $request->paid_by)->first();
            if (!isset($paidUser) || empty($paidUser)) {
                return jsonResponse(false, "", "Invalid Paying User", 200);
            }
        } else {
            return jsonResponse(false, "", "Invalid Paying User", 200);
        }
        $validator  = Validator::make($request->all(), [
            "amount" => "required|integer",
            "date" => "required|date",
            "food_item" => "required|max:255",
            "divided_users_ids" => "required",
        ], [
            "date.required" => "Please select a valid date",
            "food_item.required" => "Please insert food item",
            "amount.required" => "Amount can not be empty",
            "divided_users_ids.required" => "You must select the member in which amount to be divided"
        ]);
        if ($validator->fails()) {
            return jsonResponse(false, $validator->errors()->all(), "Validation Errors", 200);
        }

        try {
            $link = PaymentLink::where("id", $request->link_id)->where("status", 1)->first();
            if (isset($link) && !empty($link)) {
                $dividedUsersIdsJson = $request->divided_users_ids;
                $dividedUsersIds = json_decode($dividedUsersIdsJson, true);
                $totalUsers = count($dividedUsersIds);
                $indiviualAmount = 0;
                if ($totalUsers > 0) {
                    $indiviualAmount = $request->amount / $totalUsers;
                }
                try {
                    $create = PaymentForm::create([
                        "submit_by" => $link->user_id,
                        "paid_by" => $request->paid_by,
                        "title" => $request->food_item ?? null,
                        "description" => $request->remarks ?? null,
                        "total_amount" => $request->amount ?? null,
                        "per_head_amount" => $indiviualAmount ?? null,
                        "date" => $request->date ?? null,
                        "divided_in" => json_encode($dividedUsersIds) ?? null,
                    ]);
                } catch (Exception $e) {
                    return jsonResponse(false, "Create Error", "Error: " . $e->getMessage() . "  line: " . $e->getLine(), 200);
                }
                if ($create->id) {
                    $date = Carbon::parse($request->date);
                    $month = $date->format('m');
                    $year = $date->format('Y');
                    foreach ($dividedUsersIds as $index => $value) {
                        $getIndividualAmount = getIndividualAmount($indiviualAmount, $value, $request->paid_by);
                        try {
                            MonthlyCalculation::create([
                                "link_id" => $link->id ?? null,
                                "form_id" => $create->id ?? null,
                                "user_id" => $value ?? null,
                                "date" => $date ?? null,
                                "month" => $month,
                                "year" => $year,
                                "month_year" => $month . '/' . $year,
                                "amount" => $indiviualAmount,
                                "transaction_type" => $getIndividualAmount->transaction_type,
                            ]);
                        } catch (Exception $e) {
                            return jsonResponse(true, [], $e->getMessage(), 200);
                        }
                    }
                    Session::put("thankyou_" . $link->id, "Thankyou for submitting the form");
                    $route = route("front.paymentform.thankyou", $link->slug);
                    return jsonResponse(true, ['created' => $create, 'reset' => true, 'redirect' => $route], "Form has been submitted", 200);
                } else {
                    return jsonResponse(true, [], "Failed to submit", 200);
                }
            }
        } catch (Exception $e) {
            saveLog("Error while saving payment form: " . $e->getMessage(), null, "PaymentForm", 2, $request->all);
            return jsonResponse(false, [], "Error: " . $e->getMessage() . "  line: " . $e->getLine(), null);
        }
    }
}
