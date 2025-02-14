<?php

use App\Console\Commands\PettyCashEmail;
use App\Http\Controllers\PaymentFormController;
use App\Http\Controllers\PersonalExpenseController;
use App\Mail\MonthlyPettyCashEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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


Route::get("test-email", function () {
    $data = [
        "dashboardUrl" => "https://acbd.com",
        'userName' => 'John Doe',
        'Subject' => 'PETTY CASH',
        'message' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
        'supportEmail' => 'sm.ali10@yahoo.com',
        'projectName' => 'PETTY CASH - ',
        'logo' =>  getLogos()->logo_white ?? '',
    ];
    Mail::to('recipient@example.com')->send(new MonthlyPettyCashEmail($data));
    return "succcess";
});
