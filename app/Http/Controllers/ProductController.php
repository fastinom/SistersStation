<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::active()
            ->inStock()
            ->with(['seller', 'category', 'images', 'reviews']);

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter by seller
        if ($request->filled('seller')) {
            $query->whereHas('seller', function (Builder $q) use ($request) {
                $q->where('store_slug', $request->seller);
            });
        }

        // Search products
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->whereHas('reviews', function (Builder $q) use ($request) {
                $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }

        // Sort products
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc');
                break;
            default:
                $query->latest();
        }

        // Get products with pagination
        $products = $query->paginate(24);

        // Get all categories for filter sidebar
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->active()->inStock();
            }])
            ->orderBy('name')
            ->get();

        // Get all brands for filter
        $brands = Product::active()
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        // Get price range
        $priceRange = Product::active()->inStock();
        $minPrice = $priceRange->min('price');
        $maxPrice = $priceRange->max('price');

        return view('products.index', compact(
            'products',
            'categories',
            'brands',
            'minPrice',
            'maxPrice'
        ));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::active()
            ->with(['seller', 'category', 'variants', 'images' => function ($query) {
                $query->orderBy('sort_order');
            }, 'reviews' => function ($query) {
                $query->with('user')->latest()->take(10);
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related products (same category, different seller)
        $relatedProducts = Product::active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['seller', 'images'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Get seller's other products
        $sellerProducts = Product::active()
            ->inStock()
            ->where('seller_id', $product->seller_id)
            ->where('id', '!=', $product->id)
            ->with(['images'])
            ->take(4)
            ->get();

        // Get product reviews with pagination
        $reviews = $product->reviews()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        // Calculate review statistics
        $reviewStats = [
            'average' => $product->getAverageRating(),
            'count' => $product->getReviewCount(),
            'distribution' => $this->getReviewDistribution($product),
        ];

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'sellerProducts',
            'reviews',
            'reviewStats'
        ));
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        return $this->index($request);
    }

    /**
     * Get product reviews for AJAX requests.
     */
    public function getReviews(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $reviews = $product->reviews()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * Get review distribution for a product.
     */
    private function getReviewDistribution(Product $product)
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $product->reviews()->where('rating', $i)->count();
            $percentage = $product->reviews()->count() > 0 
                ? ($count / $product->reviews()->count()) * 100 
                : 0;
            $distribution[$i] = [
                'count' => $count,
                'percentage' => round($percentage, 1),
            ];
        }
        return $distribution;
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

        $products = Product::active()
            ->inStock()
            ->search($query)
            ->take(5)
            ->get(['id', 'name', 'slug']);

        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'url' => route('products.show', $product->slug),
            ];
        }));
    }
}
