

<?php $__env->startSection('title', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Shop by Category</h1>
        <p class="lead text-muted">Browse our wide selection of baby products by category</p>
    </div>

    <?php if($categories->count() > 0): ?>
        <div class="row g-4">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card category-card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <?php if($category->icon): ?>
                                    <i class="<?php echo e($category->icon); ?> fs-1 text-primary"></i>
                                <?php else: ?>
                                    <i class="bi bi-box fs-1 text-primary"></i>
                                <?php endif; ?>
                            </div>
                            <h4 class="card-title"><?php echo e($category->name); ?></h4>
                            <p class="text-muted"><?php echo e($category->description); ?></p>
                            <p class="text-muted small"><?php echo e($category->products_count ?? 0); ?> Products</p>
                            
                            <?php if($category->children->count() > 0): ?>
                                <div class="mt-3">
                                    <small class="text-muted d-block mb-2">Subcategories:</small>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <?php $__currentLoopData = $category->children->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark"><?php echo e($child->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($category->children->count() > 3): ?>
                                            <span class="badge bg-light text-dark">+<?php echo e($category->children->count() - 3); ?> more</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="btn btn-primary">
                                Browse <?php echo e($category->name); ?>

                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-box fs-1 text-muted"></i>
            <h3 class="mt-3">No Categories Available</h3>
            <p class="text-muted">Check back later for new categories.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/categories/index.blade.php ENDPATH**/ ?>