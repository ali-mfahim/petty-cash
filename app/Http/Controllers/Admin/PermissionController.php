<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resoRurce.
     */
    public function index(Request $request)
    {
        $this->authorize("permission-list");
        $data['title'] = "Permisisons List";
        if ($request->ajax()) {
            $permissions = Permission::groupBy('group')
                ->select('group')
                ->get();
            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('name', function ($model) {
                    return isset($model->group) ? ucfirst($model->group) : "-";
                })
                ->addColumn('permissions', function ($model) {
                    $display_names = groupPermissions($model->group) ?? null;
                    $display_name = "";
                    $display_name .= '<div class="row">';
                    if (isset($display_names) && !empty($display_names)) {
                        foreach ($display_names as $key => $value) {
                            $class = getClassForPermission($value->display_name);
                            $display_name .= '<div class="col-md-3">';
                            $display_name .= '<span class="badge badge-light-' . $class . ' fs-7 m-1"  data-bs-toggle="tooltip" title="' . $value->name . '">' . ucfirst($value->display_name) . '</span>';
                            $display_name .= '</div>';
                        }
                    }
                    $display_name .= '</div>';
                    return $display_name ?? "-";
                })
                ->addColumn('created_at', function ($model) {
                    return formatDateTime($model->created_at);
                })
                ->addColumn('actions', function ($model) {
                    $permission = singlePermission($model->group) ?? null;
                    return view('admin.pages.permissions.components.actions', ['permission' => $permission])->render();
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($array['search'])) {
                        $search = $request->search;
                        $instance->collection = $instance->collection->filter(function ($row) use ($search) {
                            return stripos($row['group'], $search) !== false;
                        });
                    }
                })
                ->rawColumns(['name', 'permissions', 'created_at', 'actions'])
                ->make(true);
        }
        return view("admin.pages.permissions.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("permission-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("permission-create");
        $request->validate([
            "modalPermissionName" => "required|max:255|unique:permissions,name",
            "display_names" => "array|required|min:1",
            "display_names.*" => "max:255",
        ], [
            "display_names.required" => "Please select atleast 1 option for this group"
        ]);
        try {
            $permissions = [];
            if (!empty($request->display_names)) {
                foreach ($request->display_names as $display_name) {
                    $name = setPermissionName($request->modalPermissionName, $display_name) ?? null;
                    $result = Permission::create([
                        'group' =>  isset($request->modalPermissionName) ? ucfirst($request->modalPermissionName) : null,
                        'name' =>  $name ?? null,
                        'display_name' => ucfirst($display_name),
                        'guard_name' => 'web',
                    ]);
                    $permissions[] = $result;
                }
            }
            if (isset($result) && !empty($result)) {
                return jsonResponse(true, $permissions, "Permission has been created", 200);
            } else {
                return jsonResponse(false, [], "Failed to create permission", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize("permission-show");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $this->authorize("permission-edit");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("permission-edit");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize("permission-delete");
    }

    public function addPermissionInTheGroup(Request $request)
    {
        $this->authorize("permission-edit");
        $request->validate([
            "permissionGroupId" => "required|numeric",
            "modalPermissionName" => "required|max:255",
        ], [
            "permissionGroupId.required" => "Invalid Permission or the permission id not found",
            "modalPermissionName.required" => "Please insert name of the permission",
        ]);
        try {
            if (isset($request->permissionGroupId) && !empty($request->permissionGroupId)) {
                $permission = Permission::where("id", $request->permissionGroupId)->first();
                if (isset($permission) && !empty($permission)) {
                    $name = setPermissionName($permission->group, $request->modalPermissionName) ?? null;
                    $checkPermission = Permission::where("name", $name)->first();
                    if (isset($checkPermission) && !empty($checkPermission)) {
                        return jsonResponse(false, [], "A permission with this name already exist : " . $checkPermission->name, 200);
                    }
                    $result = Permission::create([
                        'group' => $permission->group,
                        'name' =>  $name ?? null,
                        'display_name' => ucfirst($request->modalPermissionName),
                        'guard_name' => 'web',
                    ]);
                    if ($result->id) {
                        return jsonResponse(true, $result, "Permission has been created!", 200);
                    } else {
                        return jsonResponse(false, [], "Failed to create the permisison", 200);
                    }
                }
            } else {
                return jsonResponse(false,  [], "Permission ID not found", 200);
            }
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }
}
