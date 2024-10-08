<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('permissions')->truncate();
    DB::table('model_has_permissions')->truncate();
    DB::table('role_has_permissions')->truncate();
    $permissions = [
      [
        'group' =>  'Dashboard',
        'name' => 'dashboard-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Brand',
        'name' => 'brand-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Brand',
        'name' => 'brand-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Brand',
        'name' => 'brands-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Brand',
        'name' => 'brand-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Brand',
        'name' => 'brand-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Brand',
        'name' => 'brand-all-delete',
        'display_name' => 'All delete',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-update-status',
        'display_name' => 'Update Status',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Permission',
        'name' => 'permission-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Permission',
        'name' => 'permission-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Permission',
        'name' => 'permission-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Permission',
        'name' => 'permission-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Permission',
        'name' => 'permission-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Role',
        'name' => 'role-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Role',
        'name' => 'role-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Role',
        'name' => 'role-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Role',
        'name' => 'role-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Role',
        'name' => 'role-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-direct-permission',
        'display_name' => 'Direct permission',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'User',
        'name' => 'user-all-delete',
        'display_name' => 'All-delete',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Role',
        'name' => 'role-view-user',
        'display_name' => 'View user',
        'guard_name' => 'web'
      ],
      [
        'group' =>  'Role',
        'name' => 'role-user',
        'display_name' => 'User',
        'guard_name' => 'web'
      ],

    ];

    foreach ($permissions as $value) {
      Permission::create([
        'group' => $value['group'],
        'name' => $value['name'] ?? "NA",
        'display_name' => $value['display_name'],
        'guard_name' => $value['guard_name'],
      ]);
    }
  }
}
