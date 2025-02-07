<?php

use App\Models\Log as ModelsLog;
use App\Models\MonthlyCalculation;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('getSettings')) {

    function getSettings()
    {
        $settings = Setting::where("status", 1)->orderBy("id", "desc")->first();
        return $settings;
    }
}
if (!function_exists('getSlug')) {
    function getSlug($data)
    {
        $slug = date('ymd') . '-' . Str::slug($data) . '-' . time();
        return $slug;
    }
}
if (!function_exists('generateLink')) {
    function generateLink($user)
    {
        if (isset($user->slug) && !empty($user->slug)) {
            $slug = $user->slug;
        } else {
            $slug = getSlug($user->name);
            $user->update(['slug' => $slug]);
        }
        $slug = rand(1999, 9999999999) .  "~" . $slug . "~" .  rand(1999, 9999999999);
        $encodedSlug = base64_encode($slug);
        $link = route('front.paymentform', $encodedSlug);
        return (object) ['link' => $link, 'slug' => $encodedSlug];
    }
}
if (!function_exists('getLogos')) {

    function getLogos()
    {
        $settings = getSettings();

        if ($settings) {
            return (object) [
                "logo_black" =>  asset(config('project.upload_path.project_logo_black') . $settings->logo_black),
                "logo_black_thumb" =>  asset(config('project.upload_path.project_logo_black_thumb') . $settings->logo_black),
                "logo_white" =>  asset(config('project.upload_path.project_logo') . $settings->logo_white),
                "logo_white_thumb" =>  asset(config('project.upload_path.project_logo_thumb') . $settings->logo_white),
                "fav_icon" =>  asset(config('project.upload_path.project_fav_icon') . $settings->fav_icon),
            ];
        } else {
            $whiteLogo = asset('logos/white.png');
            $favIcon = asset('logos/fav-white.png');
            return (object) [
                "logo_black" => $whiteLogo,
                "logo_black_thumb" =>  $whiteLogo,
                "logo_white" => $whiteLogo,
                "logo_white_thumb" =>  $whiteLogo,
                "fav_icon" => $favIcon,
            ];
        }
    }
}
if (!function_exists("jsonResponse")) {
    function  jsonResponse($success = null, $data  = null, $message = null, $code = null)
    {
        return response()->json([
            "success" => $success ?? null,
            "data" => $data ?? null,
            "message" => $message ?? null,
        ], $code);
    }
}

function getDefaultSizes()
{
    return config("sizes");
}

function saveLog($description = null, $model_id = null, $model_name  = null, $status = null, $data = null)
{
    return ModelsLog::create([
        "model_id" => $model_id ?? null,
        "model_name" => $model_name ?? null,
        "description" => $description ?? null,
        "status" => $status ?? null,
        "data" => json_encode($data) ?? null
    ]);
}



if (!function_exists("getUser")) {
    function  getUser($value = null, $column = null)
    {

        if (isset($column) && !empty($column) && $column != null) {
            return User::with("roles")->where($column, $value)->first();
        } else {
            if (isset($value) && !empty($value)) {
                return User::with("roles")->where("id", $value)->first();
            } else {
                return User::with("roles")->where("id", Auth::user()->id)->first();
            }
        }
    }
}



if (!function_exists("getUserName")) {
    function  getUserName($user)
    {
        $name = "";
        if (!empty($user)) {
            if (!empty($user->first_name)) {
                $name = $name . " " .  $user->first_name;
            }

            if (!empty($user->last_name))
                $name  = $name . " " . $user->last_name;
        }
        return $name ?? null;
    }
}




if (!function_exists("getRolesList")) {
    function getRolesList()
    {
        return Role::all();
    }
}



if (!function_exists("getMyRole")) {
    function getMyRole($user_id = null)
    {
        if (!isset($user_id) || empty($user_id)) {
            $user_id = Auth::user()->id;
        }

        $user = getUser($user_id);
        if (!empty($user)) {
            $roles = getUser($user_id)->roles->pluck("name")->toArray();
            if (isset($roles[0]) && !empty($roles[0])) {
                return $roles[0];
            } else {
                return "-";
            }
        }
    }
}
if (!function_exists("groupPermissions")) {

    function groupPermissions($group)
    {
        return Permission::where('group', $group)->get();
    }
}

if (!function_exists("getDisplayNamePermission")) {
    function getDisplayNamePermission()
    {
        return [
            (object) ["name" => "List", "class" => "primary"],
            (object) ["name" => "Create", "class" => "success"],
            (object) ["name" => "View", "class" => "secondary"],
            (object) ["name" => "Edit", "class" => "info"],
            (object) ["name" => "Delete", "class" => "danger"],
            (object) ["name" => "Status", "class" => "warning"],
            (object) ["name" => "Trash", "class" => "danger"],
            (object) ["name" => "Restore", "class" => "warning"],
        ];
    }
}
if (!function_exists("getDisplayNamePermissionArray")) {
    function getDisplayNamePermissionArray()
    {
        return [
            ["name" => "List", "class" => "primary"],
            ["name" => "Create", "class" => "success"],
            ["name" => "View", "class" => "secondary"],
            ["name" => "Edit", "class" => "info"],
            ["name" => "Delete", "class" => "danger"],
            ["name" => "Status", "class" => "warning"],
            ["name" => "Trash", "class" => "danger"],
            ["name" => "Restore", "class" => "warning"],
        ];
    }
}
if (!function_exists("getClassForPermission")) {

    function getClassForPermission($permission)
    {
        $collection =  collect(getDisplayNamePermissionArray());
        $foundItem = $collection->first(function ($item) use ($permission) {
            return $item['name'] === $permission;
        });
        if (!empty($foundItem)) {
            $foundItem = (object) $foundItem;
            return $foundItem->class;
        } else {
            $array  = ["success", "warning", "primary", "info"];
            $randomElement = Arr::random($array);
            return $randomElement;
        }
    }
}
if (!function_exists("singlePermission")) {
    function singlePermission($group)
    {
        return Permission::where('group', $group)->first();
    }
}


if (!function_exists("formatPermissionLabel")) {
    function formatPermissionLabel($permission)
    {
        if (!empty($permission)) {
            $permission = explode('-', $permission);
            $name = "";
            foreach ($permission as $index =>  $value) {
                if ($index != 0) {
                    $name .= $value . " ";
                }
            }
            return Str::ucfirst($name);
        } else {
            return "-";
        }
    }
}
if (!function_exists("setPermissionName")) {

    function setPermissionName($name = null, $permission = null)
    {
        $combinedString = strtolower(str_replace(' ', '-', $name) . '-' . $permission);
        return $combinedString;
    }
}

if (!function_exists("projectSettings")) {
    function  projectSettings()
    {
        return (object) [
            "title" => config("project.title"),
        ];
    }
}




if (!function_exists("getWordInitial")) {
    function getWordInitial($word, $size = null, $font_size = null, $border_radius = null)
    {
        if (!isset($size) || empty($size)) {
            $size = '50px';
        }
        if (!isset($font_size) || empty($font_size)) {
            $font_size = '16px';
        }
        if (!isset($border_radius) || empty($border_radius)) {
            $border_radius = '100%';
        }
        $wordStr = !empty($word) ? substr($word, 0, 1)  : "-";
        $initial = '<p style="width: ' . $size . ';height: ' . $size . ';border-radius:' . $border_radius . ' ;background:#' . random_color() . ';display: flex;align-items: center;justify-content: center;color:white;text-transform: uppercase;font-size: ' . $font_size . '; font-weight:600; margin:0;">' . $wordStr . '</p>';
        $html = "";
        $html .= '<div class="d-flex justify-content-start align-items-center user-name">';
        $html .=     '<div class="avatar-wrapper">';
        $html .=     ' <div class="avatar avatar-sm">';
        $html .=             $initial;
        $html .=     '  </div>';
        // $html .=         '</div><div class="d-flex flex-column">';
        // $html .=              '<span class="fw-semibold">  ' . $word . '</span>';

        $html .=     '</div>';
        $html .= '</div>';
        return $html;
    }
}




if (!function_exists("random_color")) {
    function random_color()
    {
        return random_color_part() . random_color_part() . random_color_part();
    }
}




if (!function_exists("random_color_part")) {
    function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }
}



if (!function_exists("userWithHtml")) {

    function userWithHtml($user = null, $size = null, $font_size = null, $border_radius = null)
    {
        // $auth_user = Auth::user();
        $route = "javascript:;";

        if (isset($user) && isset($user->slug) && !empty($user->slug)) {
            // if (getMyRole($auth_user->id) == config('project.roles.manager') || getMyRole($auth_user->id) == "Super Admin" || $auth_user->id == $user->id) {
            $route = "javascript:;";
            // }
            $style = 'style="margin-right:15px !important;"';
            if (isset($size) && !empty($size)) {
                $style = 'style="width:' . $size . '; height:' . $size . '; border-radius:' . $border_radius . '; margin-right:15px !important;"';
            }

            $html = "";
            $html .= '<a href="' . $route . '" target="_blank">';
            $html .= '<div class="d-flex align-items-center">';
            $html .= '<div class="symbol symbol-circle symbol-50px overflow-hidden " ' . $style . '>';
            $html .=         '<div class="symbol-label">';
            if (isset($user->image) && !empty($user->image)) {
                $html .= '<img src="' . asset(config('project.upload_path.users') . $user->image ?? null) . '" alt="' . $user->full_name . '" class="w-100" />';
            } else {
                $html .= getWordInitial($user->first_name, $size, $font_size, $border_radius);
            }
            $html .=         '</div>';
            $html .= '</div>';
            $html .= '<div class="d-flex flex-column">';
            $html .=     '<a href="' . $route . '" class="text-gray-800 text-hover-primary mb-1"  target="_blank">' . $user->full_name . '</a>';
            $html .=     '<span>' . $user->email . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</a>';

            return $html;
        } else {
            return "-";
        }
    }
}

if (!function_exists("objectWithHtml")) {

    function objectWithHtml($object_title = null, $object_image  = null, $image_path = null, $size = null, $font_size = null, $border_radius = null, $route = null)
    {
        if (!isset($route) || empty($route)) {
            $route = "javscript:;";
        }
        $style = 'style="margin-right:15px !important;"';
        if (isset($size) && !empty($size)) {
            $style = 'style="width:' . $size . '; height:' . $size . '; border-radius:' . $border_radius . '; margin-right:15px !important;"';
        }
        $html = "";
        $html .= '<a href="' . $route . '"  target="_blank">';
        $html .= '<div class="d-flex align-items-center">';
        $html .= '<div class="symbol symbol-circle symbol-50px overflow-hidden " ' . $style . '>';
        $html .=         '<div class="symbol-label">';
        if (isset($object_image) && !empty($object_image) && checkFileExists($object_image, $image_path) == true) {
            $html .= '<img src="' . asset($image_path . $object_image ?? null) . '" alt="' . $object_title . '" class="w-100" />';
        } else {
            $html .= getWordInitial($object_title, $size, $font_size, $border_radius);
        }
        $html .=         '</div>';
        $html .= '</div>';
        $html .= '<div class="d-flex flex-column">';
        $html .=     '<a href="' . $route . '" class="text-gray-800 text-hover-primary mb-1"  target="_blank" style="color: white;font-weight: bold;">' . $object_title . '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</a>';

        return $html;
    }
}

if (!function_exists("checkFileExists")) {
    function checkFileExists($fileName, $filePath)
    {
        $filePath = public_path($filePath . $fileName);
        if (File::exists($filePath)) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists("deleteFile")) {

    function deleteFile($fileName, $filePath)
    {
        $filePath = public_path($filePath . $fileName);

        if (File::exists($filePath)) {
            if (File::delete($filePath)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
if (!function_exists("uploadSingleFile")) {
    function uploadSingleFile($file = null, $folder_name = null, $prefix = null, $old_image = null)
    {
        $folder = public_path($folder_name);
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }



        $thumbDirectory = public_path($folder_name) . "thumbnails/";
        if (!is_dir($thumbDirectory)) {
            mkdir($thumbDirectory, 0755, true);
        }




        if (isset($old_image) && !empty($old_image) && file_exists($folder . "/" . $old_image)) {
            unlink($folder . "/" . $old_image);
            // if (file_exists($thumbDirectory .   $old_image)) {
            //     unlink($thumbDirectory .   $old_image); // unlink thumbnail 
            // }
        }






        $name = $prefix . "-" .  Str::random(6) . "-" . time() . "." . $file->getClientOriginalExtension();
        $file->move($folder, $name);








        // thumbnail
        // $thumbnailImageName = $name;

        // $manager = App::make('image.manager');
        // $thumbImage = $manager->read($folder    . $name);
        // $thumbImage->resize(135, 135);
        // $thumbnailSave = $thumbImage->save($thumbDirectory . $thumbnailImageName);
        // thumbnail





        return $name;
    }
}



if (!function_exists("formatDate")) {
    function formatDate($date)
    {
        $format = Carbon::parse($date);
        $format = $format->format('M d,Y');
        return $format;
    }
}

if (!function_exists("formatTime")) {
    function formatTime($time)
    {
        $format = Carbon::parse($time);
        $format = $format->format('h:i A');
        return $format;
    }
}


if (!function_exists("formatDateTime")) {
    function formatDateTime($date)
    {
        $format = Carbon::parse($date);
        $format = $format->format('M d,Y / h:i A');
        return $format;
    }
}





if (!function_exists("checkFileExtension")) {
    function checkFileExtension($fileName)
    {
        $explode = explode(".", $fileName);
        $extension = end($explode);
        $imageExtension = ['jpg', 'JPG', 'JPEG', 'jpeg', 'png', 'bmp', 'webp', 'gif'];
        if (in_array($extension, $imageExtension)) {
            return "image";
        }
        $docExtension = ['word', 'doc', 'docs', 'docx'];
        if (in_array($extension, $docExtension)) {
            return "doc";
        }
        $pdfExtension = ['pdf'];
        if (in_array($extension, $pdfExtension)) {
            return "pdf";
        }
        $excelExtension = ['xls', 'xlxs'];
        if (in_array($extension, $excelExtension)) {
            return "excel";
        }
    }
}

if (!function_exists("formatMonthYear")) {
    function formatMonthYear($month_year)
    {

        $date = Carbon::createFromFormat('m/Y', $month_year);
        $formattedDate = $date->format('F Y');
        return $formattedDate;
    }
}
if (!function_exists("getIndividualAmount")) {
    function getIndividualAmount($amount, $user_id, $paid_id)
    {
        if ($user_id != $paid_id) {
            return  (object) [
                "transaction_type" => 2, //  payable
                "amount" => -1 * $amount,
            ];
        } else {
            return (object) [
                "transaction_type" => 1, // receivable
                "amount" => $amount,
            ];
        }
    }
}


if (!function_exists("calculateMonthlyStats")) {
    function calculateMonthlyStats($report_id)
    {
        $report = MonthlyCalculation::where("id", $report_id)->first();
        if (isset($report) && !empty($report)) {
            $totalSum = MonthlyCalculation::where("user_id", getUser()->id)->where("month_year", $report->month_year)->orderBy("id", "asc")->sum("amount");
            $receivable = MonthlyCalculation::where("user_id", getUser()->id)->where("month_year", $report->month_year)->where("transaction_type", 1)->orderBy("id", "asc")->sum("amount");
            $payable = MonthlyCalculation::where("user_id", getUser()->id)->where("month_year", $report->month_year)->where("transaction_type", 2)->orderBy("id", "asc")->sum("amount");
            $chekcPayNegative = checkValueInNegative($payable);
            $chekcRecNegative = checkValueInNegative($receivable);
            $totalCalculation = $receivable + $payable;
            return (object) [
                "receivable" => number_format($receivable, 2) ?? 0,
                "payable" => number_format($payable, 2) ?? 0,
                "totalCalculation" => number_format($totalCalculation),
            ];
        } else {
            return (object) [
                "receivable" => 0,
                "payable" => 0,
                "totalCalculation" => 0,
            ];
        }
    }
}



// Backup
// if (!function_exists("calculateMonthlyStats")) {
//     function calculateMonthlyStats($report_id)
//     {
//         $report = MonthlyCalculation::where("id", $report_id)->first();
//         if (isset($report) && !empty($report)) {
//             $receivable = MonthlyCalculation::where("user_id", getUser()->id)->where("month_year", $report->month_year)->where("transaction_type", 1)->orderBy("id", "asc")->sum("amount");
//             $payable = MonthlyCalculation::where("user_id", getUser()->id)->where("month_year", $report->month_year)->where("transaction_type", 2)->orderBy("id", "asc")->sum("amount");
//             $chekcPayNegative = checkValueInNegative($payable);
//             $chekcRecNegative = checkValueInNegative($receivable);
//             $totalCalculation = $receivable + $payable;
//             return (object) [
//                 "receivable" => number_format($receivable, 2) ?? 0,
//                 "payable" => number_format($payable, 2) ?? 0,
//                 "totalCalculation" => number_format($totalCalculation),
//             ];
//         } else {
//             return (object) [
//                 "receivable" => 0,
//                 "payable" => 0,
//                 "totalCalculation" => 0,
//             ];
//         }
//     }
// }


if (!function_exists("checkValueInNegative")) {
    function checkValueInNegative($value)
    {
        if ($value < 0) {
            return true;
        } else {
            return false;
        }
    }
}



if (!function_exists("monthYearSeperator")) {
    function monthYearSeperator($month_year) {
        $explode = explode("/" , $month_year);
        return $explode;
    }
}
