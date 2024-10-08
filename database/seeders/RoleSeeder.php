<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing roles before seeding (optional)
        Role::query()->delete();

        $roles =  [
            [
                'name'                  => 'Super Admin',
                'display_name'          => 'Super Admin',
                'short_description'     => 'Super Admin Role',
                'guard_name'            => 'web',
            ],
            [
                'name'                  => 'Admin',
                'display_name'          => 'Admin',
                'short_description'     => 'Admin Role',
                'guard_name'            => 'web',
            ],
            [
                'name'                  => 'Sales Person',
                'display_name'          => 'Sales Person',
                'short_description'     => 'Sales Person Role',
                'guard_name'            => 'web',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
