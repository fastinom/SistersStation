

<?php $__env->startSection('title', 'Customer Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-person fs-1 text-white"></i>
                        </div>
                        <h5 class="mb-1"><?php echo e($user->name); ?></h5>
                        <p class="text-muted small"><?php echo e($user->email); ?></p>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a href="<?php echo e(route('customer.dashboard')); ?>" class="nav-link active">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="<?php echo e(route('customer.profile')); ?>" class="nav-link">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                        <a href="<?php echo e(route('customer.orders')); ?>" class="nav-link">
                            <i class="bi bi-box me-2"></i> Orders
                        </a>
                        <a href="<?php echo e(route('customer.addresses')); ?>" class="nav-link">
                            <i class="bi bi-geo-alt me-2"></i> Addresses
                        </a>
                        <a href="<?php echo e(route('customer.reviews')); ?>" class="nav-link">
                            <i class="bi bi-star me-2"></i> Reviews
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Welcome back, <?php echo e($user->name); ?>!</h5>
                </div>
                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="bi bi-box fs-1 text-primary mb-2"></i>
                                <h4>0</h4>
                                <p class="text-muted mb-0">Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="bi bi-star fs-1 text-warning mb-2"></i>
                                <h4>0</h4>
                                <p class="text-muted mb-0">Reviews</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="bi bi-geo-alt fs-1 text-success mb-2"></i>
                                <h4>0</h4>
                                <p class="text-muted mb-0">Addresses</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="mb-4">
                        <h6 class="mb-3">Recent Orders</h6>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mb-0">No orders yet</p>
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-sm mt-2">
                                Start Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <h6 class="mb-3">Quick Actions</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-search me-2"></i> Browse Products
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo e(route('customer.profile')); ?>" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-person me-2"></i> Update Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/customer/dashboard.blade.php ENDPATH**/ ?>