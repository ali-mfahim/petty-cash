<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\User;

class RememberMeTokenLogin
{
    public function handle($request, Closure $next)
    {
        // Check if the 'remember_me' cookie exists
        $rememberTokenName = 'remember_web_' . sha1(config('app.key'));

        if (Cookie::has($rememberTokenName)) {
            $rememberToken = Cookie::get($rememberTokenName);

            // Parse the token from the cookie
            list($id, $token) = explode('|', $rememberToken, 2);

            // Retrieve the user by ID
            $user = User::find($id);

            if ($user && $this->validateRememberToken($user, $token)) {
                Auth::login($user, true);
            }
        }

        return $next($request);
    }

    /**
     * Validate the "Remember Me" token.
     *
     * @param \App\Models\User $user
     * @param string $token
     * @return bool
     */
    protected function validateRememberToken($user, $token)
    {
        return hash_equals($user->getRememberToken(), $token);
    }
}
