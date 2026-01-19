<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Coupon;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cartItems = CartHelper::getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add some products before checkout.');
        }

        $user = Auth::user();
        $subtotal = CartHelper::getSubtotal();
        $tax = CartHelper::getEstimatedTax();
        $shipping = 0; // Free shipping for orders over $50
        $total = $subtotal + $tax + $shipping;

        // Get user addresses
        $billingAddress = $user->getDefaultBillingAddress();
        $shippingAddress = $user->getDefaultShippingAddress();

        // Get all addresses for selection
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'shipping',
            'total',
            'user',
            'billingAddress',
            'shippingAddress',
            'addresses'
        ));
    }

    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        $request->validate([
            'billing_address_id' => 'required|exists:addresses,id',
            'shipping_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,paypal,stripe',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $cartItems = CartHelper::getCartItems();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Verify addresses belong to user
        $billingAddress = Address::where('id', $request->billing_address_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $shippingAddress = Address::where('id', $request->shipping_address_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $subtotal = CartHelper::getSubtotal();
            $tax = CartHelper::getEstimatedTax();
            $shipping = 0; // Free shipping
            $discountAmount = 0;

            // Apply coupon if provided
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    })
                    ->first();

                if ($coupon) {
                    $discountAmount = $this->calculateCouponDiscount($coupon, $subtotal);
                }
            }

            $totalAmount = $subtotal + $tax + $shipping - $discountAmount;

            // Create order
            $order = Order::create([
                'order_number' => 'SS' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'customer_first_name' => $user->name,
                'customer_last_name' => '',
                'billing_address' => $this->formatAddress($billingAddress),
                'shipping_address' => $this->formatAddress($shippingAddress),
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'seller_id' => $cartItem->product->seller_id,
                    'product_name' => $cartItem->product->name,
                    'variant_title' => $cartItem->variant?->title,
                    'sku' => $cartItem->variant?->sku ?? $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'tax_amount' => $cartItem->total_price * 0.08, // 8% tax
                    'product_data' => [
                        'name' => $cartItem->product->name,
                        'slug' => $cartItem->product->slug,
                        'image' => $cartItem->product->getPrimaryImageUrl(),
                    ],
                ]);

                // Update product stock
                $product = $cartItem->product;
                if ($product->track_quantity) {
                    $product->decrement('quantity', $cartItem->quantity);
                }
            }

            // Update coupon usage if used
            if (isset($coupon)) {
                $coupon->increment('used_count');
            }

            // Clear cart
            CartHelper::clearCart();

            // Process payment (simplified - in production, integrate with actual payment gateways)
            $this->processPayment($order, $request->payment_method);

            DB::commit();

            return redirect()->route('checkout.success', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    /**
     * Display order success page.
     */
    public function success($orderId)
    {
        $order = Order::with(['orderItems.product', 'orderItems.seller'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return view('checkout.success', compact('order'));
    }

    /**
     * Display order cancel page.
     */
    public function cancel()
    {
        return view('checkout.cancel');
    }

    /**
     * Calculate coupon discount.
     */
    private function calculateCouponDiscount($coupon, $subtotal)
    {
        $discount = 0;

        switch ($coupon->type) {
            case 'percentage':
                $discount = $subtotal * ($coupon->value / 100);
                break;
            case 'fixed_amount':
                $discount = min($coupon->value, $subtotal);
                break;
            case 'free_shipping':
                $discount = 0; // Shipping is already free
                break;
        }

        // Apply maximum discount limit
        if ($coupon->maximum_discount) {
            $discount = min($discount, $coupon->maximum_discount);
        }

        return $discount;
    }

    /**
     * Format address for storage.
     */
    private function formatAddress($address)
    {
        return [
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'company' => $address->company,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'phone' => $address->phone,
        ];
    }

    /**
     * Process payment (simplified implementation).
     */
    private function processPayment($order, $paymentMethod)
    {
        // In a real implementation, this would integrate with:
        // - Stripe for credit card payments
        // - PayPal for PayPal payments
        // - Other payment providers
        
        // For now, we'll simulate successful payment
        $order->update([
            'payment_status' => 'paid',
            'transaction_id' => 'txn_' . strtoupper(Str::random(16)),
            'status' => 'confirmed',
        ]);

        // Send order confirmation email
        // Mail::to($order->customer_email)->send(new OrderConfirmation($order));
    }

    /**
     * Validate coupon via AJAX.
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.',
            ]);
        }

        // Check usage limits
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon usage limit exceeded.',
            ]);
        }

        // Check user usage limit
        if ($coupon->usage_limit_per_user) {
            $userUsage = Order::where('user_id', Auth::id())
                ->whereHas('coupon', function ($query) use ($coupon) {
                    $query->where('id', $coupon->id);
                })
                ->count();

            if ($userUsage >= $coupon->usage_limit_per_user) {
                return response()->json([
                    'valid' => false,
                    'message' => 'You have reached the usage limit for this coupon.',
                ]);
            }
        }

        $subtotal = CartHelper::getSubtotal();
        $discount = $this->calculateCouponDiscount($coupon, $subtotal);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'message' => 'Coupon applied successfully!',
        ]);
    }
}
