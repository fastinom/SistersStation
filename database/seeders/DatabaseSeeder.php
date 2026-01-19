<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories first
        $this->createCategories();

        // Create sample sellers
        $this->createSampleSellers();

        // Create sample products
        $this->createSampleProducts();
    }

    private function createCategories()
    {
        $categories = [
            [
                'name' => 'Baby Clothing',
                'slug' => 'baby-clothing',
                'description' => 'Adorable and comfortable clothing for babies',
                'icon' => 'bi bi-balloon-heart',
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'name' => 'Onesies & Bodysuits',
                'slug' => 'onesies-bodysuits',
                'description' => 'Comfortable onesies and bodysuits for everyday wear',
                'icon' => 'bi bi-shirt',
                'parent_id' => null, // Will be set after parent is created
                'sort_order' => 2,
            ],
            [
                'name' => 'Sleepwear',
                'slug' => 'sleepwear',
                'description' => 'Cozy sleepwear for peaceful nights',
                'icon' => 'bi bi-moon-stars',
                'parent_id' => null, // Will be set after parent is created
                'sort_order' => 3,
            ],
            [
                'name' => 'Dresses & Outfits',
                'slug' => 'dresses-outfits',
                'description' => 'Beautiful dresses and complete outfits for special occasions',
                'icon' => 'bi bi-star',
                'parent_id' => null, // Will be set after parent is created
                'sort_order' => 4,
            ],
            [
                'name' => 'Shoes & Socks',
                'slug' => 'shoes-socks',
                'description' => 'Cute shoes and cozy socks for tiny feet',
                'icon' => 'bi bi-boot',
                'parent_id' => null, // Will be set after parent is created
                'sort_order' => 5,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Cute accessories to complete the look',
                'icon' => 'bi bi-gift',
                'parent_id' => null, // Will be set after parent is created
                'sort_order' => 6,
            ],
            [
                'name' => 'Toys & Play',
                'slug' => 'toys-play',
                'description' => 'Educational and fun toys for development',
                'icon' => 'bi bi-controller',
                'sort_order' => 7,
                'is_featured' => true,
            ],
            [
                'name' => 'Nursery',
                'slug' => 'nursery',
                'description' => 'Essentials for a comfortable nursery',
                'icon' => 'bi bi-house-heart',
                'sort_order' => 8,
                'is_featured' => true,
            ],
            [
                'name' => 'Feeding',
                'slug' => 'feeding',
                'description' => 'Everything you need for feeding time',
                'icon' => 'bi bi-cup-straw',
                'sort_order' => 9,
            ],
            [
                'name' => 'Bath Time',
                'slug' => 'bath-time',
                'description' => 'Make bath time fun and safe',
                'icon' => 'bi bi-droplet',
                'sort_order' => 10,
            ],
        ];

        $createdCategories = [];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
            $createdCategories[$categoryData['slug']] = $category;
        }

        // Set parent relationships - subcategories under Baby Clothing
        $parentMappings = [
            'onesies-bodysuits' => 'baby-clothing',
            'sleepwear' => 'baby-clothing',
            'dresses-outfits' => 'baby-clothing',
            'shoes-socks' => 'baby-clothing',
            'accessories' => 'baby-clothing',
        ];

        foreach ($parentMappings as $childSlug => $parentSlug) {
            if (isset($createdCategories[$childSlug]) && isset($createdCategories[$parentSlug])) {
                $createdCategories[$childSlug]->update([
                    'parent_id' => $createdCategories[$parentSlug]->id
                ]);
            }
        }
    }

    private function createSampleSellers()
    {
        $sellers = [
            [
                'name' => 'Baby Bliss Boutique',
                'email' => 'babybliss@example.com',
                'description' => 'Specializing in organic cotton baby clothing and accessories',
            ],
            [
                'name' => 'Tiny Treasures',
                'email' => 'tinytreasures@example.com',
                'description' => 'Handmade baby clothes and unique gifts for little ones',
            ],
            [
                'name' => 'Little Wonders',
                'email' => 'littlewonders@example.com',
                'description' => 'Premium baby wear and essentials for modern parents',
            ],
        ];

        foreach ($sellers as $sellerData) {
            $user = User::firstOrCreate(
                ['email' => $sellerData['email']],
                [
                    'name' => $sellerData['name'],
                    'password' => Hash::make('password'),
                    'user_type' => 'seller',
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            // Note: Role assignment removed for now
            // $user->assignRole('seller');

            Seller::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'store_name' => $sellerData['name'],
                    'store_slug' => Str::slug($sellerData['name']),
                    'store_description' => $sellerData['description'],
                    'verification_status' => 'approved',
                    'verified_at' => now(),
                    'commission_rate' => 10.00,
                    'rating' => rand(40, 50) / 10, // Random rating between 4.0 and 5.0
                    'review_count' => rand(10, 100),
                ]
            );
        }
    }

    private function createSampleProducts()
    {
        $sellers = Seller::with('user')->get();
        $categories = Category::all();

        $products = [
            [
                'name' => 'Organic Cotton Onesie - Pink',
                'description' => 'Soft and comfortable organic cotton onesie perfect for your little one. Made from 100% certified organic cotton, this onesie is gentle on sensitive skin and free from harmful chemicals.',
                'short_description' => 'Soft organic cotton onesie in pink',
                'price' => 12.99,
                'compare_price' => 18.99,
                'sku' => 'OC-001-PINK',
                'brand' => 'Baby Bliss',
                'weight' => 0.2,
                'quantity' => 50,
                'category_slug' => 'onesies-bodysuits',
                'tags' => ['organic', 'cotton', 'onesie', 'pink', 'newborn'],
            ],
            [
                'name' => 'Baby Sleep Set - Blue',
                'description' => 'Complete sleep set including pajamas, hat, and booties. Made from soft bamboo fabric that regulates temperature and keeps baby comfortable all night long.',
                'short_description' => 'Comfortable bamboo sleep set in blue',
                'price' => 24.99,
                'compare_price' => 34.99,
                'sku' => 'SS-002-BLUE',
                'brand' => 'Tiny Treasures',
                'weight' => 0.5,
                'quantity' => 30,
                'category_slug' => 'sleepwear',
                'tags' => ['bamboo', 'sleep', 'blue', 'pajamas'],
            ],
            [
                'name' => 'Floral Baby Dress',
                'description' => 'Beautiful floral dress perfect for special occasions. Features delicate lace details and a comfortable fit that allows for easy movement.',
                'short_description' => 'Elegant floral dress for baby girls',
                'price' => 19.99,
                'compare_price' => 29.99,
                'sku' => 'FD-003-FLORAL',
                'brand' => 'Little Wonders',
                'weight' => 0.3,
                'quantity' => 25,
                'category_slug' => 'dresses-outfits',
                'tags' => ['dress', 'floral', 'special occasion', 'girl'],
            ],
            [
                'name' => 'Baby Sun Hat - Yellow',
                'description' => 'Protective sun hat with wide brim and UPF 50+ protection. Adjustable chin strap ensures a secure fit during outdoor activities.',
                'short_description' => 'Protective sun hat in yellow',
                'price' => 8.99,
                'compare_price' => 12.99,
                'sku' => 'SH-004-YELLOW',
                'brand' => 'Baby Bliss',
                'weight' => 0.1,
                'quantity' => 75,
                'category_slug' => 'hats-caps',
                'tags' => ['sun protection', 'hat', 'yellow', 'outdoor'],
            ],
            [
                'name' => 'Soft Teddy Bear',
                'description' => 'Cuddly teddy bear made from hypoallergenic materials. Perfect companion for your little one, suitable from birth.',
                'short_description' => 'Soft and safe teddy bear',
                'price' => 15.99,
                'compare_price' => 22.99,
                'sku' => 'TB-005-BROWN',
                'brand' => 'Tiny Treasures',
                'weight' => 0.8,
                'quantity' => 40,
                'category_slug' => 'soft-toys',
                'tags' => ['teddy bear', 'soft toy', 'hypoallergenic', 'safe'],
            ],
            [
                'name' => 'Baby Crib Sheet Set',
                'description' => 'Set of 2 crib sheets made from premium cotton. Features elastic edges for secure fit and is machine washable for easy care.',
                'short_description' => 'Soft cotton crib sheet set',
                'price' => 22.99,
                'compare_price' => 32.99,
                'sku' => 'CS-006-WHITE',
                'brand' => 'Little Wonders',
                'weight' => 1.0,
                'quantity' => 20,
                'category_slug' => 'bedding',
                'tags' => ['crib sheet', 'cotton', 'bedding', 'white'],
            ],
        ];

        foreach ($products as $productData) {
            $seller = $sellers->random();
            $category = $categories->where('slug', $productData['category_slug'])->first();

            $product = Product::create([
                'seller_id' => $seller->id,
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']) . '-' . time(),
                'description' => $productData['description'],
                'short_description' => $productData['short_description'],
                'sku' => $productData['sku'],
                'brand' => $productData['brand'],
                'price' => $productData['price'],
                'compare_price' => $productData['compare_price'],
                'weight' => $productData['weight'],
                'track_quantity' => true,
                'quantity' => $productData['quantity'],
                'min_quantity' => 1,
                'requires_shipping' => true,
                'taxable' => true,
                'tax_rate' => 8.00,
                'status' => 'active',
                'is_featured' => rand(0, 1) === 1,
                'tags' => $productData['tags'],
                'published_at' => now(),
            ]);

            // Create product image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'https://via.placeholder.com/400x400/f0f0f0/666?text=' . urlencode($product->name),
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_primary' => true,
            ]);
        }
    }
}
