@extends('layouts.app')

@section('title', 'Order Successful')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h1 class="mb-3">Order Confirmed!</h1>
                <p class="lead text-muted">
                    Thank you for your order. We've received your order and will begin processing it right away.
                </p>
            </div>

            <!-- Order Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Order Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Number:</strong>
                            <p class="mb-0">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Order Date:</strong>
                            <p class="mb-0">{{ $order->created_at->format('F j, Y, g:i A') }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Payment Status:</strong>
                            <p class="mb-0">
                                <span class="badge bg-success">Paid</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Method:</strong>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Billing Address:</strong>
                            <p class="mb-0">
                                {{ $order->billing_address['first_name'] }} {{ $order->billing_address['last_name'] }}<br>
                                {{ $order->billing_address['address_line_1'] }}<br>
                                @if($order->billing_address['address_line_2'])
                                    {{ $order->billing_address['address_line_2'] }}<br>
                                @endif
                                {{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['postal_code'] }}<br>
                                {{ $order->billing_address['country'] }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Shipping Address:</strong>
                            <p class="mb-0">
                                {{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}<br>
                                {{ $order->shipping_address['address_line_1'] }}<br>
                                @if($order->shipping_address['address_line_2'])
                                    {{ $order->shipping_address['address_line_2'] }}<br>
                                @endif
                                {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['postal_code'] }}<br>
                                {{ $order->shipping_address['country'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Order Items</h6>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="{{ $item->product_data['image'] }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="rounded me-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                @if($item->variant_title)
                                    <small class="text-muted">{{ $item->variant_title }}</small><br>
                                @endif
                                <small class="text-muted">Sold by: {{ $item->seller->store_name }}</small><br>
                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">${{ number_format($item->total_price, 2) }}</div>
                                <small class="text-muted">${{ number_format($item->unit_price, 2) }} each</small>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Order Totals -->
                    <div class="border-top pt-3">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>${{ number_format($order->shipping_amount, 2) }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-danger">Discount:</span>
                                        <span class="text-danger">-${{ number_format($order->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <strong class="text-primary">${{ number_format($order->total_amount, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">What's Next?</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mb-3">
                                <i class="bi bi-envelope text-primary fs-2"></i>
                            </div>
                            <h6>Order Confirmation</h6>
                            <p class="text-muted small">We've sent a confirmation email to {{ $order->customer_email }}</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mb-3">
                                <i class="bi bi-box-seam text-info fs-2"></i>
                            </div>
                            <h6>Order Processing</h6>
                            <p class="text-muted small">Sellers will prepare your items for shipping</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mb-3">
                                <i class="bi bi-truck text-success fs-2"></i>
                            </div>
                            <h6>Shipping</h6>
                            <p class="text-muted small">You'll receive tracking information once shipped</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="text-center">
                <a href="{{ route('customer.orders') }}" class="btn btn-primary me-2">
                    <i class="bi bi-list-check me-2"></i>View My Orders
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-icon {
    animation: scaleIn 0.5s ease-in-out;
}

.step-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content-center;
    border-radius: 50%;
    background: rgba(0,123,255,0.1);
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
@endpush
