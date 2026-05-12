<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Role & Permissions Seeder
        $this->call(RolePermissionSeeder::class);

        // Admin Seeder
        $this->call(AdminSeeder::class);

        // Merchant Seeder
        $this->call(MerchantSeeder::class);

        // Category Seeder
        $this->call(CategorySeeder::class);

        // Inward Deposits Seeder
        //$this->call(InwardSeeder::class);

        // Payout Seeder
        //$this->call(PayoutSeeder::class);

        // State Seeder
        $this->call(StateSeeder::class);

        // Invoice Seeder
        //$this->call(InvoiceSeeder::class);
    }
}
