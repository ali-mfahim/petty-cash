<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("user-list");
        $data['title'] = "Users List";
        if ($request->ajax()) {
            $users = User::where("id", "!=", getUser()->id)->orderBy("id", "desc")->select("*");
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn("name", function ($model) {
                    return view('admin.pages.users.components.userView', ['record' => $model])->render();
                })
                ->addColumn("role", function ($model) {
                    $role = getMyRole($model->id)  ?? "-";
                    return '<span class="badge  bg-danger">' . $role . '</span>';
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
        return view("admin.pages.users.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("user-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("user-create");
        $request->validate([
            "first_name" => "required|max:255",
            "email" => "required|email|unique:users,email",
            "phone" => "nullable|numeric",
            'role' => 'required',
            "password" => "required|min:8|max:16"

        ], [
            "password.min" => "Must contain atleast 8 characters",
            "password.max" => "Must not be greater than 16 characters"
        ]);
        try {
            $name = "";
            if (isset($request->first_name) && !empty($request->first_name)) {
                $name .= $request->first_name;
            }
            if (isset($request->last_name) && !empty($request->last_name)) {
                $name .= " " . $request->last_name;
            }
            $create = User::create([
                "name" => $name ?? null,
                "slug" => getSlug($request->name),
                "first_name" => $request->first_name ?? null,
                "last_name" => $request->last_name ?? null,
                "email" => $request->email ?? null,
                "password" => isset($request->password) && !empty($request->password) ? Hash::make($request->password) : null,
                "phone" => $request->phone ?? null,
                "password_string" => isset($request->password) && !empty($request->password) ? $request->password : null,
            ]);
            if ($create->id) {
                $userId = $create->id;

                $role = $request->role;
                if (isset($role) && !empty($role)) {
                    $roleName = Role::where("id", $role)->first();
                    if (isset($roleName) && !empty($roleName)) {
                        $create->syncRoles($roleName->name);
                    }
                }
                $user = getUser($userId);
                return jsonResponse(true, $user, "User Created Successfuly!", 200);
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
        $this->authorize("user-view");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize("user-edit");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("user-edit");
        $request->validate([
            "first_name" => "required|max:255",
            "email" => [
                "required",
                "email",
                Rule::unique('users')->ignore($id), // Ignore the current user's email
            ],
            "phone" => "nullable|numeric",
            "password" => "nullable|min:8|max:16"
        ], [
            "password.min" => "Must contain at least 8 characters",
            "password.max" => "Must not be greater than 16 characters"
        ]);

        try {

            $user = getUser($id);
            if (isset($request->password) && !empty($request->password)) {
                $newPassword = Hash::make($request->password);
                $newPasswordString = $request->password;
                $user->password = $newPassword;
                $user->password_string = $newPasswordString;
            }
            $user->first_name = $request->first_name ?? $user->first_name;
            $user->last_name = $request->last_name ?? null;
            $user->phone = $request->phone ?? null;
            $user->email = $request->email ?? $user->email;

            $update = $user->save();
            if ($update > 0) {
                $user = getUser($id);
                $role = $request->role;
                if (isset($role) && !empty($role)) {
                    $roleName = Role::where("id", $role)->first();
                    if (isset($roleName) && !empty($roleName)) {
                        $user->syncRoles($roleName->name);
                    }
                }
                return jsonResponse(true, [], "User has been updated successfuly!", 200);
            } else {
                return jsonResponse(false, [], "Failed to update user!", 200);
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
        $this->authorize("user-delete");
        $user = getUser($id);
        if (!empty($user)) {
            $delete = $user->delete();
            if ($delete > 0) {
                return jsonResponse(true, [], "User has been deleted successfuly!", 200);
            } else {
                return jsonResponse(false, [], "Failed to delete the user", 200);
            }
        }
    }


    public function getEditUserModalContent(Request $request)
    {
        $this->authorize("user-edit");
        if (isset($request->user_id) && !empty($request->user_id)) {
            $user = getUser($request->user_id);
            if (!empty($user)) {
                $userName = getUserName($user);
                if (isset($user->roles[0]) && !empty($user->roles[0])) {
                    $role = $user->roles[0];
                } else {
                    $role = "";
                }
                $view = view("admin.pages.users.components.editUserModalContent", compact("user", 'role'))->render();
                $data = [
                    "view" => $view,
                    "user" => $user,
                    "user_name" => $userName,
                ];
                return jsonResponse(true, $data, "User Edit", 200);
            } else {
                return jsonResponse(false, [], "Failed to get user", 422);
            }
        } else {
            return jsonResponse(false, [], "User ID Not Found", 422);
        }
    }
    public function getLink(Request $request)
    {
        try {

            $user_id = $request->user_id;
            $type = $request->type;
            $title = "";
            $link = getLatestFormLink($type, $user_id);
            if ($type == 1) {
                $title =  "Petty Cash Form Link";
            }
            if ($type == 2) {
                $title = "Personal Expense Form Link";
            }
            $view = view("admin.profile.generateLinkModalContent", compact("user_id", "type", "link", "title"))->render();
            return jsonResponse(true, $view, "Modal Content", 200);
        } catch (Exception $e) {
            return jsonResponse(false, [], $e->getMessage(), 200);
        }
    }
}
