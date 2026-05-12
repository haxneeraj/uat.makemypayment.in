<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use DirectoryTree\Authorization\Role;
use DirectoryTree\Authorization\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('permissions.permissions');

        $roles = config('permissions.roles');

        foreach ($roles as $role) {
            $record = Role::create($role);
            foreach($permissions[$role['name']] as $permission) {
                $permission_record = Permission::updateOrCreate(['name' => $permission['name']], $permission);
                $record->grant($permission_record);
            }
        }
    }
}
