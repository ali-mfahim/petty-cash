<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->route("dashboard.index");
    }
    public function refreshToken()
    {

        // $response = Http::get('https://graph.instagram.com/refresh_access_token', [
        //     'grant_type' => 'ig_refresh_token',
        //     'access_token' => 'IGQWRQMEpDaTFjdm50Q0l5dnBWbWoyOUhtM2RPWTZAxRUY0OUd5UV9EX2ZANRnVON0IxbmpZAN1NtYnZA2Qk1sZAkNFWTdGcGhVUTUtZAmlMOEFseWNTUXE1Umg1ME12REprWDNIZA3BNUnktdlFva2QtczdVSHd2eEZANVXcZD',
        // ]);



        $response = Http::asForm()->post('https://api.instagram.com/oauth/access_token', [
            'client_id' => "352287159698416",
            'client_secret' => "ad28f93e891bc0ed3e694c12178c02ec",
            'grant_type' => 'authorization_code',
            'redirect_uri' => "http://petty-cash.local/",
            'code' => "AQBnJOF_ba4tJcemDAQhe3JCFc6Tbk0kfPgtpUpb4grA2zyLbBq32CzWJOwqYnY42G00HSU6YfgjM0HXS8OxkPf10QjJPAKyoLkHRyzSPQrZ5sOiU5G6JHjKGyfscKHNWYNwk7gbBgI3y0xr84m5-6x3akgt6AxHw8h-IQ9R1GlgTZKB5ldR97MYrLXoe5GQ1GZsvPD9YbGsQYSx6ijxRuVJydAbJtpvX9yrJPIScpF9Yw",
        ]);
        return $response;
        $accessToken = $response->json()['access_token'] ?? null;
        
        return $accessToken ?: 'Failed to get access token';



        return $response->json();
    }
}
