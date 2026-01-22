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
        $categories = Category::query()
            ->active()
            ->root()
            ->with('children')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category and its products.
     */
    public function show($slug)
    {
        $category = Category::query()
            ->active()
            ->where('slug', $slug)
            ->with('children')
            ->firstOrFail();

        $products = Product::query()
            ->active()
            ->where('category_id', $category->id)
            ->with(['category', 'seller', 'images'])
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}
