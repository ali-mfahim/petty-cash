<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): voidp
    {
        $role = Role::where("id", 1)->first();
        $user = User::find(1);
        $user->assignRole($role->name);

        $permissions = Permission::pluck("name")->toArray();
        if (isset($permissions) && !empty($permissions) && count($permissions)) {
            $role->syncPermissions($permissions);
        }
        dd("success");
    }
}
