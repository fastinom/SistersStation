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
        $babyCategories = Category::query()
            ->active()
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $featuredProducts = Product::query()
            ->active()
            ->featured()
            ->with(['category', 'seller', 'images'])
            ->withAvg(['reviews as reviews_avg_rating' => function ($query) {
                $query->where('is_approved', true);
            }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->active()
            ->with(['category', 'seller', 'images'])
            ->withAvg(['reviews as reviews_avg_rating' => function ($query) {
                $query->where('is_approved', true);
            }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->latest()
            ->take(3)
            ->get();

        $saleProducts = Product::query()
            ->active()
            ->whereNotNull('compare_price')
            ->whereColumn('compare_price', '>', 'price')
            ->with(['category', 'seller', 'images'])
            ->withAvg(['reviews as reviews_avg_rating' => function ($query) {
                $query->where('is_approved', true);
            }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->latest()
            ->take(8)
            ->get();

        return view('home', [
            'babyCategories' => $babyCategories,
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'featuredCategories' => Category::query()->featured()->active()->take(6)->get(),
            'topSellers' => Seller::query()->where('is_featured', true)->take(6)->get(),
            'saleProducts' => $saleProducts
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
