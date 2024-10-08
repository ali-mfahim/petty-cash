<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("role-list");
        $data['title'] = "Roles List";
        $data['roles'] = Role::all();
        $data['permissions'] = Permission::groupBy('group')
            ->select('group')
            ->get();

        if ($request->ajax()) {
            $users = User::orderBy("id", "desc")->select("*");
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn("name", function ($model) {
                    return view('admin.pages.users.components.userView', ['record' => $model])->render();
                })
                ->addColumn("role", function ($model) {
                    $role = getMyRole($model->id)  ?? "-";

                    return '<span class="badge  bg-primary">' . $role . '</span>';
                })
                ->addColumn("phone", function ($model) {
                    return $model->phone  ?? "-";
                })
                ->addColumn('actions', function ($model) {
                    $user = $model;
                    return view("admin.pages.users.components.action", compact("user"));
                })
                ->rawColumns(['name', 'role', 'actions'])
                ->make(true);
        }
        return view("admin.pages.roles.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("role-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("role-create");
        $request->validate([
            "roleName" => "required|unique:roles,name|max:255",
        ]);
        try {
            $role = Role::create([
                "name" => $request->roleName ?? null,
                "display_name" => $request->roleName ?? null,
                "short_description" => $request->roleName ?? null,
                "guard_name" => "web",
            ]);
            if ($role->id) {
                if (isset($request->permissions) && !empty($request->permissions) && count($request->permissions) > 0) {
                    $permissionIds = $request->permissions;
                    $permissions = Permission::whereIn("id", $permissionIds)->pluck("name")->toArray();
                    if (isset($permissions) && !empty($permissions) && count($permissions)) {
                        $role->syncPermissions($permissions);
                    }
                    return jsonResponse(true, [], "Role has been created!", 200);
                }
            } else {
                return jsonResponse(false, [], "Failed to create the Role", 200);
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
        $this->authorize("role-view");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize("role-edit");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("role-edit");
        $request->validate([
            'roleName' => [
                'required',
                'max:255',
                Rule::unique('roles', 'name')->ignore($id),
            ],
        ]);

        try {
            $role = Role::where("id", $id)->first();
            if (isset($role) && !empty($role)) {
                $update =  $role->update([
                    "name" => $request->roleName ?? null,
                    "display_name" => $request->roleName ?? null,
                    "short_description" => $request->roleName ?? null,
                    "guard_name" => "web",
                ]);
            }

            if ($update > 0) {
                $role = Role::where("id", $id)->first();
                if (isset($request->permissions) && !empty($request->permissions) && count($request->permissions) > 0) {
                    $permissionIds = $request->permissions;
                    $permissions = Permission::whereIn("id", $permissionIds)->pluck("name")->toArray();
                    if (isset($permissions) && !empty($permissions) && count($permissions)) {
                        $role->syncPermissions($permissions);
                    }
                    return jsonResponse(true, [], "Role has been updated!", 200);
                }
            } else {
                return jsonResponse(false, [], "Failed to update the Role", 200);
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
        $this->authorize("role-delete");
    }
    public function getEditRoleModalContent(Request $request)
    {
        $this->authorize("role-edit");
        if (isset($request->roleId) && !empty($request->roleId)) {
            $roleId = $request->roleId;
            $role = Role::where("id", $roleId)->first();
            $permissions = Permission::groupBy('group')
                ->select('group')
                ->get();
            $permissions_names = $role->permissions->pluck('id')->toArray();
            $view = view("admin.pages.roles.components.editRoleModalContent", compact("role", "permissions", "permissions_names"))->render();
            $data = [
                "view" => $view ?? null,
                "role" => $role ?? null,
                "permissions" => $permissions ?? null
            ];
            return jsonResponse(true, $data, "Edit Role", 200);
        } else {
            return jsonResponse(false, [], "Role ID not found", 200);
        }
    }
}
