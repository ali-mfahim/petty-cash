<?php

use App\Models\Collection;
use App\Models\CollectionProduct;
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


function getCollection($id)
{
    return Collection::where("id", $id)->with('products', 'matchedProducts')->first();
}
function getCollectionProductIds($id)
{
    try {
        $collection = getCollection($id);
        $productIds = $collection->matchedProducts->pluck("new_gid")->toArray();
        return jsonResponse(true, $productIds, "Matched Product IDs", 200);
    } catch (Exception $e) {
        return jsonResponse(false, null, $e->getMessage(), 200);
    }
}
if (!function_exists("checkCollectionExistsByHandle")) {
    function checkCollectionExistsByHandle($collectionHandle, $store_id)
    {
        $store = getStoreDetails($store_id, "any");
        $accessToken = $store->access_token;
        $endpoint = $store->base_url . $store->api_version . "/graphql.json";

        // GraphQL query to fetch collection by handle
        $query = '
        query CheckCollection($handle: String!) {
            collectionByHandle(handle: $handle) {
                id
                title
                handle
            }
        }';

        // Make the request to Shopify's GraphQL API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $accessToken
        ])->post($endpoint, [
            'query' => $query,
            'variables' => [
                'handle' => $collectionHandle,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Check if the collection exists by handle
            if (isset($data['data']['collectionByHandle']) && $data['data']['collectionByHandle'] !== null) {
                $collection = $data['data']['collectionByHandle'];
                return jsonResponse(true, $collection, 'Collection exists with handle: ' . $collection['handle'], 200);
            } else {
                return jsonResponse(false, 'Collection does not exist with the provided handle.', 'Does not exist', 200);
            }
        } else {
            return jsonResponse(false,  $response->json(), 'Error while fetching data from Shopify.', 200);
            // Error fetching data from Shopify
        }
    }
}

if (!function_exists("createUniqueCollection")) {
    function createUniqueCollection($collection)
    {
        $store_id = $collection->export_store_id;
        $store = getStoreDetails($store_id, "any");
        $accessToken = $store->access_token;
        $endpoint = $store->base_url . $store->api_version . "/graphql.json";
        $uniqueKeyword = "-api";
        $collectionTitle = $collection->title . $uniqueKeyword;
        $collectionHandle = $collection->handle . $uniqueKeyword;

        $raw_data = $collection['raw_data'];
        $raw_data = json_decode($raw_data, true);
        $raw_data = (object) $raw_data;
        $metafields = $raw_data->metafields ?? null;
        $ruleSet = $raw_data->ruleSet ?? null;
        if (isset($metafields) && !empty($metafields) && count($metafields) > 0) {
            $metafieldInputs = array_map(function ($edge) {
                $metafield = $edge['node'];
                if($metafield['key'] == "collection_announcement") {
                    $metafield_type  = "collection_announcement"; 
                }
                if($metafield['key'] == "banner_img_desktop") {
                    $metafield_type  = "file"; 
                }
                if($metafield['key'] == "banner_img_desktop") {
                    $metafield_type  = "file"; 
                }
                return [
                    'namespace' => $metafield['namespace']  ?? null,
                    'key' => $metafield['key']   ?? null,
                    'value' => $metafield['value']   ?? null,
                    'type' => $metafield_type   ?? null
                ];
            }, $metafields['edges']);
        }


        if (isset($ruleSet) && !empty($ruleSet) && count($ruleSet) > 0) {
            $ruleSetInputs = [
                'appliedDisjunctively' => $ruleSet['appliedDisjunctively'],
                'rules' => array_map(function ($rule) {
                    return [
                        'column' => $rule['column']   ?? null,
                        'relation' => $rule['relation']   ?? null,
                        'condition' => $rule['condition']   ?? null
                    ];
                }, $ruleSet['rules'])
            ];
        }
        $mutation = '
        mutation CreateCollection($input: CollectionInput!) {
            collectionCreate(input: $input) {
                collection { 
                    id
                    title
                    handle
                    description
                    image {
                        src
                        altText
                    }
                    updatedAt
                    sortOrder
                    metafields(first: 250) {
                        edges {
                            node {
                                id
                                namespace
                                key
                                value
                                type
                                description
                                createdAt
                                updatedAt
                                ownerType
                            }
                        }
                    }
                    ruleSet {
                        rules {
                            column
                            relation
                            condition
                        }
                        appliedDisjunctively
                    }
                } 
                userErrors {
                    field
                    message
                }
                
            }
        }';
        $variables = [];
        $variables['input']['title'] = $collectionTitle ?? null;
        $variables['input']['handle'] = $collectionHandle ?? null;
        $variables['input']['descriptionHtml'] = $collection->description ?? null;
        $variables['input']['sortOrder'] = $collection->sort_order ?? null;
        if (isset($metafieldInputs) && !empty($metafieldInputs)) {
            $variables['input']['metafields'] = $metafieldInputs ?? null;
        }
        if (isset($ruleSetInputs) && !empty($ruleSetInputs)) {
            $variables['input']['ruleSet'] = $ruleSetInputs ?? null;
        }
        // Prepare the input data for the collection creation
        // $variables2 = [
        //     'input' => [
        //         'title' => $collectionTitle,
        //         'handle' => $collectionHandle,
        //         'descriptionHtml' => $collection->description,
        //         'sortOrder' => $collection->sort_order,
        //         'metafields' => $metafieldInputs,
        //         'ruleSet' => $ruleSetInputs,
        //     ]
        // ];
        // Log::info("VARIABLES1 = " . json_encode($variables));
        // Log::info("VARIABLES2 = " . json_encode($variables2));
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $accessToken
            ])->post($endpoint, [
                'query' => $mutation,
                'variables' => $variables
            ]);
            $data = $response->json();
            if (isset($data['data']['collectionCreate']['userErrors']) && !empty($data['data']['collectionCreate']['userErrors']) && count($data['data']['collectionCreate']['userErrors'])) {
                $message = $data['data']['collectionCreate']['userErrors'][0]['message'] ?? '-';
                saveLog("Error while creating collection: " . $message, $collection->id, "Collection", 2, $data['data']['collectionCreate']['userErrors']);
                return jsonResponse(false, '', $message, 200);
            }
            // Check for a successful response
            if ($response->successful()) {
                // Process the response data
                return jsonResponse(true, $data, 'Request successful', 200);
            } else {
                // Handle non-2xx responses
                return jsonResponse(false, [], 'Request failed: ' . $response->body(), $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Handle HTTP request exceptions
            $message  = 'HTTP Request Exception: ' . $e->getMessage() . "-line" . $e->getLine() . '-file: ' . $e->getFile();
            saveLog($message, '', "Collection", 2, []);
            return jsonResponse(false, [], $message, 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            $message  = 'General Exception: ' . $e->getMessage() . "-line" . $e->getLine() . '-file: ' . $e->getFile();
            saveLog($message, '', "Collection", 2, []);
            return jsonResponse(false, [], $message, 500);
        }
    }
}




if (!function_exists("getShopifyProductByHandle")) {
    function getShopifyProductByHandle($product)
    {
        try {
            $store = getStoreDetails($product->collection->export_store_id, "any");
            $accessToken = $store->access_token;
            $url = $store->base_url . $store->api_version . "/graphql.json";
            $query = '
                query GetProductByHandle($handle: String!) {
                    productByHandle(handle: $handle) {
                        id
                        title
                        handle
                    } 
                    
                }
            ';
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json'
            ])->post($url, [
                'query' => $query,
                'variables' => [
                    'handle' => $product->handle,
                ],
            ]);

            $data = $response->json();
            if (isset($data['data']['productByHandle']) && !empty($data['data']['productByHandle']) &&  $data['data']['productByHandle'] != null) {
                return jsonResponse(true, $data, 'Product Found', 200);
            } else {
                return jsonResponse(false, [], "Product NOt Found", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }
}

if (!function_exists("addProductsToCollection")) {
    function addProductsToCollection($collection)
    {
        $store_id = $collection->export_store_id;
        $store = getStoreDetails($store_id, "any");
        $accessToken = $store->access_token;
        $endpoint = $store->base_url . $store->api_version . "/graphql.json";

        $collectionId = $collection->new_gid;

        while (true) {
            // Fetch up to 250 product IDs with status 0
            $products = CollectionProduct::where("collection_id", $collection->id)
                ->where("status", 0)
                ->whereNotNull("new_gid")
                ->limit(250)
                ->get();

            if ($products->isEmpty()) {
                break;
            }

            $productIds = $products->pluck("new_gid")->toArray();
            saveLog("PRODUCTS IN PROCESS: " . json_encode(count($productIds)), "", "", 1, "");
            foreach ($products as $i => $v) {
                CollectionProduct::where('id', $v->id)->update(['status' => 1]);
            }

            $mutation = '
                mutation AddProductsToCollection($id: ID!, $productIds: [ID!]!) {
                    collectionAddProducts(id: $id, productIds: $productIds) {
                        collection {
                            id
                            title
                            handle
                        }
                        userErrors {
                            field
                            message
                        }
                    }
            }';

            $variables = [
                'id' => $collectionId,
                'productIds' => $productIds
            ];


            saveLog("Product IDS to be added in collection: ", $collectionId, "Collection", 2, json_encode($productIds));

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $accessToken
                ])->post($endpoint, [
                    'query' => $mutation,
                    'variables' => $variables
                ]);

                $responseBody = $response->json();

                if (isset($responseBody['data']['collectionAddProducts']['collection']) && !empty($responseBody['data']['collectionAddProducts']['collection'])) {
                    foreach ($products as $i => $v) {

                        $cProduct = CollectionProduct::where('id', $v->id)->first();
                        if (isset($cProduct) && !empty($cProduct)) {
                            $cProduct->update([
                                "status" => 2,
                            ]);
                        }
                    }
                    $collectionData = $responseBody['data']['collectionAddProducts']['collection'];
                    // return jsonResponse(true , json_encode($responseBody), "Products Exported" ,200 );

                }


                if (isset($responseBody['errors'])) {
                    foreach ($responseBody['errors'] as $error) {
                        $message = "Error while adding products to collection: " . $error['message'] . "\n";
                        saveLog($message, $collectionId, "Collection", 2);
                    }
                    // return jsonResponse(false, [], 'GraphQL Error', 500);
                } elseif (isset($responseBody['data']['collectionAddProducts']['userErrors']) && !empty($responseBody['data']['collectionAddProducts']['userErrors'])) {
                    $userErrors = $responseBody['data']['collectionAddProducts']['userErrors'];
                    foreach ($userErrors as $userError) {
                        $message = "User Error: " . $userError['message'] . " on field " . implode(', ', $userError['field']);
                        saveLog($message, $collectionId, "Collection", 2);
                    }
                    // return jsonResponse(false, [], 'User Error', 500);
                }
            } catch (\Illuminate\Http\Client\RequestException $e) {
                $message = 'HTTP Request Exception: ' . $e->getMessage() . '-file: ' . $e->getFile() . "-line: " . $e->getLine();
                saveLog($message, "", $message, 2);
                // return jsonResponse(false, [], 'HTTP Request Exception: ' . $e->getMessage() . '-file: ' . $e->getFile() . "-line: " . $e->getLine(), 500);
            } catch (\Exception $e) {
                $message = 'HTTP Request Exception: ' . $e->getMessage() . '-file: ' . $e->getFile() . "-line: " . $e->getLine();
                saveLog($message, "", $message, 2);
                // return jsonResponse(false, [], 'General Exception: ' . $e->getMessage() . '-file: '. $e->getFile() . "-line: " . $e->getLine(), 500);
            }
        }

        // Return success when all products are processed
        return jsonResponse(true, [], 'All products processed successfully', 200);
    }
}


if (!function_exists("getCollectionByIds")) {

    function getCollectionByIds($ids)
    {

        $collections = Collection::whereIn("id", $ids)->get();
        return $collections;
    }
}


if (!function_exists("updateCollectionMetaFieldsById")) {
    function updateCollectionMetaFieldsById($collection, $data)
    {
        $client = new \GuzzleHttp\Client();

        // Assuming you have a function to get store details
        $store = getStoreDetails($collection->export_store_id, "any");
        $accessToken = $store->access_token;
        $url = $store->base_url . $store->api_version . "/graphql.json";

        // Extract necessary details from the fetched data
        $collectionData = $data['data']['collection'];
        $metafields = $collectionData['metafields']['edges'];
        $ruleSet = $collectionData['ruleSet'];

        // Prepare metafields input for the mutation
        $metafieldInputs = array_map(function ($metafield) {
            return [
                'namespace' => $metafield['node']['namespace'],
                'key' => $metafield['node']['key'],
                'value' => $metafield['node']['value'],
                'type' => $metafield['node']['type']
            ];
        }, $metafields);

        // Prepare the GraphQL mutation query for updating metafields
        $mutation = '
            mutation updateCollection($id: ID!, $metafields: [MetafieldInput!]!) {
                collectionUpdate(input: {
                    id: $id,
                    metafields: $metafields
                }) {
                    collection {
                        id
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }';
        $variables = [
            'id' => $collection->new_gid,
            'metafields' => $metafieldInputs,
        ];

        // Execute the mutation for updating metafields
        try {
            $response = $client->post($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'query' => $mutation,
                    'variables' => $variables,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            dd($body);
            if (isset($body['errors'])) {
                // Handle errors
                throw new \Exception('Failed to update collection: ' . json_encode($body['errors']));
            }

            if (!empty($body['data']['collectionUpdate']['userErrors'])) {
                dd($body['data']['collectionUpdate']['userErrors']);
                // Handle user errors
                throw new \Exception('Failed to update collection: ' . json_encode($body['data']['collectionUpdate']['userErrors']));
            }

            return $body['data']['collectionUpdate'] ?? null;
        } catch (\Exception $e) {
            // Handle exceptions
            dd($e->getMessage());
            throw new \Exception('Failed to update collection: ' . $e->getMessage());
        }
    }
}


if (!function_exists("updateCollectionRuleSetById")) {
    function updateCollectionRuleSetById($collection, $data)
    {
        $client = new \GuzzleHttp\Client();

        // Assuming you have a function to get store details
        $store = getStoreDetails($collection->export_store_id, "any");
        $accessToken = $store->access_token;
        $url = $store->base_url . $store->api_version . "/graphql.json";

        // Extract necessary details from the fetched data
        $collectionData = $data['data']['collection'];
        $ruleSet = $collectionData['ruleSet'];

        // Prepare rules input for the mutation
        $rulesInput = [
            'rules' => array_map(function ($rule) {
                return [
                    'column' => $rule['column'],
                    'relation' => $rule['relation'],
                    'condition' => $rule['condition']
                ];
            }, $ruleSet['rules']),
            'appliedDisjunctively' => $ruleSet['appliedDisjunctively']
        ];

        // Prepare the GraphQL mutation query for updating the ruleset
        $mutation = '
            mutation updateCollectionRules($input: CollectionInput!) {
                collectionUpdate(input: $input) {
                    collection {
                        id
                        ruleSet {
                            rules {
                                column
                                relation
                                condition
                            }
                            appliedDisjunctively
                        }
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }';
        $variables = [
            'input' => [
                'id' => $collection->new_gid,
                'ruleSet' => $rulesInput
            ]
        ];

        // Execute the mutation for updating the ruleset
        try {
            $response = $client->post($url, [
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'query' => $mutation,
                    'variables' => $variables,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            dd($body);
            if (isset($body['errors'])) {
                // Handle errors
                throw new \Exception('Failed to update collection: ' . json_encode($body['errors']));
            }

            if (!empty($body['data']['collectionUpdate']['userErrors'])) {
                dd($body['data']['collectionUpdate']['userErrors']);
                // Handle user errors
                throw new \Exception('Failed to update collection: ' . json_encode($body['data']['collectionUpdate']['userErrors']));
            }

            return $body['data']['collectionUpdate'] ?? null;
        } catch (\Exception $e) {
            // Handle exceptions
            dd($e->getMessage());
            throw new \Exception('Failed to update collection: ' . $e->getMessage());
        }
    }
}
if (!function_exists("getCollectionFromStore")) {
    function getCollectionFromStore($store, $collection)
    {
        $client = new Client();
        $accessToken = $store->access_token;
        $url = $store->base_url . $store->api_version . "/graphql.json";
        $query = '
            query getCollectionById($id: ID!) {
                collection(id: $id) {
                    id
                    title
                    handle
                    descriptionHtml
                    updatedAt
                    sortOrder
                    templateSuffix
                    image {
                        src
                        altText
                    }
                    metafields(first: 250) {
                        edges {
                            node {
                            id
                            namespace
                            key
                            value
                            type
                            description
                            createdAt
                            updatedAt
                            ownerType
                            }
                        }
                    }
                    ruleSet {
                        rules {
                            column
                            relation
                            condition
                        }
                        appliedDisjunctively
                    }
                  
                }
            }';
        $response = $client->post($url, [
            'headers' => [
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'query' => $query,
                'variables' => [
                    'id' => $collection->gid,
                ],
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        return [
            "store" => $store,
            "collection" => $body,
        ];
        $updateCollection = updateCollectionRuleSetById($collection, $body);
        if (isset($body['errors'])) {
            // Handle errors
            throw new \Exception('Failed to fetch collection: ' . json_encode($body['errors']));
        }

        return $body['data']['collection'] ?? null;
    }
}
