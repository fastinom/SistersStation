

<?php $__env->startSection('title', 'Manage Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Products</h2>
            <p class="text-muted mb-0">Manage the product catalog</p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Product
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if($products->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Seller</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo e($product->primary_image_url); ?>" alt="<?php echo e($product->name); ?>" class="rounded me-3" style="width: 56px; height: 56px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold"><?php echo e($product->name); ?></div>
                                                <small class="text-muted">SKU: <?php echo e($product->sku); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($product->category?->name ?? '-'); ?></td>
                                    <td><?php echo e($product->seller?->store_name ?? '-'); ?></td>
                                    <td>
                                        <?php if($product->compare_price && $product->compare_price > $product->price): ?>
                                            <div>
                                                <span class="text-danger fw-bold">$<?php echo e(number_format($product->price, 2)); ?></span>
                                                <br>
                                                <small class="text-muted text-decoration-line-through">$<?php echo e(number_format($product->compare_price, 2)); ?></small>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-danger fw-bold">$<?php echo e(number_format($product->price, 2)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($product->status === 'active' ? 'success' : ($product->status === 'draft' ? 'warning' : 'secondary')); ?>">
                                            <?php echo e(ucfirst($product->status)); ?>

                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.products.delete', $product)); ?>" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($products->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-box fs-1 text-muted"></i>
                    <h5 class="mt-3">No products yet</h5>
                    <p class="text-muted">Add your first product to the catalog.</p>
                    <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">Add Product</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/admin/products/index.blade.php ENDPATH**/ ?>