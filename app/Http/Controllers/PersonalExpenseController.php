<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomNotFoundException;
use App\Models\PaymentForm;
use App\Models\PaymentLink;
use App\Models\PersonalExpense;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PersonalExpenseController extends Controller
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
                    $title  = "Personal Expense Form";
                    return view("front.personalExpenses.create", compact("user", "logos", "users", "findLink", "defaultKeywords", "title"));
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
                return view("front.personalExpenses.thankyou", compact("session", "link", "logos"));
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
        ], [
            "date.required" => "Please select a valid date",
            "food_item.required" => "Please insert title",
            "amount.required" => "Amount can not be empty",
        ]);
        if ($validator->fails()) {
            return jsonResponse(false, $validator->errors()->all(), "Validation Errors", 200);
        }
        try {
            $link = PaymentLink::where("id", $request->link_id)->where("status", 1)->first();
            if (isset($link) && !empty($link)) {
                try {
                    $create = PersonalExpense::create([
                        "submit_by" => $link->user_id,
                        "paid_by" => $request->paid_by,
                        "user_id" => $link->user_id,
                        "title" => $request->food_item ?? null,
                        "description" => $request->remarks ?? null,
                        "amount" => $request->amount ?? null,
                        "date" => $request->date ?? null,
                        "type" => $request->type ?? 0,
                    ]);
                } catch (Exception $e) {
                    return jsonResponse(false, "Create Error", "Error: " . $e->getMessage() . "  line: " . $e->getLine(), 200);
                }
                if ($create->id) {
                    Session::put("thankyou_" . $link->id, "Thankyou for submitting the form");
                    $route = route("front.expenseForm.thankyou", $link->slug);
                    return jsonResponse(true, ['created' => $create, 'reset' => true, 'redirect' => $route], "Form has been submitted", 200);
                } else {
                    return jsonResponse(true, [], "Failed to submit", 200);
                }
            }
        } catch (Exception $e) {
            saveLog("Error while saving expense form: " . $e->getMessage(), null, "Personal Expense", 2, $request->all);
            return jsonResponse(false, [], "Error: " . $e->getMessage() . "  line: " . $e->getLine(), 200);
        }
    }
}
