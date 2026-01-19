<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category and its products.
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['children' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->firstOrFail();

        // Get products in this category and its subcategories
        $categoryIds = $category->descendants()->pluck('id')->push($category->id);
        
        $products = Product::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->with(['seller', 'category'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}
