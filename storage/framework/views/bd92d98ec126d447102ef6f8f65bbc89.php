

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Admin Dashboard</h2>
            <p class="text-muted mb-0">Manage your marketplace</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-gear me-1"></i>Quick Actions
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo e(route('admin.sellers')); ?>">Review Sellers</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('admin.products')); ?>">Manage Products</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('admin.orders')); ?>">View Orders</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('admin.settings')); ?>">Settings</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['total_users'])); ?></h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +12 this month
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-people fs-4 text-primary"></i>
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
                            <h6 class="text-muted mb-2">Total Sellers</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['total_sellers'])); ?></h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +3 this month
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-shop fs-4 text-success"></i>
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
                            <h6 class="text-muted mb-2">Total Products</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['total_products'])); ?></h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +8 this month
                            </small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-box fs-4 text-info"></i>
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
                            <h3 class="mb-0"><?php echo e(number_format($stats['total_orders'])); ?></h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +45 this month
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cart-check fs-4 text-warning"></i>
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
                            <h6 class="text-muted mb-2">Total Revenue</h6>
                            <h3 class="mb-0">$<?php echo e(number_format($stats['total_revenue'], 2)); ?></h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +15% this month
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-currency-dollar fs-4 text-success"></i>
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
                            <h6 class="text-muted mb-2">Pending Sellers</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['pending_sellers'])); ?></h3>
                            <small class="text-warning">
                                <i class="bi bi-clock"></i> Need review
                            </small>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
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
                            <h6 class="text-muted mb-2">Monthly Revenue</h6>
                            <h3 class="mb-0">$<?php echo e(number_format($stats['monthly_revenue'], 2)); ?></h3>
                            <small class="text-info">
                                <i class="bi bi-graph-up"></i> Current month
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-graph-up fs-4 text-primary"></i>
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
                    <h6 class="mb-0">Revenue Overview (Last 7 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Order Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Orders</span>
                            <span class="fw-bold"><?php echo e(number_format($stats['total_orders'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Pending Orders</span>
                            <span class="fw-bold text-warning">25</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Completed Orders</span>
                            <span class="fw-bold text-success">280</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Cancelled Orders</span>
                            <span class="fw-bold text-danger">15</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">System Health</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Database</span>
                            <span class="badge bg-success">Healthy</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Storage</span>
                            <span class="badge bg-success">85% Free</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Last Backup</span>
                            <span class="badge bg-success">2 hours ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Top Sellers -->
    <div class="row g-4 mt-2">
        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Orders</h6>
                    <a href="<?php echo e(route('admin.orders')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if(count($recentOrders) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="text-decoration-none">
                                                    #<?php echo e($order->id); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($order->user->name); ?></td>
                                            <td>$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning')); ?>">
                                                    <?php echo e(ucfirst($order->status)); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($order->created_at->diffForHumans()); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No orders yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Sellers -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Top Sellers</h6>
                    <a href="<?php echo e(route('admin.sellers')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if(count($topSellers) > 0): ?>
                        <?php $__currentLoopData = $topSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <span class="badge bg-primary rounded-circle p-2">
                                        <?php echo e($index + 1); ?>

                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo e($seller->store_name); ?></h6>
                                    <small class="text-muted"><?php echo e($seller->user->name); ?></small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Sales</small>
                                    <div class="fw-bold">$<?php echo e(number_format($seller->total_sales, 2)); ?></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-shop fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No sellers yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Top Products</h6>
                    <a href="<?php echo e(route('admin.products')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if(count($topProducts) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Seller</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo e($product->primary_image_url); ?>" 
                                                         alt="<?php echo e($product->name); ?>" 
                                                         class="rounded me-2"
                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                    <?php echo e(Str::limit($product->name, 30)); ?>

                                                </div>
                                            </td>
                                            <td><?php echo e($product->seller->store_name); ?></td>
                                            <td><?php echo e($product->category->name); ?></td>
                                            <td>$<?php echo e(number_format($product->price, 2)); ?></td>
                                            <td><?php echo e($product->order_items_count); ?></td>
                                            <td>$<?php echo e(number_format($product->order_items_count * $product->price, 2)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-box fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No products yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(collect($salesChart)->pluck('date'), 15, 512) ?>,
            datasets: [{
                label: 'Revenue',
                data: <?php echo json_encode(collect($salesChart)->pluck('sales'), 15, 512) ?>,
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
                            return 'Revenue: $' + context.parsed.y.toFixed(2);
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>