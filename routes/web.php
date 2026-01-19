<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/__ping', function () {
    return 'babywear ok';
});

// Home and Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify', [AuthController::class, 'notice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resend'])->name('verification.send');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Cart Routes (Guest accessible)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Wishlist Routes
Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist.index')->middleware('auth');
Route::post('/wishlist/add', [AccountController::class, 'addToWishlist'])->name('wishlist.add')->middleware('auth');
Route::delete('/wishlist/remove/{id}', [AccountController::class, 'removeFromWishlist'])->name('wishlist.remove')->middleware('auth');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('auth');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success')->middleware('auth');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel')->middleware('auth');

// Customer Dashboard Routes
Route::middleware(['auth', 'role:customer'])->prefix('account')->name('customer.')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderDetails'])->name('orders.show');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::get('/addresses/create', [AccountController::class, 'createAddress'])->name('addresses.create');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AccountController::class, 'editAddress'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountController::class, 'deleteAddress'])->name('addresses.delete');
    Route::get('/reviews', [AccountController::class, 'reviews'])->name('reviews');
    Route::post('/reviews', [AccountController::class, 'storeReview'])->name('reviews.store');
});

// Seller Dashboard Routes
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [SellerController::class, 'profile'])->name('profile');
    Route::put('/profile', [SellerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/products', [SellerController::class, 'products'])->name('products');
    Route::get('/products/create', [SellerController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [SellerController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [SellerController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [SellerController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [SellerController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/orders', [SellerController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [SellerController::class, 'orderDetails'])->name('orders.show');
    Route::put('/orders/{order}/status', [SellerController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::get('/analytics', [SellerController::class, 'analytics'])->name('analytics');
    Route::get('/reviews', [SellerController::class, 'reviews'])->name('reviews');
});

// Admin Panel Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/sellers', [AdminController::class, 'sellers'])->name('sellers');
    Route::get('/sellers/{seller}/edit', [AdminController::class, 'editSeller'])->name('sellers.edit');
    Route::put('/sellers/{seller}', [AdminController::class, 'updateSeller'])->name('sellers.update');
    Route::put('/sellers/{seller}/verify', [AdminController::class, 'verifySeller'])->name('sellers.verify');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'orderDetails'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

// API Routes for AJAX requests
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/search/suggestions', [ProductController::class, 'searchSuggestions'])->name('search.suggestions');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::post('/cart/add-ajax', [CartController::class, 'addAjax'])->name('cart.add-ajax');
    Route::get('/products/{product}/reviews', [ProductController::class, 'getReviews'])->name('products.reviews');
});
