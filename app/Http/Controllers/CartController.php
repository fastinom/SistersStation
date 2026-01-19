<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cartItems = CartHelper::getCartItems();
        $subtotal = CartHelper::getSubtotal();
        $tax = CartHelper::getEstimatedTax();
        $total = CartHelper::getEstimatedTotal();

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $result = CartHelper::addToCart(
            $request->product_id,
            $request->variant_id,
            $request->quantity
        );

        if ($request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->route('cart.index')
                ->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Add product to cart via AJAX.
     */
    public function addAjax(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $result = CartHelper::addToCart(
            $request->product_id,
            $request->variant_id ?? null,
            $request->quantity
        );

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'cart_count' => CartHelper::getCartCount(),
        ]);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $result = CartHelper::updateCartItem($id, $request->quantity);

        if ($request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        $result = CartHelper::removeFromCart($id);

        if (request()->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        $result = CartHelper::clearCart();

        if ($result['success']) {
            return redirect()->route('cart.index')
                ->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Get cart count for AJAX requests.
     */
    public function getCartCount()
    {
        return response()->json([
            'count' => CartHelper::getCartCount(),
        ]);
    }

    /**
     * Get cart summary for checkout.
     */
    public function getCartSummary()
    {
        $cartItems = CartHelper::getCartItems();
        $subtotal = CartHelper::getSubtotal();
        $tax = CartHelper::getEstimatedTax();
        $total = CartHelper::getEstimatedTotal();

        return response()->json([
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'image' => $item->product->getPrimaryImageUrl(),
                ];
            }),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'count' => CartHelper::getCartCount(),
        ]);
    }
}
