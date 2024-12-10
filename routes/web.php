<?php

use App\Http\Controllers\PaymentFormController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route("dashboard.index")->with("success", "Welcome to Dashboard");
});


Auth::routes();

Route::get("submit-petty-cash-form/{slug}", [PaymentFormController::class, 'index'])->name("front.paymentform");
Route::get("submit-petty-cash-form-thankyou/{slug}", [PaymentFormController::class, 'thankyou'])->name("front.paymentform.thankyou");
Route::post("submit-petty-cash-form", [PaymentFormController::class, 'submit'])->name("front.paymentform.submit");
