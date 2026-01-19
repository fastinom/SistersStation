@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-cart3 me-2"></i>Shopping Cart
                        <span class="badge bg-primary ms-2">{{ $cartItems->count() }} items</span>
                    </h4>
                </div>
                <div class="card-body">
                    @if($cartItems->count() > 0)
                        @foreach($cartItems as $item)
                            <div class="cart-item border-bottom pb-3 mb-3" data-item-id="{{ $item->id }}">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ $item->product_data['image'] ?? '/images/placeholder-product.jpg' }}" 
                                             alt="{{ $item->product_data['name'] }}" 
                                             class="img-fluid rounded"
                                             style="height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1">
                                            <a href="{{ route('products.show', $item->product_data['slug']) }}" class="text-decoration-none text-dark">
                                                {{ $item->product_data['name'] }}
                                            </a>
                                        </h6>
                                        @if($item->variant)
                                            <small class="text-muted">{{ $item->variant->title }}</small>
                                        @endif
                                        <div class="text-muted small">
                                            Sold by: {{ $item->product->seller->store_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group input-group-sm" style="max-width: 120px;">
                                            <button class="btn btn-outline-secondary quantity-decrease" type="button">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   value="{{ $item->quantity }}" min="1" max="99">
                                            <button class="btn btn-outline-secondary quantity-increase" type="button">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="fw-bold">${{ number_format($item->unit_price, 2) }}</div>
                                        <div class="text-muted small">each</div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <div class="fw-bold text-primary item-total">
                                            ${{ number_format($item->total_price, 2) }}
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger remove-item mt-2">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x fs-1 text-muted"></i>
                            <h5 class="mt-3">Your cart is empty</h5>
                            <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @if($cartItems->count() > 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Estimated Tax:</span>
                                <span>${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h6>Total:</h6>
                                <h6 class="text-primary">${{ number_format($total, 2) }}</h6>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Coupon code">
                            <button class="btn btn-outline-secondary btn-sm w-100 mt-2">Apply Coupon</button>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-lock me-2"></i>Proceed to Checkout
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Secure checkout powered by SSL encryption
                            </small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-receipt fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">Add items to your cart to see the order summary</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity increase/decrease handlers
    document.querySelectorAll('.quantity-increase').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            if (currentValue < 99) {
                input.value = currentValue + 1;
                updateQuantity(input);
            }
        });
    });

    document.querySelectorAll('.quantity-decrease').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateQuantity(input);
            }
        });
    });

    // Direct input change handler
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                value = 1;
            } else if (value > 99) {
                value = 99;
            }
            this.value = value;
            updateQuantity(this);
        });
    });

    // Remove item handler
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const itemId = cartItem.dataset.itemId;
            
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                removeItem(itemId, cartItem);
            }
        });
    });

    function updateQuantity(input) {
        const cartItem = input.closest('.cart-item');
        const itemId = cartItem.dataset.itemId;
        const quantity = parseInt(input.value);
        const unitPrice = parseFloat(cartItem.querySelector('.text-center .fw-bold').textContent.replace('$', ''));
        
        // Show loading state
        input.disabled = true;
        
        fetch(`{{ route('cart.update', ':id') }}`.replace(':id', itemId), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update item total
                const newTotal = unitPrice * quantity;
                cartItem.querySelector('.item-total').textContent = '$' + newTotal.toFixed(2);
                
                // Update cart summary
                updateCartSummary();
                
                // Update cart count in header
                updateCartCount();
                
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Error updating cart', 'error');
                // Reset quantity
                input.value = input.defaultValue;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating cart', 'error');
            input.value = input.defaultValue;
        })
        .finally(() => {
            input.disabled = false;
        });
    }

    function removeItem(itemId, cartItemElement) {
        fetch(`{{ route('cart.remove', ':id') }}`.replace(':id', itemId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from DOM with animation
                cartItemElement.style.transition = 'opacity 0.3s, transform 0.3s';
                cartItemElement.style.opacity = '0';
                cartItemElement.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    cartItemElement.remove();
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        location.reload();
                    } else {
                        updateCartSummary();
                        updateCartCount();
                    }
                }, 300);
                
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Error removing item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing item', 'error');
        });
    }

    function updateCartSummary() {
        fetch('{{ route("cart.summary") }}')
            .then(response => response.json())
            .then(data => {
                // Update summary values
                const summaryCard = document.querySelector('.sticky-top .card-body');
                if (summaryCard) {
                    summaryCard.querySelector('.d-flex.justify-content-between span.fw-bold').textContent = '$' + data.subtotal.toFixed(2);
                    const taxElement = summaryCard.querySelectorAll('.d-flex.justify-content-between span')[1];
                    if (taxElement) {
                        taxElement.textContent = '$' + data.tax.toFixed(2);
                    }
                    const totalElement = summaryCard.querySelector('h6.text-primary');
                    if (totalElement) {
                        totalElement.textContent = '$' + data.total.toFixed(2);
                    }
                }
            });
    }

    function updateCartCount() {
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                const cartCountElement = document.querySelector('.badge.bg-danger');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count;
                }
            });
    }

    function showToast(message, type = 'success') {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.innerHTML = toastHtml;
        
        document.body.appendChild(toastContainer);
        
        const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
        toast.show();
        
        toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', () => {
            toastContainer.remove();
        });
    }
});
</script>
@endpush
