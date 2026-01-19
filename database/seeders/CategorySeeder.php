<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Baby Clothing',
                'slug' => 'baby-clothing',
                'description' => 'Adorable and comfortable clothing for babies',
                'icon' => 'bi bi-balloon-heart',
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Baby Clothing - Sisters Station',
                'meta_description' => 'Shop for cute and comfortable baby clothing at Sisters Station',
                'meta_keywords' => 'baby clothes, infant clothing, baby wear',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Onesies & Bodysuits',
                'slug' => 'onesies-bodysuits',
                'description' => 'Comfortable onesies and bodysuits for everyday wear',
                'icon' => 'bi bi-shirt',
                'parent_id' => null, // Will be updated to Baby Clothing ID after insertion
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Onesies & Bodysuits',
                'meta_description' => 'Comfortable onesies and bodysuits for babies',
                'meta_keywords' => 'onesies, bodysuits, baby clothes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sleepwear',
                'slug' => 'sleepwear',
                'description' => 'Cozy sleepwear for peaceful nights',
                'icon' => 'bi bi-moon-stars',
                'parent_id' => null, // Will be updated to Baby Clothing ID after insertion
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Baby Sleepwear',
                'meta_description' => 'Cozy sleepwear for peaceful nights',
                'meta_keywords' => 'baby sleepwear, pajamas, night clothes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dresses & Outfits',
                'slug' => 'dresses-outfits',
                'description' => 'Beautiful dresses and complete outfits for special occasions',
                'icon' => 'bi bi-star',
                'parent_id' => null, // Will be updated to Baby Clothing ID after insertion
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Baby Dresses & Outfits',
                'meta_description' => 'Beautiful dresses and complete outfits for special occasions',
                'meta_keywords' => 'baby dresses, outfits, special occasion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shoes & Socks',
                'slug' => 'shoes-socks',
                'description' => 'Cute shoes and cozy socks for tiny feet',
                'icon' => 'bi bi-boot',
                'parent_id' => null, // Will be updated to Baby Clothing ID after insertion
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Baby Shoes & Socks',
                'meta_description' => 'Cute shoes and cozy socks for tiny feet',
                'meta_keywords' => 'baby shoes, baby socks, footwear',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Cute accessories to complete the look',
                'icon' => 'bi bi-gift',
                'parent_id' => null, // Will be updated to Baby Clothing ID after insertion
                'sort_order' => 6,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Baby Accessories',
                'meta_description' => 'Cute accessories to complete the look',
                'meta_keywords' => 'baby accessories, hats, bibs, burp cloths',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toys & Play',
                'slug' => 'toys-play',
                'description' => 'Educational and fun toys for development',
                'icon' => 'bi bi-controller',
                'parent_id' => null,
                'sort_order' => 7,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Baby Toys & Play',
                'meta_description' => 'Educational and fun toys for development',
                'meta_keywords' => 'baby toys, educational toys, playtime',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nursery',
                'slug' => 'nursery',
                'description' => 'Essentials for a comfortable nursery',
                'icon' => 'bi bi-house-heart',
                'parent_id' => null,
                'sort_order' => 8,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Baby Nursery Essentials',
                'meta_description' => 'Essentials for a comfortable nursery',
                'meta_keywords' => 'nursery, baby room, crib, bedding',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Feeding',
                'slug' => 'feeding',
                'description' => 'Everything you need for feeding time',
                'icon' => 'bi bi-cup-straw',
                'parent_id' => null,
                'sort_order' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Baby Feeding Essentials',
                'meta_description' => 'Everything you need for feeding time',
                'meta_keywords' => 'baby feeding, bottles, bibs, high chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bath Time',
                'slug' => 'bath-time',
                'description' => 'Make bath time fun and safe',
                'icon' => 'bi bi-droplet',
                'parent_id' => null,
                'sort_order' => 10,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Baby Bath Time',
                'meta_description' => 'Make bath time fun and safe',
                'meta_keywords' => 'baby bath, bath toys, bath time',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert categories and get their IDs
        $insertedCategories = [];
        foreach ($categories as $category) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'icon' => $category['icon'],
                'parent_id' => $category['parent_id'],
                'sort_order' => $category['sort_order'],
                'is_active' => $category['is_active'],
                'is_featured' => $category['is_featured'],
                'meta_title' => $category['meta_title'],
                'meta_description' => $category['meta_description'],
                'meta_keywords' => $category['meta_keywords'],
                'created_at' => $category['created_at'],
                'updated_at' => $category['updated_at'],
            ]);
            
            $insertedCategories[$category['slug']] = $categoryId;
        }

        // Update parent_id for subcategories to point to Baby Clothing
        $babyClothingId = $insertedCategories['baby-clothing'];
        $subcategories = ['onesies-bodysuits', 'sleepwear', 'dresses-outfits', 'shoes-socks', 'accessories'];
        
        foreach ($subcategories as $subcategorySlug) {
            DB::table('categories')
                ->where('slug', $subcategorySlug)
                ->update(['parent_id' => $babyClothingId]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
