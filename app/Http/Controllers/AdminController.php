<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => Seller::count(),
            'total_products' => Product::where('status', 'active')->count(),
            'total_orders' => Order::count(),
            'pending_sellers' => Seller::where('verification_status', 'pending')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'monthly_revenue' => Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
        ];

        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get top sellers
        $topSellers = Seller::with('user')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        // Get top products
        $topProducts = Product::with(['seller', 'category'])
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
            $sales = Order::whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            $salesChart[] = [
                'date' => now()->subDays($i)->format('M d'),
                'sales' => $sales,
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topSellers',
            'topProducts',
            'salesChart'
        ));
    }

    /**
     * Display all users.
     */
    public function users()
    {
        $users = User::with('seller')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing a user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'user_type' => 'required|in:customer,seller,admin',
            'is_active' => 'boolean',
        ]);

        $user->update($request->all());

        // Update role
        $user->syncRoles([$request->user_type]);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    /**
     * Display all sellers.
     */
    public function sellers()
    {
        $sellers = Seller::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Show the form for editing a seller.
     */
    public function editSeller(Seller $seller)
    {
        $seller->load('user');
        return view('admin.sellers.edit', compact('seller'));
    }

    /**
     * Update the specified seller.
     */
    public function updateSeller(Request $request, Seller $seller)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'verification_status' => 'required|in:pending,approved,rejected',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_featured' => 'boolean',
        ]);

        $seller->update($request->all());

        // Set verified_at timestamp if approved
        if ($request->verification_status === 'approved' && !$seller->verified_at) {
            $seller->update(['verified_at' => now()]);
        }

        return redirect()->route('admin.sellers')
            ->with('success', 'Seller updated successfully!');
    }

    /**
     * Verify seller.
     */
    public function verifySeller(Request $request, Seller $seller)
    {
        $request->validate([
            'verification_status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:verification_status,rejected|string|max:1000',
        ]);

        $seller->update([
            'verification_status' => $request->verification_status,
            'rejection_reason' => $request->rejection_reason,
            'verified_at' => $request->verification_status === 'approved' ? now() : null,
        ]);

        return back()->with('success', 'Seller verification status updated!');
    }

    /**
     * Display all products.
     */
    public function products()
    {
        $products = Product::with(['seller', 'category'])
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for editing a product.
     */
    public function editProduct(Product $product)
    {
        $product->load(['seller', 'category', 'images']);
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,active,inactive,archived',
            'is_featured' => 'boolean',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product.
     */
    public function deleteProduct(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    /**
     * Display all categories.
     */
    public function categories()
    {
        $categories = Category::with('parent', 'children')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function createCategory()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing a category.
     */
    public function editCategory(Category $category)
    {
        $categories = Category::active()->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category.
     */
    public function deleteCategory(Category $category)
    {
        // Check if category has children
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

    /**
     * Display all orders.
     */
    public function orders()
    {
        $orders = Order::with('user', 'orderItems.product')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order details.
     */
    public function orderDetails(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'orderItems.variant', 'addresses']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'tracking_number' => 'nullable|string|max:100',
        ]);

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
     * Display analytics.
     */
    public function analytics()
    {
        // Get monthly revenue for the last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }

        // Get top categories by sales
        $topCategories = Category::withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->withSum(['products.orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', '!=', 'cancelled');
                });
            }], 'total_price')
            ->orderBy('products_order_items_sum_total_price', 'desc')
            ->take(10)
            ->get();

        // Get user growth data
        $userGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $users = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $userGrowth[] = [
                'month' => $date->format('M Y'),
                'users' => $users,
            ];
        }

        return view('admin.analytics', compact(
            'monthlyRevenue',
            'topCategories',
            'userGrowth'
        ));
    }

    /**
     * Show settings page.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'site_description' => 'nullable|string|max:1000',
            'maintenance_mode' => 'boolean',
        ]);

        // Store settings in cache or database
        // This is a simplified version - in production, you'd use a proper settings system
        
        return back()->with('success', 'Settings updated successfully!');
    }
}
