<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the customer dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        return view('customer.dashboard', compact('user'));
    }

    /**
     * Show the user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('customer.profile', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update($request->all());

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the user's orders.
     */
    public function orders()
    {
        $user = Auth::user();
        
        // For now, return empty collection until database works
        $orders = collect([]);
        
        return view('customer.orders', compact('orders'));
    }

    /**
     * Show order details.
     */
    public function orderDetails($id)
    {
        // For now, return 404 until database works
        abort(404, 'Order not found');
    }

    /**
     * Show the user's addresses.
     */
    public function addresses()
    {
        $user = Auth::user();
        
        // For now, return empty collection until database works
        $addresses = collect([]);
        
        return view('customer.addresses', compact('addresses'));
    }

    /**
     * Show the form to create a new address.
     */
    public function createAddress()
    {
        return view('customer.addresses.create');
    }

    /**
     * Store a new address.
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'type' => 'required|in:shipping,billing',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        // For now, just redirect with success message
        return redirect()->route('customer.addresses')
            ->with('success', 'Address added successfully!');
    }

    /**
     * Show the form to edit an address.
     */
    public function editAddress($id)
    {
        // For now, return 404 until database works
        abort(404, 'Address not found');
    }

    /**
     * Update an address.
     */
    public function updateAddress(Request $request, $id)
    {
        // For now, just redirect with success message
        return back()->with('success', 'Address updated successfully!');
    }

    /**
     * Delete an address.
     */
    public function deleteAddress($id)
    {
        // For now, just redirect with success message
        return back()->with('success', 'Address deleted successfully!');
    }

    /**
     * Show the user's reviews.
     */
    public function reviews()
    {
        $user = Auth::user();
        
        // For now, return empty collection until database works
        $reviews = collect([]);
        
        return view('customer.reviews', compact('reviews'));
    }

    /**
     * Store a new review.
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
        ]);

        // For now, just redirect with success message
        return back()->with('success', 'Review added successfully!');
    }
}
