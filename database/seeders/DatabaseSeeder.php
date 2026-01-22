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
        // Create admin user first
        $this->call(AdminUserSeeder::class);

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
                'name' => 'Tommee Tippee',
                'slug' => 'tommee-tippee',
                'description' => 'Tommee Tippee products',
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'name' => 'Baby Clothing',
                'slug' => 'baby-clothing',
                'description' => 'Adorable and comfortable clothing for babies',
                'icon' => 'bi bi-balloon-heart',
                'sort_order' => 2,
                'is_featured' => true,
            ],
            [
                'name' => 'Bath and Skin Care',
                'slug' => 'bath-and-skin-care',
                'description' => 'Bath and skin care essentials',
                'sort_order' => 3,
                'is_featured' => true,
            ],
            [
                'name' => 'Bedding',
                'slug' => 'bedding',
                'description' => 'Bedding and sleep essentials',
                'sort_order' => 4,
                'is_featured' => true,
            ],
            [
                'name' => 'Crafted with Love',
                'slug' => 'crafted-with-love',
                'description' => 'Handmade and crafted items',
                'sort_order' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Mommy\'s Corner',
                'slug' => 'mommys-corner',
                'description' => 'Products for moms',
                'sort_order' => 6,
                'is_featured' => true,
            ],
            [
                'name' => 'Nursery Accessories',
                'slug' => 'nursery-accessories',
                'description' => 'Nursery accessories and decor',
                'sort_order' => 7,
                'is_featured' => true,
            ],
            [
                'name' => 'Baby Toys',
                'slug' => 'baby-toys',
                'description' => 'Toys for babies',
                'sort_order' => 8,
                'is_featured' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $categoryData = array_merge([
                'is_active' => true,
                'parent_id' => null,
                'icon' => $categoryData['icon'] ?? null,
                'description' => $categoryData['description'] ?? null,
            ], $categoryData);

            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
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
                'name' => 'Tommee Tippee Baby Bottle',
                'description' => 'Tommee Tippee baby bottle designed for comfortable feeding.',
                'short_description' => 'Tommee Tippee bottle',
                'price' => 12.99,
                'compare_price' => 18.99,
                'sku' => 'TT-BOTTLE-001',
                'brand' => 'Tommee Tippee',
                'weight' => 0.2,
                'quantity' => 50,
                'category_slug' => 'tommee-tippee',
                'tags' => ['bottle', 'feeding', 'tommee tippee'],
            ],
            [
                'name' => 'Baby Clothing Set',
                'description' => 'Comfortable baby clothing set.',
                'short_description' => 'Baby clothing',
                'price' => 24.99,
                'compare_price' => 34.99,
                'sku' => 'BC-SET-001',
                'brand' => 'Sisters Station',
                'weight' => 0.5,
                'quantity' => 30,
                'category_slug' => 'baby-clothing',
                'tags' => ['clothing'],
            ],
            [
                'name' => 'Baby Bath Set',
                'description' => 'Gentle bath and skin care set for babies.',
                'short_description' => 'Bath and skin care',
                'price' => 19.99,
                'compare_price' => 29.99,
                'sku' => 'BSC-SET-001',
                'brand' => 'Sisters Station',
                'weight' => 0.3,
                'quantity' => 25,
                'category_slug' => 'bath-and-skin-care',
                'tags' => ['bath', 'skin care'],
            ],
            [
                'name' => 'Baby Bedding Set',
                'description' => 'Soft bedding set for baby crib.',
                'short_description' => 'Bedding',
                'price' => 22.99,
                'compare_price' => 32.99,
                'sku' => 'BED-SET-001',
                'brand' => 'Sisters Station',
                'weight' => 0.1,
                'quantity' => 20,
                'category_slug' => 'bedding',
                'tags' => ['bedding'],
            ],
            [
                'name' => 'Handmade Baby Gift',
                'description' => 'Handmade item crafted with love.',
                'short_description' => 'Crafted with Love',
                'price' => 15.99,
                'compare_price' => 22.99,
                'sku' => 'CWL-001',
                'brand' => 'Sisters Station',
                'weight' => 0.8,
                'quantity' => 40,
                'category_slug' => 'crafted-with-love',
                'tags' => ['handmade'],
            ],
            [
                'name' => 'Mommy Care Kit',
                'description' => 'Essentials for moms.',
                'short_description' => 'Mommy\'s Corner',
                'price' => 22.99,
                'compare_price' => 32.99,
                'sku' => 'MC-001',
                'brand' => 'Sisters Station',
                'weight' => 1.0,
                'quantity' => 20,
                'category_slug' => 'mommys-corner',
                'tags' => ['mom'],
            ],
        ];

        foreach ($products as $productData) {
            $seller = $sellers->random();
            $category = $categories->where('slug', $productData['category_slug'])->first();

            if (!$category) {
                $category = $categories->where('slug', 'baby-clothing')->first();
            }

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
