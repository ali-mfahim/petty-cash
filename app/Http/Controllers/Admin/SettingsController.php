<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = "Settings";
        $data['settings'] = Setting::orderBy("id", "desc")->first();
        return view("admin.pages.settings.index", $data);
    }



    public function update(Request $request)
    {
        $settings = Setting::orderBy("id", "desc")->first();
        if (!empty($settings)) {
            $update = $settings->update([
                "support_email" => $request->support_email ?? null,
                "cron_enable" => $request->cron_enable ?? null,
            ]);
            if ($update > 0) {
                return redirect()->route("settings.index")->with("success", "Settings Updated");
            } else {
                return redirect()->route("settings.index")->with("false", "Failed to update settings");
            }
        } else {
            $create = Setting::create([
                "support_email" => $request->support_email ?? null,
                "cron_enable" => $request->cron_enable ?? null,
            ]);
            if ($create->id) {
                return redirect()->route("settings.index")->with("success", "Settings Created");
            } else {
                return redirect()->route("settings.index")->with("false", "Failed to create settings");
            }
        }
    }
}
