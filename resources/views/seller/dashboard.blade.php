@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Seller Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div>
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Product
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Products</h6>
                            <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-box fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Orders</h6>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cart-check fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Pending Orders</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['pending_orders'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-clock-history fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Monthly Sales</h6>
                            <h3 class="mb-0 text-success">${{ number_format($stats['monthly_sales'], 2) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-currency-dollar fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Sales Overview (Last 7 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Store Performance</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Average Rating</span>
                            <span class="fw-bold">{{ number_format($stats['average_rating'], 1) }}/5</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ ($stats['average_rating'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Reviews</span>
                            <span class="fw-bold">{{ $stats['review_count'] }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Sales</span>
                            <span class="fw-bold text-success">${{ number_format($stats['total_sales'], 2) }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Verification Status</span>
                            <span class="badge bg-{{ Auth::user()->seller->verification_status === 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst(Auth::user()->seller->verification_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('seller.products.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Product
                        </a>
                        <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-check me-2"></i>View Orders
                        </a>
                        <a href="{{ route('seller.analytics') }}" class="btn btn-outline-info">
                            <i class="bi bi-graph-up me-2"></i>View Analytics
                        </a>
                        <a href="{{ route('seller.profile') }}" class="btn btn-outline-warning">
                            <i class="bi bi-gear me-2"></i>Store Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Top Products -->
    <div class="row g-4 mt-2">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Orders</h6>
                    <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $orderItem)
                                        <tr>
                                            <td>
                                                <a href="{{ route('seller.orders.show', $orderItem->id) }}" class="text-decoration-none">
                                                    #{{ $orderItem->order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $orderItem->order->user->name }}</td>
                                            <td>{{ Str::limit($orderItem->product->name, 30) }}</td>
                                            <td>${{ number_format($orderItem->total_price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $orderItem->order->status === 'delivered' ? 'success' : ($orderItem->order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($orderItem->order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $orderItem->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        @foreach($topProducts as $product)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $product->getPrimaryImageUrl() }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded me-3"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($product->name, 25) }}</h6>
                                    <small class="text-muted">{{ $product->order_items_count }} sold</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">${{ number_format($product->order_items_sum_total_price, 2) }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-box fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No sales data yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesChart->pluck('date')),
            datasets: [{
                label: 'Sales',
                data: @json($salesChart->pluck('sales')),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Sales: $' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toFixed(0);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
