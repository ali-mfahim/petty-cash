<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PaymentFormController extends Controller
{
    public function index(Request $request, $slug)
    {
        if (isset($slug) && !empty($slug)) {
            $slug = base64_decode($slug);
            $explode = explode("~", $slug);
            if (isset($explode[1]) && !empty($explode[1])) {
                $decodedSlug = $explode[1];
                $user = getUser($decodedSlug, "slug");
                if (!isset($user) || empty($user)) {
                    abort(404);
                }
                $logos = getLogos();
                $users = User::where("status", 1)->get();
                return view("front.paymentForms.create", compact("user", "logos", "users"));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function submit(Request $request)
    {
        // $format = str_replace("[", "", $request->divided_users_ids);
        // $format = str_replace("]", "", $format);
        // $format = explode(",", $format);
        // dd($format[0]);
        return jsonResponse(true, $request->all(), "All Requests", 200);
    }
}
