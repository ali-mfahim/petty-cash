<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize("store-list");
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
        // $this->authorize("store-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $this->authorize("store-create");
        $request->validate([
            "name" => "required|max:255|unique:stores,name,NULL,id,deleted_at,NULL",
            "domain" => "required|max:255|unique:stores,domain,NULL,id,deleted_at,NULL",
            "base_url" => "required|max:255|unique:stores,base_url,NULL,id,deleted_at,NULL",
        ]);
        try {
            if (isset($request->logo) && !empty($request->logo)) {
                $logo = uploadSingleFile($request->logo, config("project.upload_path.store_logo"), "store");
            }
            $create = Store::create([
                "created_by" => getUser()->id ?? null,
                "name" => $request->name ?? null,
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
        // $this->authorize("store-edit");
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
            $update =  $store->update([
                "name" => $request->name ?? null,
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
        // $this->authorize("store-delete");
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
        // $this->authorize("categories-edit");
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
}
