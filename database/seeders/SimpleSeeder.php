<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;

class SimpleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@sistersstation.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create seller user first
        $sellerUser = User::create([
            'name' => 'Baby Bliss Boutique',
            'email' => 'babybliss@example.com',
            'password' => bcrypt('password'),
            'user_type' => 'seller',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create sample categories
        $categories = [
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Onesies & Bodysuits', 'slug' => 'onesies-bodysuits'],
            ['name' => 'Sleepwear', 'slug' => 'sleepwear'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Toys', 'slug' => 'toys'],
            ['name' => 'Nursery', 'slug' => 'nursery'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample seller with user_id
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Baby Bliss Boutique',
            'store_slug' => 'baby-bliss-boutique',
            'store_description' => 'Specializing in organic cotton baby clothing and accessories',
            'verification_status' => 'approved',
            'verified_at' => now(),
            'commission_rate' => 10.00,
            'rating' => 4.5,
            'review_count' => 25,
        ]);

        // Create sample products
        $products = [
            [
                'name' => 'Organic Cotton Onesie - Pink',
                'slug' => 'organic-cotton-onesie-pink',
                'description' => 'Soft and comfortable organic cotton onesie perfect for your little one.',
                'price' => 12.99,
                'sku' => 'OC-001-PINK',
                'brand' => 'Baby Bliss',
                'quantity' => 50,
                'category_id' => 2, // Onesies & Bodysuits
                'seller_id' => $seller->id,
                'status' => 'active',
            ],
            [
                'name' => 'Baby Sleep Set - Blue',
                'slug' => 'baby-sleep-set-blue',
                'description' => 'Complete sleep set including pajamas, hat, and booties.',
                'price' => 24.99,
                'sku' => 'SS-002-BLUE',
                'brand' => 'Tiny Treasures',
                'quantity' => 30,
                'category_id' => 3, // Sleepwear
                'seller_id' => $seller->id,
                'status' => 'active',
            ],
            [
                'name' => 'Floral Baby Dress',
                'slug' => 'floral-baby-dress',
                'description' => 'Beautiful floral dress perfect for special occasions.',
                'price' => 19.99,
                'sku' => 'FD-003-FLORAL',
                'brand' => 'Little Wonders',
                'quantity' => 25,
                'category_id' => 1, // Clothing
                'seller_id' => $seller->id,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
