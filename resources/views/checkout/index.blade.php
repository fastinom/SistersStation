@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Cart</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>

    <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
        @csrf
        <div class="row">
            <!-- Order Summary -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items -->
                        <div class="mb-4">
                            @foreach($cartItems as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $item->product_data['image'] }}" 
                                         alt="{{ $item->product_data['name'] }}" 
                                         class="rounded me-3"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ Str::limit($item->product_data['name'], 30) }}</h6>
                                        <small class="text-muted">Qty: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</small>
                                    </div>
                                    <div class="fw-bold">
                                        ${{ number_format($item->total_price, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Coupon Code -->
                        <div class="mb-4">
                            <label class="form-label">Coupon Code</label>
                            <div class="input-group">
                                <input type="text" name="coupon_code" class="form-control" 
                                       placeholder="Enter coupon code" id="couponCode">
                                <button type="button" class="btn btn-outline-secondary" id="applyCoupon">
                                    Apply
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2"></div>
                        </div>

                        <!-- Order Totals -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Estimated Tax:</span>
                                <span>${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" id="discountRow" style="display: none;">
                                <span class="text-danger">Discount:</span>
                                <span class="text-danger" id="discountAmount">-$0.00</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h6>Total:</h6>
                                <h6 class="text-primary" id="totalAmount">${{ number_format($total, 2) }}</h6>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mt-4">
                            <label class="form-label">Payment Method</label>
                            <div class="payment-methods">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="credit_card">
                                        <i class="bi bi-credit-card me-2"></i>Credit/Debit Card
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="paypal" value="paypal">
                                    <label class="form-check-label" for="paypal">
                                        <i class="bi bi-paypal me-2"></i>PayPal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="stripe" value="stripe">
                                    <label class="form-check-label" for="stripe">
                                        <i class="bi bi-credit-card-2-back me-2"></i>Stripe
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="placeOrderBtn">
                                <i class="bi bi-lock me-2"></i>Place Order
                            </button>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Secure checkout powered by SSL encryption
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="col-lg-8">
                <!-- Billing Address -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Billing Address</h6>
                    </div>
                    <div class="card-body">
                        @if($addresses->count() > 0)
                            <div class="row g-3 mb-3">
                                @foreach($addresses as $address)
                                    @if($address->type === 'billing')
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="billing_address_id" 
                                                       id="billing_{{ $address->id }}" value="{{ $address->id }}"
                                                       {{ $billingAddress && $billingAddress->id === $address->id ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label" for="billing_{{ $address->id }}">
                                                    <div class="border rounded p-3">
                                                        <div class="fw-bold">{{ $address->first_name }} {{ $address->last_name }}</div>
                                                        <div class="small text-muted">
                                                            {{ $address->address_line_1 }}<br>
                                                            @if($address->address_line_2){{ $address->address_line_2 }}<br>@endif
                                                            {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                                            {{ $address->country }}<br>
                                                            {{ $address->phone }}
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <a href="{{ route('customer.addresses.create', ['type' => 'billing']) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-plus-circle me-2"></i>Add New Billing Address
                            </a>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-house fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No billing addresses found</p>
                                <a href="{{ route('customer.addresses.create', ['type' => 'billing']) }}" 
                                   class="btn btn-primary">
                                    Add Billing Address
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Shipping Address</h6>
                    </div>
                    <div class="card-body">
                        @if($addresses->count() > 0)
                            <div class="row g-3 mb-3">
                                @foreach($addresses as $address)
                                    @if($address->type === 'shipping')
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipping_address_id" 
                                                       id="shipping_{{ $address->id }}" value="{{ $address->id }}"
                                                       {{ $shippingAddress && $shippingAddress->id === $address->id ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label" for="shipping_{{ $address->id }}">
                                                    <div class="border rounded p-3">
                                                        <div class="fw-bold">{{ $address->first_name }} {{ $address->last_name }}</div>
                                                        <div class="small text-muted">
                                                            {{ $address->address_line_1 }}<br>
                                                            @if($address->address_line_2){{ $address->address_line_2 }}<br>@endif
                                                            {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                                            {{ $address->country }}<br>
                                                            {{ $address->phone }}
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <a href="{{ route('customer.addresses.create', ['type' => 'shipping']) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-plus-circle me-2"></i>Add New Shipping Address
                            </a>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-truck fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No shipping addresses found</p>
                                <a href="{{ route('customer.addresses.create', ['type' => 'shipping']) }}" 
                                   class="btn btn-primary">
                                    Add Shipping Address
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Order Notes (Optional)</h6>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Add any special instructions for your order..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let discountAmount = 0;
    const subtotal = {{ $subtotal }};
    const tax = {{ $tax }};
    const shipping = {{ $shipping }};
    
    // Apply coupon
    document.getElementById('applyCoupon').addEventListener('click', function() {
        const couponCode = document.getElementById('couponCode').value.trim();
        const messageDiv = document.getElementById('couponMessage');
        
        if (!couponCode) {
            messageDiv.innerHTML = '<span class="text-danger">Please enter a coupon code.</span>';
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<i class="bi bi-hourglass-split"></i> Applying...';
        
        fetch('{{ route("api.coupon.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: couponCode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                discountAmount = data.discount;
                document.getElementById('discountAmount').textContent = '-$' + discountAmount.toFixed(2);
                document.getElementById('discountRow').style.display = 'flex';
                updateTotal();
                messageDiv.innerHTML = '<span class="text-success">' + data.message + '</span>';
            } else {
                messageDiv.innerHTML = '<span class="text-danger">' + data.message + '</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.innerHTML = '<span class="text-danger">Error applying coupon.</span>';
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = 'Apply';
        });
    });
    
    function updateTotal() {
        const total = subtotal + tax + shipping - discountAmount;
        document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
    }
    
    // Form submission
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('placeOrderBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    });
});
</script>
@endpush
