<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return jsonResponse(false, [], "No user exists with this email", 200);
        }

        if (!Hash::check($request->password, $user->password)) {
            return jsonResponse(false, [], "The password you entered is incorrect", 200);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember_me');

        if (Auth::attempt($credentials, $remember)) {
            return jsonResponse(
                true,
                [
                    'user' => Auth::user(),
                    'url' => route('dashboard.index'),
                ],
                "User has been logged in successfully",
                200
        );
        } else {
            return jsonResponse(false, [], "Failed to login", 200);
        }
    }
    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("login")->with("success", "Logged out successfuly!");
    }
}
