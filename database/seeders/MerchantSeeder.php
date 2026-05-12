<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

use App\Models\User;
use DirectoryTree\Authorization\Role;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchant = User::create([
            'first_name' => 'Merchant',
            'last_name' => ' ',
            'full_name' => 'Merchant',
            'phone' => '9927724426',
            'email' => 'cybler.tk@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'merchant',
            'email_verified_at' => now(),
            'merchant_id' => (string) Str::uuid(),
            'profile_photo' => 'default-avatar.png'
        ]);

        $role = Role::where('name', 'merchant')->first();
        $permissions = $role->permissions;

        if(!$role) {
            return;
        }

        // attach merchant role to merchant user
        $merchant->roles()->attach($role);

        // attach merchant permissions to merchant user
        $merchant->permissions()->attach($permissions);
    }
}
