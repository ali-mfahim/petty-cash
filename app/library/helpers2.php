<?php

use App\Models\Collection;
use App\Models\CustomerForm;
use App\Models\CustomerFormStatus;
use App\Models\Followup;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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


if (!function_exists("getUser")) {
    function  getUser($user_id = null)
    {
        if (isset($user_id) && !empty($user_id)) {
            return User::with("roles")->where("id", $user_id)->first();
        } else {
            return User::with("roles")->where("id", Auth::user()->id)->first();
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






        $name = $prefix . "-" .  Str::random(6) . "-" . request()->ip()  . "-" . time() . "." . $file->getClientOriginalExtension();
        $file->move($folder, $name);








        // thumbnail
        $thumbnailImageName = $name;

        $manager = App::make('image.manager');
        $thumbImage = $manager->read($folder    . $name);
        $thumbImage->resize(135, 135);
        $thumbnailSave = $thumbImage->save($thumbDirectory . $thumbnailImageName);
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



// if (!function_exists("uploadSingleFile")) {
//     function uploadSingleFile($file = null, $folder_name = null, $prefix = null, $old_image = null)
//     {
//         $folder = public_path($folder_name);
//         if (isset($old_image) && !empty($old_image) && file_exists($folder . "/" . $old_image)) {
//             unlink($folder . "/" . $old_image);
//         }

//         if (!is_dir($folder)) {
//             mkdir($folder, 0755, true);
//         }
//         $name = $prefix . "-" .  Str::random(6) . time() . "." . $file->getClientOriginalExtension();
//         $file->move($folder, $name);
//         return $name;
//     }
// }


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

if (!function_exists("saveFollowup")) {
    function saveFollowup($array)
    {
        $followup = Followup::create([
            "title" => $array['title'] ?? null,
            "causer_id" => $array['causer_id'] ?? null,
            "model_id" => $array['model_id'] ?? null,
            "model_name" => $array['model_name'] ?? null,
            "remarks" => $array['remarks'] ?? null,
            "model_status" => $array['status'],
            "type" => $array['type'],
            "data" => isset($array['data']) && !empty($array['data']) ? json_encode($array['data']) : null,
        ]);
        return $followup;
    }
}


// if (!function_exists("getFormStatus")) {
//     function getFormStatus($followup)
//     {

//         $status =  CustomerFormStatus::where("id", $followup->model_status)->first();
//         if (!empty($status)) {
//             return $status;
//         }
//     }
// }

if (!function_exists("getCollectionProducts")) {
    function getCollectionProducts($collectionId)
    {
        $collection = Collection::where("id", $collectionId)->first();
        $store = getStoreDetails($collection->import_store_id, "any");
        $products = [];
        $cursor = null;

        do {
            $response = fetchCollectionProducts($collection->gid, $cursor, $store);
            Log::info("RESPONSE: " . json_encode($response));
            $collection = $response['data']['collection'];
            $products = array_merge($products, $collection['products']['edges']);

            $cursor = end($collection['products']['edges'])['cursor'];
            $hasNextPage = $collection['products']['pageInfo']['hasNextPage'];
        } while ($hasNextPage);

        return $products;
    }
}

if (!function_exists("fetchCollectionProducts")) {
    function fetchCollectionProducts($collectionId = null, $cursor = null, $store = null)
    {
        $client = new Client();
        $accessToken = $store->access_token;
        $endpoint = $store->base_url . $store->api_version . "/graphql.json";
        $query = <<<'GRAPHQL'
        query ($collectionId: ID!, $cursor: String) {
          collection(id: $collectionId) {
            title
            products(first: 250, after: $cursor) {
              edges {
                cursor
                node {
                  id
                  title
                  handle
                }
              }
              pageInfo {
                hasNextPage
              }
            }
          }
        }
        GRAPHQL;

        $variables = [
            'collectionId' => $collectionId,
            'cursor' => $cursor,
        ];

        $response = $client->post($endpoint, [
            'headers' => [
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'query' => $query,
                'variables' => $variables,
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            throw new \Exception('Error fetching collection products: ' . $response->getBody()->getContents());
        }
    }
}


function getCollection($id) {
    return Collection::where("id" , $id)->first();
}