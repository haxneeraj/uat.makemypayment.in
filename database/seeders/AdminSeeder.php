<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

use App\Models\User;
use DirectoryTree\Authorization\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'full_name' => 'Super Admin',
            'phone' => '6354409951',
            'email' => 'admin@makemypayment.in',
            'password' => bcrypt('password'),
            'role' => 'super-admin',
            'email_verified_at' => now(),
            'merchant_id' => (string) Str::uuid(),
            'profile_photo' => 'default-avatar.png'
        ]);

        $role = Role::where('name', 'super-admin')->first();
        $permissions = $role->permissions;

        if(!$role) {
            return;
        }

        // attach admin role to admin user
        $admin->roles()->attach($role);

        // attach admin permissions to admin user
        $admin->permissions()->attach($permissions);
    }
}
