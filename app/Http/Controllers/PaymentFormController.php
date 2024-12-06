<?php

namespace App\Http\Controllers;

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
                return $user;
                return view("front.paymentForms.create");
            }
        }
    }
}
