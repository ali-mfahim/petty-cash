<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

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
            if (isset($request->white_logo) && !empty($request->white_logo)) {
                $logo_white = uploadSingleFile($request->white_logo, config("project.upload_path.store_logo"), "store", $settings->logo_white);
            } else {
                $logo_white = $settings->logo_white;
            }
            if (isset($request->black_logo) && !empty($request->black_logo)) {
                $logo_black = uploadSingleFile($request->black_logo, config("project.upload_path.store_logo_black"), "store", $settings->logo_black);
            } else {
                $logo_black = $settings->logo_black;
            }
            if (isset($request->fav_icon) && !empty($request->fav_icon)) {
                $fav_icon = uploadSingleFile($request->fav_icon, config("project.upload_path.store_fav_icon"), "store", $settings->fav_icon);
            } else {
                $fav_icon = $settings->fav_icon;
            }
            $update = $settings->update([
                "support_email" => $request->support_email ?? null,
                "cron_enable" => $request->cron_enable ?? null,
                "logo_black" => $logo_black ?? null,
                "logo_white" => $logo_white ?? null,
                "fav_icon" => $fav_icon ?? null,
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
                "logo_black" => $logo_black ?? null,
                "logo_white" => $logo_white ?? null,
                "fav_icon" => $fav_icon ?? null,
            ]);
            if ($create->id) {
                return redirect()->route("settings.index")->with("success", "Settings Created");
            } else {
                return redirect()->route("settings.index")->with("false", "Failed to create settings");
            }
        }
    }

 
}
