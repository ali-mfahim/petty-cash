<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;




class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("categories-list");
        $data['title'] = "Categories List";
        if ($request->ajax()) {
            $categories = Category::orderBy("id", "desc")->select("*");
            return DataTables::of($categories)
                ->addIndexColumn()

                ->addColumn("description", function ($model) {
                    $column = "";
                    $column .= '<a href="javascript:;" class="view-category-description">';
                    $column .= '<span class="badge  bg-primary view-description-btn" data-category-id="' . $model->id . '" >View</span>';
                    $column .= '</a>';
                    return $column;
                })
                ->addColumn("image", function ($model) {
                    if (checkFileExists($model->image, config("project.upload_path.categories"))) {
                        $route = asset(config('project.upload_path.categories') . $model->image);
                    } else {
                        $route = "javascript:;";
                    }

                    $html = objectWithHtml($model->title, $model->image ?? null, config('project.upload_path.categories_thumb'), "70px", "40px", "100%", $route);
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
                    $category = $model;
                    return view("admin.pages.categories.components.action", compact("category"));
                })
                ->rawColumns(['description', 'image', 'status', 'actions'])
                ->make(true);
        }
        return view("admin.pages.categories.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("categories-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->authorize("categories-create");

        $request->validate([
            "title" => "required|max:255",
        ]);
        try {
            if (isset($request->image) && !empty($request->image)) {
                $image = uploadSingleFile($request->image, config("project.upload_path.categories"), "category");
            }
            $create = Category::create([
                "title" => $request->title ?? null,
                "description" => $request->description ?? null,
                "status" => $request->status ?? null,
                "image" => $image ?? null,
            ]);
            if ($create->id) {
                return jsonResponse(true, $create, "Category Created Successfuly!", 200);
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
        $this->authorize("categories-edit");
        $request->validate([
            "title" => "required|max:255",
        ]);

        try {
            $category = Category::where("id", $id)->first();
            if (isset($request->image) && !empty($request->image)) {
                $image = uploadSingleFile($request->image, config("project.upload_path.categories"), "category", $category->image);
            } else {
                $image = $category->image;
            }
            $update =  $category->update([
                "title" => $request->title ?? null,
                "description" => $request->descripton ?? null,
                "status" => $request->status ?? null,
                "image" => $image ?? null,
            ]);
            if ($update > 0) {
                return jsonResponse(true, [], "Category has been updated successfuly!", 200);
            } else {
                return jsonResponse(false, [], "Failed to update category!", 200);
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
        $this->authorize("categories-delete");
        try {
            $category = Category::where("id", $id)->first();
            if (!empty($category)) {
                $delete = $category->delete();
                if ($delete > 0) {
                    return jsonResponse(true, [], "Category has been deleted successfuly!", 200);
                } else {
                    return jsonResponse(false, [], "Failed to delete the Category", 200);
                }
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }

    public function getEditCategoryModalContent(Request $request)
    {
        $this->authorize("categories-edit");
        if (isset($request->category_id) && !empty($request->category_id)) {
            $category = Category::where("id", $request->category_id)->first();
            if (!empty($category)) {
                $view = view("admin.pages.categories.components.editCategoryModalContent", compact("category"))->render();
                $data = [
                    "view" => $view,
                    "category" => $category,
                ];
                return jsonResponse(true, $data, "Category Edit", 200);
            } else {
                return jsonResponse(false, [], "Failed to get category", 422);
            }
        } else {
            return jsonResponse(false, [], "Category ID Not Found", 422);
        }
    }
    public function viewDescription(Request $request)
    {
        $this->authorize("categories-view");
        if (isset($request->category_id) && !empty($request->category_id)) {
            $category = Category::where("id", $request->category_id)->first();
            if (!empty($category)) {
                return jsonResponse(true, $category, "Category description", 200);
            } else {
                return jsonResponse(false, [], "Failed to get category", 422);
            }
        } else {
            return jsonResponse(false, [], "Category ID Not Found", 422);
        }
    }
}
