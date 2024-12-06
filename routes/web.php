<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
 
Route::get('/', function () {
    return redirect()->route("dashboard.index")->with("success", "Welcome to Dashboard");
});
 

Auth::routes();