<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $data['title'] = "Profile";
        $data['model'] = getUser();
        return view("admin.profile.index", $data);
    }
    public function update(Request $request)
    {
        $user = getUser();
        $request->validate([
            "first_name" => "required|max:255",
            "email" => [
                "required",
                "email",
                Rule::unique('users')->ignore(getUser()->id), // Ignore the current user's email
            ],
            "phone_number" => "nullable|numeric",
        ], [
            "first_name.required" => "Please insert first name",
            "email.required" => "Please insert email",
            "email.email" => "Please insert a valid email address",
        ]);
        try {
            $update = $user->update([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "phone" => $request->phone_number,
                "address" => $request->address,
            ]);
            if ($update > 0) {
                return jsonResponse(true, getUser(), "Profile Updated", 200);
            } else {
                return jsonResponse(false, [], "Failed to update!", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }
    public function generateLink(Request $request)
    {
        $user = getUser($request->user_id);
        $oldLinks = PaymentLink::where("user_id", $user->id)->get();
        if (isset($oldLinks) && !empty($oldLinks) && count($oldLinks)) {
            foreach ($oldLinks as $index => $value) {
                $value->update(['status' => 0]);
            }
        }
        $generteNewLink = generateLink($user);

        $create = PaymentLink::create([
            "user_id" => $user->id,
            "slug" => $generteNewLink->slug ?? null,
            "link" => $generteNewLink->link ?? null,
            "created_by" => getUser()->id,
        ]);

        return jsonResponse(true, $create, "Response", 200);
    }
}
