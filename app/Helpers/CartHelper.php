<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartHelper
{
    /**
     * Get cart items for current user or session.
     */
    public static function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())
                ->with(['product', 'variant'])
                ->get();
        } else {
            $sessionId = Session::getId();
            return CartItem::where('session_id', $sessionId)
                ->with(['product', 'variant'])
                ->get();
        }
    }

    /**
     * Get cart total amount.
     */
    public static function getCartTotal()
    {
        return self::getCartItems()->sum('total_price');
    }

    /**
     * Get cart item count.
     */
    public static function getCartCount()
    {
        return self::getCartItems()->sum('quantity');
    }

    /**
     * Add item to cart.
     */
    public static function addToCart($productId, $variantId = null, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        
        if (!$product->isInStock()) {
            return ['success' => false, 'message' => 'Product is out of stock'];
        }

        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : Session::getId();

        // Check if item already exists in cart
        $existingItem = CartItem::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
        ->where('product_id', $productId)
        ->where('variant_id', $variantId)
        ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            
            // Check stock
            if ($product->track_quantity && $newQuantity > $product->quantity) {
                return ['success' => false, 'message' => 'Not enough stock available'];
            }

            $existingItem->update([
                'quantity' => $newQuantity,
                'total_price' => $existingItem->unit_price * $newQuantity,
            ]);

            return ['success' => true, 'message' => 'Cart updated successfully'];
        } else {
            // Check stock
            if ($product->track_quantity && $quantity > $product->quantity) {
                return ['success' => false, 'message' => 'Not enough stock available'];
            }

            // Determine price
            $unitPrice = $product->price;
            if ($variantId) {
                $variant = $product->variants()->find($variantId);
                if ($variant) {
                    $unitPrice = $variant->price;
                }
            }

            CartItem::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $quantity,
                'product_data' => [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->getPrimaryImageUrl(),
                ],
            ]);

            return ['success' => true, 'message' => 'Product added to cart'];
        }
    }

    /**
     * Update cart item quantity.
     */
    public static function updateCartItem($itemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($itemId);
        
        // Check ownership
        if (Auth::check() && $cartItem->user_id !== Auth::id()) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        
        if (!Auth::check() && $cartItem->session_id !== Session::getId()) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return ['success' => true, 'message' => 'Item removed from cart'];
        }

        // Check stock
        $product = $cartItem->product;
        if ($product->track_quantity && $quantity > $product->quantity) {
            return ['success' => false, 'message' => 'Not enough stock available'];
        }

        $cartItem->update([
            'quantity' => $quantity,
            'total_price' => $cartItem->unit_price * $quantity,
        ]);

        return ['success' => true, 'message' => 'Cart updated successfully'];
    }

    /**
     * Remove item from cart.
     */
    public static function removeFromCart($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        
        // Check ownership
        if (Auth::check() && $cartItem->user_id !== Auth::id()) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        
        if (!Auth::check() && $cartItem->session_id !== Session::getId()) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        $cartItem->delete();
        return ['success' => true, 'message' => 'Item removed from cart'];
    }

    /**
     * Clear cart.
     */
    public static function clearCart()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            CartItem::where('session_id', Session::getId())->delete();
        }
        
        return ['success' => true, 'message' => 'Cart cleared successfully'];
    }

    /**
     * Merge guest cart with user cart after login.
     */
    public static function mergeGuestCart($user)
    {
        $sessionId = Session::getId();
        $guestCartItems = CartItem::where('session_id', $sessionId)->get();

        foreach ($guestCartItems as $guestItem) {
            // Check if user already has this item in cart
            $existingItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $guestItem->product_id)
                ->where('variant_id', $guestItem->variant_id)
                ->first();

            if ($existingItem) {
                // Update quantity
                $newQuantity = $existingItem->quantity + $guestItem->quantity;
                
                // Check stock
                $product = $existingItem->product;
                if (!$product->track_quantity || $newQuantity <= $product->quantity) {
                    $existingItem->update([
                        'quantity' => $newQuantity,
                        'total_price' => $existingItem->unit_price * $newQuantity,
                    ]);
                }
                $guestItem->delete();
            } else {
                // Transfer to user cart
                $guestItem->update([
                    'user_id' => $user->id,
                    'session_id' => null,
                ]);
            }
        }
    }

    /**
     * Get cart subtotal (without tax and shipping).
     */
    public static function getSubtotal()
    {
        return self::getCartItems()->sum('total_price');
    }

    /**
     * Calculate estimated tax.
     */
    public static function getEstimatedTax()
    {
        $subtotal = self::getSubtotal();
        return $subtotal * 0.08; // 8% tax rate (configurable)
    }

    /**
     * Get estimated total with tax.
     */
    public static function getEstimatedTotal()
    {
        return self::getSubtotal() + self::getEstimatedTax();
    }
}
