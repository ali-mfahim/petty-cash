<?php

use App\Http\Controllers\PaymentFormController;
use App\Http\Controllers\PersonalExpenseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route("dashboard.index")->with("success", "Welcome to Dashboard");
});


Auth::routes();

Route::get("petty-cash/{slug}", [PaymentFormController::class, 'index'])->name("front.paymentform");
Route::get("petty-cash-thankyou/{slug}", [PaymentFormController::class, 'thankyou'])->name("front.paymentform.thankyou");
Route::post("submit-petty-cash-form", [PaymentFormController::class, 'submit'])->name("front.paymentform.submit");


// user for both petty cash and expense form 
Route::get("get-link-modal-content", [PaymentFormController::class, 'getLinkModalContent'])->name("front.paymentForms.modalContent");
// user for both petty cash and expense form 


Route::get("expense-form/{slug}", [PersonalExpenseController::class, 'index'])->name("front.expenseForm");
Route::get("expense-form-thankyou/{slug}", [PersonalExpenseController::class, 'thankyou'])->name("front.expenseForm.thankyou");
Route::post("submit-expense-form-form", [PersonalExpenseController::class, 'submit'])->name("front.expenseForm.submit");
