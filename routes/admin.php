<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerFormController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GmailController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// Route::get("/", function () {
//     // return redirect()->route("dashboard.index");
// });

Route::group(['middleware' => ['web', 'rememberme']], function () {
    Route::post("user-login", [AuthController::class, 'adminLogin'])->name("adminLogin");
    Route::middleware(['auth'])->group(function () {
        Route::post("admin-logout", [AuthController::class, 'adminLogout'])->name("admin.logout");


        Route::get("profile", [ProfileController::class, "index"])->name("profiles.index");
        Route::post("profile-update", [ProfileController::class, "update"])->name("profiles.update");


        Route::get("getEditUserModalContent", [UserController::class, "getEditUserModalContent"])->name("users.getEditUserModalContent");
        Route::get("getEditRoleModalContent", [RoleController::class, "getEditRoleModalContent"])->name("roles.getEditRoleModalContent");
        Route::post("addPermissionInTheGroup", [PermissionController::class, "addPermissionInTheGroup"])->name("permissions.addPermissionInTheGroup");

        // category
        Route::get("getEditCategoryModalContent", [CategoryController::class, "getEditCategoryModalContent"])->name("categories.getEditCategoryModalContent");
        Route::get("viewDescription", [CategoryController::class, "viewDescription"])->name("categories.viewDescription");
        // category


        // stores
        Route::get("getEditStoreModalContent", [StoreController::class, "getEditStoreModalContent"])->name("stores.getEditStoreModalContent");
        Route::get("apps/{slug}", [StoreController::class, "apps"])->name("stores.apps");
        // stores


        Route::get("getEditColorModalContent", [ColorController::class, "getEditColorModalContent"])->name("colors.getEditColorModalContent");


        Route::get("global-search", [SearchController::class, "index"])->name("search.global");



        Route::get('emails', [GmailController::class, 'fetchEmails'])->name("emails.index");
        Route::get('emails/{id}', [GmailController::class, 'emailDetail'])->name("emails.detail");


        Route::get('add-followup-modal-content', [CustomerFormController::class, 'addFollowupModalContent'])->name("coorporate-forms.addFollowupModalContent");
        Route::post('add-followup-to-coorpoate-form', [CustomerFormController::class, 'addFollowup'])->name("coorporate-forms.addFollowup");
        Route::get('view-followup-of-coorpoate-form', [CustomerFormController::class, 'viewFollowups'])->name("coorporate-forms.viewFollowups");
        Route::post('send-email-to-customer', [CustomerFormController::class, 'sendEmailToCustomer'])->name("coorporate-forms.sendEmailToCustomer");
        Route::get('get-change-status-modal-content', [CustomerFormController::class, 'getChangeStatusModalContent'])->name("coorporate-forms.getChangeStatusModalContent");
        Route::post('update-customer-form-status', [CustomerFormController::class, 'updateFormStatus'])->name("coorporate-forms.updateFormStatus");
        Route::get('get-form-files', [CustomerFormController::class, 'getFormFiles'])->name("coorporate-forms.getFormFiles");


        Route::get('customer-details/{id}', [CustomerController::class, 'customerDetail'])->name("customers.customerDetail");

        Route::resource("dashboard", DashboardController::class);
        Route::resource("users", UserController::class);
        Route::resource("roles", RoleController::class);
        Route::resource("permissions", PermissionController::class);
        Route::resource("coorporate-forms", CustomerFormController::class);
        Route::resource("categories", CategoryController::class);
        Route::resource("customers", CustomerController::class);
        Route::resource("colors", ColorController::class);
        Route::resource("stores", StoreController::class);
    });
});
