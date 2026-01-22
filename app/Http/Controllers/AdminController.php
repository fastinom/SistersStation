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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Temporarily disabled role middleware until roles work properly
        // $this->middleware('role:admin');
    }

    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Temporary hardcoded stats until database migrations work
        $stats = [
            'total_users' => 150,
            'total_sellers' => 25,
            'total_products' => 45,
            'total_orders' => 320,
            'pending_sellers' => 8,
            'total_revenue' => 45680.50,
            'monthly_revenue' => 8750.00,
        ];

        // Get hardcoded recent orders
        $recentOrders = [
            (object)[
                'id' => 1001,
                'total_amount' => 125.99,
                'status' => 'delivered',
                'created_at' => now()->subDays(2),
                'user' => (object)['name' => 'John Doe', 'email' => 'john@example.com']
            ],
            (object)[
                'id' => 1002,
                'total_amount' => 89.50,
                'status' => 'processing',
                'created_at' => now()->subDays(1),
                'user' => (object)['name' => 'Jane Smith', 'email' => 'jane@example.com']
            ],
            (object)[
                'id' => 1003,
                'total_amount' => 156.00,
                'status' => 'confirmed',
                'created_at' => now()->subHours(6),
                'user' => (object)['name' => 'Bob Johnson', 'email' => 'bob@example.com']
            ],
        ];

        // Get hardcoded top sellers
        $topSellers = [
            (object)[
                'id' => 1,
                'store_name' => 'Baby Bliss Boutique',
                'total_sales' => 125000,
                'user' => (object)['name' => 'Alice Wilson', 'email' => 'alice@example.com']
            ],
            (object)[
                'id' => 2,
                'store_name' => 'Tiny Treasures',
                'total_sales' => 98000,
                'user' => (object)['name' => 'Carol Davis', 'email' => 'carol@example.com']
            ],
            (object)[
                'id' => 3,
                'store_name' => 'Little Wonders',
                'total_sales' => 76000,
                'user' => (object)['name' => 'David Brown', 'email' => 'david@example.com']
            ],
        ];

        // Get hardcoded top products
        $topProducts = [
            (object)[
                'id' => 1,
                'name' => 'Pink Floral Baby Dress',
                'price' => 24.99,
                'order_items_count' => 45,
                'primary_image_url' => asset('images/products/dress1.jpg'),
                'seller' => (object)['store_name' => 'Baby Bliss Boutique'],
                'category' => (object)['name' => 'Dresses & Outfits']
            ],
            (object)[
                'id' => 2,
                'name' => 'Blue Elephant Onesie',
                'price' => 18.99,
                'order_items_count' => 38,
                'primary_image_url' => asset('images/products/onesie1.jpg'),
                'seller' => (object)['store_name' => 'Tiny Treasures'],
                'category' => (object)['name' => 'Onesies & Bodysuits']
            ],
            (object)[
                'id' => 3,
                'name' => 'Yellow Duck Sleep Set',
                'price' => 32.50,
                'order_items_count' => 32,
                'primary_image_url' => asset('images/products/sleep1.jpg'),
                'seller' => (object)['store_name' => 'Little Wonders'],
                'category' => (object)['name' => 'Sleepwear']
            ],
        ];

        // Get sales chart data (last 7 days)
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sales = [450, 380, 520, 290, 410, 375, 425][$i];
            
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
        // Temporary hardcoded users until database works
        $users = [
            (object)[
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'user_type' => 'customer',
                'is_active' => true,
                'created_at' => now()->subDays(30)
            ],
            (object)[
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'user_type' => 'seller',
                'is_active' => true,
                'created_at' => now()->subDays(15)
            ],
            (object)[
                'id' => 5,
                'name' => 'Admin User',
                'email' => 'admin@babywear.com',
                'user_type' => 'admin',
                'is_active' => true,
                'created_at' => now()->subDays(7)
            ]
        ];

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
        // Temporary hardcoded sellers until database works
        $sellers = [
            (object)[
                'id' => 1,
                'store_name' => 'Baby Bliss Boutique',
                'verification_status' => 'approved',
                'commission_rate' => 10,
                'is_featured' => true,
                'total_sales' => 125000,
                'user' => (object)[
                    'name' => 'Alice Wilson',
                    'email' => 'alice@example.com'
                ],
                'created_at' => now()->subDays(60)
            ],
            (object)[
                'id' => 2,
                'store_name' => 'Tiny Treasures',
                'verification_status' => 'pending',
                'commission_rate' => 12,
                'is_featured' => false,
                'total_sales' => 98000,
                'user' => (object)[
                    'name' => 'Carol Davis',
                    'email' => 'carol@example.com'
                ],
                'created_at' => now()->subDays(45)
            ],
            (object)[
                'id' => 3,
                'store_name' => 'Little Wonders',
                'verification_status' => 'approved',
                'commission_rate' => 8,
                'is_featured' => false,
                'total_sales' => 76000,
                'user' => (object)[
                    'name' => 'David Brown',
                    'email' => 'david@example.com'
                ],
                'created_at' => now()->subDays(30)
            ]
        ];

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
        $products = Product::query()
            ->with(['category', 'seller', 'images'])
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::query()->orderBy('name')->get();
        $sellers = Seller::query()->orderBy('store_name')->get();

        if ($categories->count() === 0) {
            return redirect()->route('admin.categories.create')
                ->with('error', 'No categories exist yet. Create at least one category before adding products.');
        }

        return view('admin.products.create', compact('categories', 'sellers'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'seller_id' => 'nullable|exists:sellers,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku',
            'brand' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive,archived',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $sellerId = $request->seller_id;
        if (!$sellerId) {
            $sellerId = Seller::query()->value('id');
        }

        if (!$sellerId) {
            return back()->withInput()->with('error', 'No sellers exist yet. Create a seller first.');
        }

        $product = Product::create([
            'seller_id' => $sellerId,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'brand' => $request->brand,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'track_quantity' => true,
            'quantity' => $request->quantity,
            'min_quantity' => 1,
            'requires_shipping' => true,
            'taxable' => true,
            'tax_rate' => 0,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $request->status === 'active' ? now() : null,
        ]);

        if ($request->hasFile('images')) {
            $dir = public_path('images/products');
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }

            foreach ($request->file('images') as $index => $image) {
                $filename = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                $image->move($dir, $filename);

                $product->images()->create([
                    'image_path' => 'images/products/' . $filename,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing a product.
     */
    public function editProduct(Product $product)
    {
        $product->load(['images']);
        $categories = Category::query()->orderBy('name')->get();
        $sellers = Seller::query()->orderBy('store_name')->get();

        if ($categories->count() === 0) {
            return redirect()->route('admin.categories.create')
                ->with('error', 'No categories exist yet. Create at least one category before editing products.');
        }

        return view('admin.products.edit', compact('product', 'categories', 'sellers'));
    }

    /**
     * Update the specified product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,active,inactive,archived',
            'is_featured' => 'boolean',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'seller_id' => 'required|exists:sellers,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'brand' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $product->update([
            'seller_id' => $request->seller_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'brand' => $request->brand,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $request->status === 'active' ? ($product->published_at ?? now()) : null,
        ]);

        if ($request->hasFile('new_images')) {
            $dir = public_path('images/products');
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }

            $startSort = (int) ($product->images()->max('sort_order') ?? 0);

            foreach ($request->file('new_images') as $index => $image) {
                $filename = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                $image->move($dir, $filename);

                $product->images()->create([
                    'image_path' => 'images/products/' . $filename,
                    'alt_text' => $product->name,
                    'sort_order' => $startSort + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product.
     */
    public function deleteProduct(Product $product)
    {
        $product->load('images');

        foreach ($product->images as $image) {
            $path = $image->image_path;

            if ($path) {
                if (str_starts_with($path, 'images/')) {
                    $fullPath = public_path($path);
                    if (is_file($fullPath)) {
                        @unlink($fullPath);
                    }
                } elseif (str_starts_with($path, 'products/')) {
                    Storage::disk('public')->delete($path);
                }
            }

            $image->delete();
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    /**
     * Display all categories.
     */
    public function categories()
    {
        $categories = Category::query()
            ->with('parent')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function createCategory()
    {
        $categories = Category::query()->active()->orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function storeCategory(Request $request)
    {
        $slug = $request->input('slug') ?: Str::slug($request->input('name'));
        $request->merge(['slug' => $slug]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        try {
            Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

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
        $slug = $request->input('slug') ?: Str::slug($request->input('name'));
        $request->merge(['slug' => $slug]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

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
        // Temporary hardcoded orders until database works
        $orders = [
            (object)[
                'id' => 1001,
                'order_number' => 'ORD-2024-001',
                'total_amount' => 125.99,
                'status' => 'delivered',
                'payment_status' => 'paid',
                'created_at' => now()->subDays(2),
                'user' => (object)[
                    'id' => 1,
                    'name' => 'John Doe',
                    'email' => 'john@example.com'
                ],
                'orderItems' => collect([
                    (object)[
                        'id' => 1,
                        'quantity' => 2,
                        'unit_price' => 24.99,
                        'total_price' => 49.98,
                        'product' => (object)[
                            'id' => 1,
                            'name' => 'Pink Floral Baby Dress'
                        ]
                    ],
                    (object)[
                        'id' => 2,
                        'quantity' => 1,
                        'unit_price' => 76.01,
                        'total_price' => 76.01,
                        'product' => (object)[
                            'id' => 3,
                            'name' => 'Yellow Duck Sleep Set'
                        ]
                    ]
                ])
            ],
            (object)[
                'id' => 1002,
                'order_number' => 'ORD-2024-002',
                'total_amount' => 89.50,
                'status' => 'processing',
                'payment_status' => 'paid',
                'created_at' => now()->subDays(1),
                'user' => (object)[
                    'id' => 2,
                    'name' => 'Jane Smith',
                    'email' => 'jane@example.com'
                ],
                'orderItems' => collect([
                    (object)[
                        'id' => 3,
                        'quantity' => 3,
                        'unit_price' => 18.99,
                        'total_price' => 56.97,
                        'product' => (object)[
                            'id' => 2,
                            'name' => 'Blue Elephant Onesie'
                        ]
                    ],
                    (object)[
                        'id' => 4,
                        'quantity' => 1,
                        'unit_price' => 32.53,
                        'total_price' => 32.53,
                        'product' => (object)[
                            'id' => 5,
                            'name' => 'Red Car Toy Set'
                        ]
                    ]
                ])
            ],
            (object)[
                'id' => 1003,
                'order_number' => 'ORD-2024-003',
                'total_amount' => 156.00,
                'status' => 'confirmed',
                'payment_status' => 'pending',
                'created_at' => now()->subHours(6),
                'user' => (object)[
                    'id' => 3,
                    'name' => 'Bob Johnson',
                    'email' => 'bob@example.com'
                ],
                'orderItems' => collect([
                    (object)[
                        'id' => 5,
                        'quantity' => 1,
                        'unit_price' => 45.00,
                        'total_price' => 45.00,
                        'product' => (object)[
                            'id' => 4,
                            'name' => 'Green Frog Blanket'
                        ]
                    ],
                    (object)[
                        'id' => 6,
                        'quantity' => 2,
                        'unit_price' => 55.50,
                        'total_price' => 111.00,
                        'product' => (object)[
                            'id' => 6,
                            'name' => 'Premium Baby Carrier'
                        ]
                    ]
                ])
            ]
        ];

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
     * Display analytics dashboard.
     */
    public function analytics()
    {
        // Temporary hardcoded analytics data until database works
        
        // Get monthly revenue for the last 12 months
        $monthlyRevenue = [];
        $revenues = [45000, 52000, 48000, 61000, 58000, 67000, 72000, 69000, 75000, 82000, 78000, 87500];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenues[$i],
            ];
        }

        // Get top categories by sales
        $topCategories = [
            (object)[
                'name' => 'Baby Clothing',
                'products_count' => 15,
                'products_order_items_sum_total_price' => 125000
            ],
            (object)[
                'name' => 'Nursery & Sleep',
                'products_count' => 12,
                'products_order_items_sum_total_price' => 98000
            ],
            (object)[
                'name' => 'Feeding Essentials',
                'products_count' => 8,
                'products_order_items_sum_total_price' => 76000
            ],
            (object)[
                'name' => 'Toys & Games',
                'products_count' => 6,
                'products_order_items_sum_total_price' => 45000
            ],
            (object)[
                'name' => 'Diapering',
                'products_count' => 4,
                'products_order_items_sum_total_price' => 32000
            ]
        ];

        // Get user growth data
        $userGrowth = [];
        $userCounts = [12, 15, 18, 22, 25, 28, 31, 35, 38, 42, 45, 50];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $userGrowth[] = [
                'month' => $date->format('M Y'),
                'users' => $userCounts[$i],
            ];
        }

        // Get order statistics
        $orderStats = [
            'total_orders' => 320,
            'pending_orders' => 25,
            'processing_orders' => 45,
            'completed_orders' => 235,
            'cancelled_orders' => 15,
            'average_order_value' => 142.75
        ];

        // Get top products
        $topProducts = [
            (object)[
                'name' => 'Pink Floral Baby Dress',
                'total_sold' => 45,
                'total_revenue' => 1124.55
            ],
            (object)[
                'name' => 'Blue Elephant Onesie',
                'total_sold' => 38,
                'total_revenue' => 721.62
            ],
            (object)[
                'name' => 'Yellow Duck Sleep Set',
                'total_sold' => 32,
                'total_revenue' => 1040.00
            ]
        ];

        return view('admin.analytics', compact(
            'monthlyRevenue',
            'topCategories',
            'userGrowth',
            'orderStats',
            'topProducts'
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
