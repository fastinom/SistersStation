<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        // Temporary hardcoded categories until database migrations work
        $categoriesData = [
            (object)[
                'id' => 1,
                'name' => 'Baby Clothing',
                'slug' => 'baby-clothing',
                'description' => 'Adorable and comfortable clothing for babies',
                'icon' => 'bi bi-balloon-heart',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
                'parent_id' => null,
                'products_count' => 0
            ],
            (object)[
                'id' => 2,
                'name' => 'Onesies & Bodysuits',
                'slug' => 'onesies-bodysuits',
                'description' => 'Comfortable onesies and bodysuits for everyday wear',
                'icon' => 'bi bi-shirt',
                'sort_order' => 2,
                'is_featured' => false,
                'is_active' => true,
                'parent_id' => 1,
                'products_count' => 0
            ],
            (object)[
                'id' => 3,
                'name' => 'Sleepwear',
                'slug' => 'sleepwear',
                'description' => 'Cozy sleepwear for peaceful nights',
                'icon' => 'bi bi-moon-stars',
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => true,
                'parent_id' => 1,
                'products_count' => 0
            ],
            (object)[
                'id' => 4,
                'name' => 'Dresses & Outfits',
                'slug' => 'dresses-outfits',
                'description' => 'Beautiful dresses and complete outfits for special occasions',
                'icon' => 'bi bi-star',
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => true,
                'parent_id' => 1,
                'products_count' => 0
            ],
            (object)[
                'id' => 5,
                'name' => 'Shoes & Socks',
                'slug' => 'shoes-socks',
                'description' => 'Cute shoes and cozy socks for tiny feet',
                'icon' => 'bi bi-boot',
                'sort_order' => 5,
                'is_featured' => false,
                'is_active' => true,
                'parent_id' => 1,
                'products_count' => 0
            ],
            (object)[
                'id' => 6,
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Cute accessories to complete the look',
                'icon' => 'bi bi-gift',
                'sort_order' => 6,
                'is_featured' => false,
                'is_active' => true,
                'parent_id' => 1,
                'products_count' => 0
            ],
            (object)[
                'id' => 7,
                'name' => 'Toys & Play',
                'slug' => 'toys-play',
                'description' => 'Educational and fun toys for development',
                'icon' => 'bi bi-controller',
                'sort_order' => 7,
                'is_featured' => true,
                'is_active' => true,
                'parent_id' => null,
                'products_count' => 0
            ],
            (object)[
                'id' => 8,
                'name' => 'Nursery',
                'slug' => 'nursery',
                'description' => 'Essentials for a comfortable nursery',
                'icon' => 'bi bi-house-heart',
                'sort_order' => 8,
                'is_featured' => true,
                'is_active' => true,
                'parent_id' => null,
                'products_count' => 0
            ],
            (object)[
                'id' => 9,
                'name' => 'Feeding',
                'slug' => 'feeding',
                'description' => 'Everything you need for feeding time',
                'icon' => 'bi bi-cup-straw',
                'sort_order' => 9,
                'is_featured' => false,
                'is_active' => true,
                'parent_id' => null,
                'products_count' => 0
            ],
            (object)[
                'id' => 10,
                'name' => 'Bath Time',
                'slug' => 'bath-time',
                'description' => 'Make bath time fun and safe',
                'icon' => 'bi bi-droplet',
                'sort_order' => 10,
                'is_featured' => false,
                'is_active' => true,
                'parent_id' => null,
                'products_count' => 0
            ],
        ];

        // Filter root categories (parent_id is null)
        $babyCategories = collect($categoriesData)->where('parent_id', null);

        return view('home', [
            'babyCategories' => $babyCategories,
            'featuredProducts' => collect([]),
            'latestProducts' => collect([]),
            'featuredCategories' => collect([]),
            'topSellers' => collect([]),
            'saleProducts' => collect([])
        ]);
    }

    /**
     * Show the about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Show the contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Here you would typically send an email or store the contact request
        // For now, we'll just redirect with a success message
        
        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Get search suggestions for autocomplete.
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([]);
        }

        // For now, return empty suggestions until database is set up
        return response()->json([
            'products' => [],
            'categories' => [],
            'sellers' => []
        ]);
    }
}
