<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("color-list");
        $data['title'] = "Colors List";
        if ($request->ajax()) {
            $users = Color::where("id", "!=", getUser()->id)->orderBy("id", "desc")->select("*");
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn("name", function ($model) {
                    return view('admin.pages.colors.components.colorView', ['record' => $model])->render();
                })
                ->addColumn("code", function ($model) {
                    return $model->code ?? "-";
                })
                ->addColumn("status", function ($model) {
                    $name = "";
                    $color = "";
                    if (isset($model->status) && !empty($model->status)) {
                        if ($model->status == 1) {
                            $name = "Active";
                            $color = "success";
                        } else {
                            $name = "Disabled";
                            $color = "danger";
                        }
                    }
                    return '<span class="badge  bg-' . $color . '">' . $name . '</span>';
                })
                ->addColumn('actions', function ($model) {
                    $color = $model;
                    return view("admin.pages.colors.components.action", compact("color"));
                })
                ->rawColumns(['name', 'status', 'actions'])
                ->make(true);
        }
        return view("admin.pages.colors.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("color-create");
        $request->validate([
            "name" => "required|max:255",
        ], [
            "name.required" => "Please type a color name",
        ]);
        try {
            $create = Color::create([
                "created_by" => getUser()->id,
                "name" => $request->name ?? null,
                "code" => $request->code ?? null,
            ]);
            if ($create->id) {
                return jsonResponse(true, $create, "Color Created Successfuly!", 200);
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
        $this->authorize("color-edit");
        $request->validate([
            "name" => "required|max:255",
        ], [
            "name.required" =>  "Please type color name"
        ]);

        try {
            $color = Color::where("id", $id)->first();
            if (!empty($color)) {
                $update = $color->update([
                    "name" => $request->name ?? '',
                    "code" => $request->code ?? '',
                    "status" => $request->status ?? '',
                ]);
                if ($update > 0) {
                    return jsonResponse(true, [], "Color has been updated", 200);
                } else {
                    return jsonResponse(false, [], "Failed to update color", 200);
                }
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
        $this->authorize("color-delete");
        $color = Color::where("id", $id)->first();
        if (!empty($color)) {
            $delete = $color->delete();
            if ($delete > 0) {
                return jsonResponse(true, [], "Color has been deleted successfuly!", 200);
            } else {
                return jsonResponse(false, [], "Failed to delete the color", 200);
            }
        }
    }
    public function getEditColorModalContent(Request $request)
    {
        $this->authorize("color-edit");
        if (isset($request->id) && !empty($request->id)) {
            $color = Color::where("id", $request->id)->first();
            if (!empty($color)) {
                $view = view("admin.pages.colors.components.editColorModalContent", compact("color"))->render();
                $data = [
                    "view" => $view,
                    "color" => $color,
                ];
                return jsonResponse(true, $data, "Color Edit", 200);
            } else {
                return jsonResponse(false, [], "Failed to get Color", 422);
            }
        } else {
            return jsonResponse(false, [], "Color ID Not Found", 422);
        }
    }
}
