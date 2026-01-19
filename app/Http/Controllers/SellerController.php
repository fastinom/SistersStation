<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:seller');
    }

    /**
     * Display the seller dashboard.
     */
    public function dashboard()
    {
        $seller = Auth::user()->seller;
        
        // Get dashboard statistics
        $stats = [
            'total_products' => $seller->products()->where('status', 'active')->count(),
            'total_orders' => $seller->orderItems()->count(),
            'pending_orders' => $seller->orderItems()
                ->whereHas('order', function ($query) {
                    $query->whereIn('status', ['pending', 'confirmed']);
                })
                ->count(),
            'monthly_sales' => $seller->getMonthlySales(),
            'total_sales' => $seller->total_sales,
            'average_rating' => $seller->rating,
            'review_count' => $seller->review_count,
        ];

        // Get recent orders
        $recentOrders = $seller->orderItems()
            ->with(['order.user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // Get top products
        $topProducts = $seller->products()
            ->withCount(['orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', '!=', 'cancelled');
                });
            }])
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Get sales chart data (last 7 days)
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sales = $seller->orderItems()
                ->whereHas('order', function ($query) use ($date) {
                    $query->whereDate('created_at', $date)
                          ->where('status', '!=', 'cancelled');
                })
                ->sum('total_price');
            
            $salesChart[] = [
                'date' => now()->subDays($i)->format('M d'),
                'sales' => $sales,
            ];
        }

        return view('seller.dashboard', compact(
            'stats',
            'recentOrders',
            'topProducts',
            'salesChart'
        ));
    }

    /**
     * Show the seller profile.
     */
    public function profile()
    {
        $seller = Auth::user()->seller;
        return view('seller.profile', compact('seller'));
    }

    /**
     * Update the seller profile.
     */
    public function updateProfile(Request $request)
    {
        $seller = Auth::user()->seller;
        
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string|max:2000',
            'business_email' => 'nullable|email|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_address' => 'nullable|string|max:500',
        ]);

        $seller->update($request->all());

        // Update store slug if name changed
        if ($seller->wasChanged('store_name')) {
            $seller->store_slug = Str::slug($request->store_name) . '-' . $seller->id;
            $seller->save();
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Display seller's products.
     */
    public function products()
    {
        $seller = Auth::user()->seller;
        $products = $seller->products()
            ->with(['category', 'images'])
            ->withCount(['orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', '!=', 'cancelled');
                });
            }])
            ->latest()
            ->paginate(15);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function createProduct()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('seller.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products',
            'brand' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'track_quantity' => 'boolean',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'requires_shipping' => 'boolean',
            'taxable' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tags' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $seller = Auth::user()->seller;

        $product = $seller->products()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'cost_price' => $request->cost_price,
            'sku' => $request->sku,
            'brand' => $request->brand,
            'weight' => $request->weight,
            'track_quantity' => $request->boolean('track_quantity'),
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity ?? 1,
            'requires_shipping' => $request->boolean('requires_shipping'),
            'taxable' => $request->boolean('taxable'),
            'tax_rate' => $request->tax_rate ?? 0,
            'tags' => $request->tags,
            'status' => 'active',
            'published_at' => now(),
        ]);

        // Handle product images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $request->name,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        // Update seller statistics
        $seller->updateStatistics();

        return redirect()->route('seller.products')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function editProduct(Product $product)
    {
        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $categories = Category::active()->orderBy('name')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'brand' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'track_quantity' => 'boolean',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'requires_shipping' => 'boolean',
            'taxable' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tags' => 'nullable|array',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'cost_price' => $request->cost_price,
            'sku' => $request->sku,
            'brand' => $request->brand,
            'weight' => $request->weight,
            'track_quantity' => $request->boolean('track_quantity'),
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity ?? 1,
            'requires_shipping' => $request->boolean('requires_shipping'),
            'taxable' => $request->boolean('taxable'),
            'tax_rate' => $request->tax_rate ?? 0,
            'tags' => $request->tags,
        ]);

        // Handle new product images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $request->name,
                    'sort_order' => $product->images()->max('sort_order') + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('seller.products')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product.
     */
    public function deleteProduct(Product $product)
    {
        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        // Check if product has any orders
        if ($product->orderItems()->count() > 0) {
            return back()->with('error', 'Cannot delete product with existing orders. Consider archiving instead.');
        }

        // Delete product images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        // Update seller statistics
        Auth::user()->seller->updateStatistics();

        return back()->with('success', 'Product deleted successfully!');
    }

    /**
     * Display seller's orders.
     */
    public function orders()
    {
        $seller = Auth::user()->seller;
        $orderItems = $seller->orderItems()
            ->with(['order.user', 'product', 'variant'])
            ->latest()
            ->paginate(15);

        return view('seller.orders.index', compact('orderItems'));
    }

    /**
     * Show order details.
     */
    public function orderDetails($orderItemId)
    {
        $orderItem = OrderItem::with(['order.user', 'order.addresses', 'product', 'variant'])
            ->where('seller_id', Auth::user()->seller->id)
            ->findOrFail($orderItemId);

        return view('seller.orders.show', compact('orderItem'));
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, $orderItemId)
    {
        $orderItem = OrderItem::where('seller_id', Auth::user()->seller->id)
            ->findOrFail($orderItemId);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order = $orderItem->order;
        
        // Update order status
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
        ]);

        // Set timestamps based on status
        if ($request->status === 'shipped') {
            $order->update(['shipped_at' => now()]);
        } elseif ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Display seller analytics.
     */
    public function analytics()
    {
        $seller = Auth::user()->seller;

        // Get monthly revenue for the last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = $seller->orderItems()
                ->whereHas('order', function ($query) use ($date) {
                    $query->whereMonth('created_at', $date->month)
                          ->whereYear('created_at', $date->year)
                          ->where('status', '!=', 'cancelled');
                })
                ->sum('total_price');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }

        // Get top selling products
        $topProducts = $seller->products()
            ->withCount(['orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', '!=', 'cancelled');
                });
            }])
            ->withSum(['orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', '!=', 'cancelled');
                });
            }, 'total_price')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        // Get order status distribution
        $orderStatuses = $seller->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw('orders.status, COUNT(*) as count')
            ->groupBy('orders.status')
            ->pluck('count', 'status')
            ->toArray();

        return view('seller.analytics', compact(
            'monthlyRevenue',
            'topProducts',
            'orderStatuses'
        ));
    }

    /**
     * Display seller reviews.
     */
    public function reviews()
    {
        $seller = Auth::user()->seller;
        $reviews = $seller->reviews()
            ->with('user', 'product')
            ->where('is_approved', true)
            ->latest()
            ->paginate(15);

        return view('seller.reviews', compact('reviews'));
    }
}
