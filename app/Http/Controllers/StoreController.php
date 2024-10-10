<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreApp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Colors\Rgb\Channels\Red;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("store-list");
        $data['title'] = "Store List";
        if ($request->ajax()) {
            $categories = Store::orderBy("id", "desc")->select("*");
            return DataTables::of($categories)
                ->addIndexColumn()

                ->addColumn("created_by", function ($model) {
                    $column = "";
                    $creator = isset($model->creator) && !empty($model->creator) ? getUserName($model->creator) : null;
                    $column .= '<a href="javascript:;">';
                    $column .= '<span class="badge bg-primary view-description-btn" data-user-id="' . $model->created_by . '" >' .  $creator . '</span>';
                    $column .= '</a>';
                    return $column;
                })
                ->addColumn("domain", function ($model) {
                    $column = "";
                    $column .= $model->domain ?? "-";
                    return $column;
                })
                ->addColumn("api_url", function ($model) {
                    $column = "";
                    $column .= $model->api_url ?? "-";
                    return $column;
                })

                ->addColumn("base_url", function ($model) {
                    $column = "";
                    $column .= $model->base_url ?? "-";
                    return $column;
                })
                ->addColumn("logo", function ($model) {
                    if (checkFileExists($model->image, config("project.upload_path.store_logo_thumb"))) {
                        $route = asset(config('project.upload_path.store_logo_thumb') . $model->logo);
                    } else {
                        $route = "javascript:;";
                    }

                    $html = objectWithHtml($model->name, $model->logo ?? null, config('project.upload_path.store_logo_thumb'), "70px", "40px", "100%", $route);
                    return $html;
                })
                ->addColumn("status", function ($model) {
                    switch ($model->status) {
                        case "1":
                            return '<span class="badge  bg-success" >Active</span>';
                        case "2":
                            return '<span class="badge  bg-danger" >Disabled</span>';
                    }
                })
                ->addColumn('actions', function ($model) {
                    $record = $model;
                    return view("admin.pages.stores.components.action", compact("record"));
                })
                ->rawColumns(['created_by', 'logo', 'status', 'actions'])
                ->make(true);
        }
        return view("admin.pages.stores.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("store-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->authorize("store-create");
        $request->validate([
            "name" => "required|max:255|unique:stores,name,NULL,id,deleted_at,NULL",
            "domain" => "required|max:255|unique:stores,domain,NULL,id,deleted_at,NULL",
            "base_url" => "required|max:255|unique:stores,base_url,NULL,id,deleted_at,NULL",
        ]);
        try {
            if (isset($request->logo) && !empty($request->logo)) {
                $logo = uploadSingleFile($request->logo, config("project.upload_path.store_logo"), "store");
            }
            $slug = Str::slug($request->name);
            $create = Store::create([
                "created_by" => getUser()->id ?? null,
                "name" => $request->name ?? null,
                "slug" => $slug ?? null,
                "domain" => $request->domain ?? null,
                "base_url" => $request->base_url ?? null,
                "api_url" => $request->api_url ?? null,
                "logo" => $logo ?? null,
                "status" => $request->status ?? null,
            ]);
            if ($create->id) {
                return jsonResponse(true, $create, "Store Created Successfuly!", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage() . ' on file : ' . $e->getFile() . " at line: " . $e->getLine(), 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("store-edit");
        $request->validate([
            "name" => [
                "required",
                Rule::unique('stores')->whereNull('deleted_at')->ignore($id),
            ],
            "domain" => [
                "required",
                Rule::unique('stores')->whereNull('deleted_at')->ignore($id),
            ],
            "base_url" => [
                "required",
                Rule::unique('stores')->whereNull('deleted_at')->ignore($id),
            ],
        ]);


        try {
            $store = Store::where("id", $id)->first();
            if (isset($request->logo) && !empty($request->logo)) {
                $logo = uploadSingleFile($request->logo, config("project.upload_path.store_logo"), "store", $store->logo);
            } else {
                $logo = $store->logo;
            }
            $slug = Str::slug($request->name);
            $update =  $store->update([
                "name" => $request->name ?? null,
                "slug" => $slug ?? null,
                "domain" => $request->domain ?? null,
                "base_url" => $request->base_url ?? null,
                "api_url" => $request->api_url ?? null,
                "logo" => $logo ?? null,
                "status" => $request->status ?? null,
            ]);
            if ($update > 0) {
                return jsonResponse(true, [], "Store has been updated successfuly!", 200);
            } else {
                return jsonResponse(false, [], "Failed to update store!", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize("store-delete");
        try {
            $category = Store::where("id", $id)->first();
            if (!empty($category)) {
                $delete = $category->delete();
                if ($delete > 0) {
                    return jsonResponse(true, [], "Store has been deleted successfuly!", 200);
                } else {
                    return jsonResponse(false, [], "Failed to delete the Store", 200);
                }
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }

    public function getEditStoreModalContent(Request $request)
    {
        $this->authorize("store-edit");
        if (isset($request->store_id) && !empty($request->store_id)) {
            $store = Store::where("id", $request->store_id)->first();
            if (!empty($store)) {
                $view = view("admin.pages.stores.components.editStoreModalContent", compact("store"))->render();
                $data = [
                    "view" => $view,
                    "store" => $store,
                ];
                return jsonResponse(true, $data, "Store Edit", 200);
            } else {
                return jsonResponse(false, [], "Failed to get store", 422);
            }
        } else {
            return jsonResponse(false, [], "Store ID Not Found", 422);
        }
    }


    // apps department
    public function apps(Request $request, $slug)
    {
        $data['store'] = Store::where("slug", $slug)->first();
        if (!empty($data['store'])) {
            $data['title'] = "Apps of " . $data['store']->name ?? '';
            if ($request->ajax()) {
                $data['apps'] = StoreApp::where("store_id", $data['store']->id)->orderBy("id", "desc")->select("*");
                if (isset($data['apps']) && !empty($data['apps'])) {
                    return DataTables::of($data['apps'])
                        ->addIndexColumn()

                        ->addColumn("created_by", function ($model) {
                            $column = "";
                            $creator = isset($model->creator) && !empty($model->creator) ? getUserName($model->creator) : null;
                            $column .= '<a href="javascript:;">';
                            $column .= '<span class="badge bg-primary " data-user-id="' . $model->created_by . '" >' .  $creator . '</span>';
                            $column .= '</a>';
                            return $column;
                        })
                        ->addColumn("app_name", function ($model) {
                            $column = "";
                            $column .= $model->app_name ?? "-";
                            return $column;
                        })
                        ->addColumn("app_key", function ($model) {
                            $column = "";
                            $column .= '<a href="javascript:;">';
                            $column .= '<span class="badge bg-primary reveal app_key"   data-value="' . $model->app_key . '" >Reveal</span>';
                            $column .= '</a>';
                            return $column;
                        })

                        ->addColumn("app_secret", function ($model) {
                            $column = "";
                            $column .= '<a href="javascript:;">';
                            $column .= '<span class="badge bg-primary reveal app_secret" data-value="' . $model->app_secret . '" >Reveal</span>';
                            $column .= '</a>';
                            return $column;
                        })

                        ->addColumn("access_token", function ($model) {
                            $column = "";
                            $column .= '<a href="javascript:;">';
                            $column .= '<span class="badge bg-primary reveal access_token" data-value="' . $model->access_token . '" >Reveal</span>';
                            $column .= '</a>';
                            return $column;
                        })
                        ->addColumn("api_version", function ($model) {
                            $column = "";
                            $column .= $model->api_version ?? "-";
                            return $column;
                        })
                        ->addColumn("status", function ($model) {
                            $checked = "";
                            switch ($model->status) {
                                case "1":
                                    $checked = "checked";
                            }
                            $column = "";
                            // $column .= '<label class="form-check-label mb-50" for="status_switch_'.$model->id.'"></label>';
                            $column .= '<div class="form-check form-switch form-check-primary">';
                            $column .=          '<input type="checkbox" class="form-check-input status_switch_btn"  data-app-id="' . $model->id . '" value="' . $model->status . '"   id="status_switch_' . $model->id . '" ' . $checked . ' />';
                            $column .=          '<label class="form-check-label" for="status_switch_' . $model->id . '">';
                            $column .=              '<span class="switch-icon-left"><i data-feather="check"></i></span>';
                            $column .=              '<span class="switch-icon-right"><i data-feather="x"></i></span>';
                            $column .=          '</label>';
                            $column .=      '</div>';
                            return $column;
                        })
                        ->addColumn('actions', function ($model) {
                            $record = $model;
                            return view("admin.pages.stores.components.action", compact("record"));
                        })
                        ->rawColumns(['created_by', 'app_key', 'app_secret', 'access_token', 'status', 'actions'])
                        ->make(true);
                }
            }



            return view("admin.pages.stores.apps.index", $data);
        } else {
            return redirect()->route("stores.index")->with("error", "Something went wrong while fetching apps of this store");
        }
    }

    public function saveApp(Request $request)
    {

        // $this->authorize("store-create");
        $request->validate([
            "name" => "required|max:255|unique:store_apps,app_name,NULL,id,deleted_at,NULL",
            "app_key" => "required|max:255|unique:store_apps,app_key,NULL,id,deleted_at,NULL",
            "app_secret" => "required|max:255|unique:store_apps,app_secret,NULL,id,deleted_at,NULL",
            "access_token" => "required|max:255|unique:store_apps,access_token,NULL,id,deleted_at,NULL",
            "api_version" => "required|max:255",
        ]);
        try {
            $slug = Str::slug($request->name);
            $create = StoreApp::create([
                "store_id" => $request->store_id ?? null,
                "created_by" => getUser()->id ?? null,
                "app_name" => $request->name ?? null,
                "slug" => $slug ?? null,
                "app_key" => $request->app_key ?? null,
                "app_secret" => $request->app_secret ?? null,
                "access_token" => $request->access_token ?? null,
                "api_version" => $request->api_version ?? null,
                "status" => $request->status ?? null,
            ]);
            if ($create->id) {
                if ($request->status == 1) {
                    $apps = StoreApp::where("store_id", $request->store_id)->where("id", "!=", $create->id)->get();
                    if (isset($apps) && !empty($apps)) {
                        foreach ($apps as $i => $v) {
                            $v->update(['status' => 2]);
                        }
                    }
                }
                return jsonResponse(true, $create, "App Created Successfuly!", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage() . ' on file : ' . $e->getFile() . " at line: " . $e->getLine(), 422);
        }
    }

    public function updateAppStatus(Request $request)
    {
        try {
            if (isset($request->app_id) && !empty($request->app_id)) {
                $app = StoreApp::where("id", $request->app_id)->first();
                if (isset($app) && !empty($app)) {
                    $update = $app->update([
                        "status" => $request->status,
                    ]);
                    $otherApps = StoreApp::where("id", "!=", $request->app_id)->get();
                    if ($request->status == 1) {
                        $status = 2;
                    } else {
                        $status = 1;
                    }
                    if (isset($otherApps) && !empty($otherApps)) {
                        foreach ($otherApps as $i => $v) {
                            $v->update(['status' => $status]);
                        }
                    }
                    if ($update > 0) {
                        return jsonResponse(true, $app, "Status Updated", 200);
                    }
                } else {
                    return jsonResponse(false, [], "APP not found!", 200);
                }
            } else {
                return jsonResponse(false, [], "APP ID not found", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, $e->getMessage() . "- on line number: " . $e->getLine() . " - of file: "  . $e->getFile());
        }
    }


    public function deleteApp(Request $request)
    {
        return $request->all();
    }
    // apps department
}
