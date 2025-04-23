<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MonthlyReportController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PaymentFormController;
use App\Models\PaymentForm;
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
        Route::get("profile-generate-link", [ProfileController::class, "generateLink"])->name("profiles.generateLink");


        Route::get("getEditUserModalContent", [UserController::class, "getEditUserModalContent"])->name("users.getEditUserModalContent");
        Route::get("getLink", [UserController::class, "getLink"])->name("users.getLink");
        Route::get("getEditRoleModalContent", [RoleController::class, "getEditRoleModalContent"])->name("roles.getEditRoleModalContent");
        Route::post("addPermissionInTheGroup", [PermissionController::class, "addPermissionInTheGroup"])->name("permissions.addPermissionInTheGroup");


        // settings
        Route::get("settings", [SettingsController::class, 'index'])->name("settings.index");
        Route::post("settings-update", [SettingsController::class, 'update'])->name("settings.update");
        // settings

        Route::get("dashboard-data", [DashboardController::class, 'dashboardData'])->name("dashboard.graphData");

        Route::get("entries/{month}/{year}/{user_id}", [PaymentFormController::class, 'detail'])->name("entries.details");
        Route::get("entries-json/{month}/{year}/{user_id}", [PaymentFormController::class, 'json'])->name("entries.json");

        Route::get("get-user-report-status", [PaymentFormController::class, 'getUserReportStatus'])->name("entries.getUserReportStatus");


        Route::get("monthly-reports-detail/{month}/{year}", [MonthlyReportController::class, 'detail'])->name("monthly-reports.detail");


        Route::get("test-petty", function () {
            $records = PaymentForm::whereYear('date', "2025")->whereMonth('date', "02")
                ->where(function ($query) {
                    return $query->whereJsonContains("divided_in", "4")->orWhere("paid_by", "4");
                })
                ->orderBy("id", "desc")->get();
            $data['records_count'] = $records->count();
            $data['records'] = $records;
            return $data;
        });


        Route::resource("dashboard", DashboardController::class);
        Route::resource("users", UserController::class);
        Route::resource("roles", RoleController::class);
        Route::resource("permissions", PermissionController::class);
        Route::resource("entries", PaymentFormController::class);
        Route::resource("monthly-reports", MonthlyReportController::class);
    });
});
