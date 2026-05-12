<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'E-commerce'              => ['Fashion', 'Electronics', 'Grocery', 'Jewelry', 'Toys'],
            'Education'               => ['EdTech', 'Coaching', 'Schools', 'Online Courses'],
            'Healthcare'              => ['Clinics', 'Pharmacy', 'Diagnostics'],
            'Food & Beverage'         => ['Restaurant', 'Cloud Kitchen', 'Cafe'],
            'Travel'                  => ['Hotels', 'Tours', 'Ticket Booking'],
            'Financial Services'      => ['Insurance', 'Lending', 'Investment'],
            'Digital Services'        => ['SaaS', 'Web Development', 'Marketing'],
            'Professional Services'   => ['CA', 'Legal', 'Consulting'],
            'Retail'                  => ['Clothing Store', 'Mobile Shop', 'Supermarket'],
            'Subscription Services'   => ['OTT', 'Membership', 'Recurring Billing'],
            'Gaming & Entertainment'  => ['Online Gaming', 'Events', 'Streaming'],
            'Logistics'               => ['Courier', 'Transport', 'Delivery'],
            'Donations & NGOs'        => ['Charity', 'Religious Trust'],
            'Real Estate'             => ['Property Dealer', 'Rental Services'],
            'Others'                  => ['Miscellaneous'],
        ];

        foreach ($data as $categoryName => $subCategories) {
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($subCategories as $subCategoryName) {
                SubCategory::firstOrCreate([
                    'category_id' => $category->id,
                    'name'        => $subCategoryName,
                ]);
            }
        }
    }
}
