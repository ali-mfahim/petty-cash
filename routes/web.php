<?php

use App\Http\Controllers\PaymentFormController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route("dashboard.index")->with("success", "Welcome to Dashboard");
});


Auth::routes();

Route::get("submit-petty-cash-form/{slug}", [PaymentFormController::class, 'index'])->name("front.paymentform");
