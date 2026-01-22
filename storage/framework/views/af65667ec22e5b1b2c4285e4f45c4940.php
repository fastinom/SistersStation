

<?php $__env->startSection('title', 'Manage Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Categories</h2>
            <p class="text-muted mb-0">Manage category structure</p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Category
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
            <?php if($categories->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Parent</th>
                                <th>Products</th>
                                <th>Active</th>
                                <th>Featured</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($category->name); ?></td>
                                    <td><code><?php echo e($category->slug); ?></code></td>
                                    <td><?php echo e($category->parent?->name ?? '-'); ?></td>
                                    <td><?php echo e($category->products_count ?? 0); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($category->is_active ? 'success' : 'secondary'); ?>">
                                            <?php echo e($category->is_active ? 'Yes' : 'No'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($category->is_featured ? 'warning' : 'secondary'); ?>">
                                            <?php echo e($category->is_featured ? 'Yes' : 'No'); ?>

                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.categories.delete', $category)); ?>" class="d-inline" onsubmit="return confirm('Delete this category?');">
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
                    <?php echo e($categories->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-tags fs-1 text-muted"></i>
                    <h5 class="mt-3">No categories yet</h5>
                    <p class="text-muted">Create your first category.</p>
                    <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">Add Category</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>