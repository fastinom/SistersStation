

<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Filters</h6>
                        <a href="<?php echo e(request()->url()); ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('products.index')); ?>">
                        <!-- Search -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   value="<?php echo e(request('search')); ?>" placeholder="Search products...">
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Categories</label>
                            <div class="category-filters" style="max-height: 200px; overflow-y: auto;">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="category" 
                                               value="<?php echo e($category->slug); ?>" id="category-<?php echo e($category->id); ?>"
                                               <?php echo e(request('category') == $category->slug ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="category-<?php echo e($category->id); ?>">
                                            <?php echo e($category->name); ?>

                                            <small class="text-muted">(<?php echo e($category->products_count); ?>)</small>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Price Range</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control" 
                                           placeholder="Min" value="<?php echo e(request('min_price')); ?>" min="0" step="0.01">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control" 
                                           placeholder="Max" value="<?php echo e(request('max_price')); ?>" min="0" step="0.01">
                                </div>
                            </div>
                            <?php if($minPrice && $maxPrice): ?>
                                <small class="text-muted">Range: $<?php echo e(number_format($minPrice, 2)); ?> - $<?php echo e(number_format($maxPrice, 2)); ?></small>
                            <?php endif; ?>
                        </div>

                        <!-- Brands -->
                        <?php if($brands->count() > 0): ?>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Brands</label>
                                <div class="brand-filters" style="max-height: 150px; overflow-y: auto;">
                                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="brand" 
                                                   value="<?php echo e($brand); ?>" id="brand-<?php echo e(loop->index); ?>"
                                                   <?php echo e(request('brand') == $brand ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="brand-<?php echo e(loop->index); ?>">
                                                <?php echo e($brand); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Minimum Rating</label>
                            <?php $__currentLoopData = [5, 4, 3, 2, 1]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="rating" 
                                           value="<?php echo e($rating); ?>" id="rating-<?php echo e($rating); ?>"
                                           <?php echo e(request('rating') == $rating ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="rating-<?php echo e($rating); ?>">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo e($i <= $rating ? '-fill text-warning' : ''); ?>"></i>
                                        <?php endfor; ?>
                                        & Up
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Sort -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="latest" <?php echo e(request('sort') == 'latest' ? 'selected' : ''); ?>>Latest</option>
                                <option value="price_low" <?php echo e(request('sort') == 'price_low' ? 'selected' : ''); ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Price: High to Low</option>
                                <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?>>Highest Rated</option>
                                <option value="name_asc" <?php echo e(request('sort') == 'name_asc' ? 'selected' : ''); ?>>Name: A-Z</option>
                                <option value="name_desc" <?php echo e(request('sort') == 'name_desc' ? 'selected' : ''); ?>>Name: Z-A</option>
                                <option value="featured" <?php echo e(request('sort') == 'featured' ? 'selected' : ''); ?>>Featured</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8">
            <!-- Results Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Products</h4>
                    <p class="text-muted mb-0">
                        Showing <?php echo e($products->firstItem()); ?>-<?php echo e($products->lastItem()); ?> of <?php echo e($products->total()); ?> results
                        <?php if(request('search')): ?>
                            for "<?php echo e(request('search')); ?>"
                        <?php endif; ?>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="grid-view">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="list-view">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if($products->count() > 0): ?>
                <div class="products-grid" id="products-container">
                    <div class="row g-4">
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-4 col-md-6 product-item">
                                <div class="card product-card h-100 border-0 shadow-sm">
                                    <div class="product-image-container position-relative">
                                        <a href="<?php echo e(route('products.show', $product->slug)); ?>">
                                            <img src="<?php echo e($product->getPrimaryImageUrl()); ?>" 
                                                 alt="<?php echo e($product->name); ?>" 
                                                 class="card-img-top product-image"
                                                 style="height: 250px; object-fit: cover;">
                                        </a>
                                        <?php if($product->getDiscountPercentage()): ?>
                                            <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                                -<?php echo e($product->getDiscountPercentage()); ?>%
                                            </span>
                                        <?php endif; ?>
                                        <?php if($product->is_featured): ?>
                                            <span class="position-absolute top-0 end-0 badge bg-warning m-2">
                                                <i class="bi bi-star-fill"></i> Featured
                                            </span>
                                        <?php endif; ?>
                                        <div class="product-actions position-absolute top-50 start-50 translate-middle">
                                            <button class="btn btn-light btn-sm me-2 quick-view" data-product-id="<?php echo e($product->id); ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <?php if(auth()->guard()->check()): ?>
                                                <button class="btn btn-light btn-sm add-to-wishlist" data-product-id="<?php echo e($product->id); ?>">
                                                    <i class="bi bi-heart"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-2">
                                            <small class="text-muted"><?php echo e($product->category->name); ?></small>
                                        </div>
                                        <h6 class="card-title">
                                            <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="text-decoration-none text-dark">
                                                <?php echo e(Str::limit($product->name, 50)); ?>

                                            </a>
                                        </h6>
                                        <div class="mb-2">
                                            <small class="text-muted"><?php echo e($product->seller->store_name); ?></small>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <?php if($product->compare_price && $product->compare_price > $product->price): ?>
                                                    <span class="text-danger fw-bold">$<?php echo e(number_format($product->price, 2)); ?></span>
                                                    <span class="text-muted text-decoration-line-through small">$<?php echo e(number_format($product->compare_price, 2)); ?></span>
                                                <?php else: ?>
                                                    <span class="text-danger fw-bold">$<?php echo e(number_format($product->price, 2)); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-warning small">
                                                <i class="bi bi-star-fill"></i> <?php echo e(number_format($product->getAverageRating(), 1)); ?>

                                                <small class="text-muted">(<?php echo e($product->getReviewCount()); ?>)</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-sm w-100 add-to-cart" 
                                                data-product-id="<?php echo e($product->id); ?>"
                                                data-product-name="<?php echo e($product->name); ?>"
                                                data-product-price="<?php echo e($product->price); ?>">
                                            <i class="bi bi-cart-plus"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    <?php echo e($products->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <h5 class="mt-3">No products found</h5>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">Clear Filters</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.product-image {
    transition: transform 0.3s ease;
}

.product-image:hover {
    transform: scale(1.05);
}

.product-actions {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-image-container:hover .product-actions {
    opacity: 1;
}

.category-filters::-webkit-scrollbar,
.brand-filters::-webkit-scrollbar {
    width: 4px;
}

.category-filters::-webkit-scrollbar-track,
.brand-filters::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.category-filters::-webkit-scrollbar-thumb,
.brand-filters::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.category-filters::-webkit-scrollbar-thumb:hover,
.brand-filters::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* List view styles */
.products-list .product-item {
    margin-bottom: 1rem;
}

.products-list .product-card {
    display: flex;
    flex-direction: row;
    max-height: 200px;
}

.products-list .product-image-container {
    width: 200px;
    flex-shrink: 0;
}

.products-list .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

@media (max-width: 768px) {
    .products-list .product-card {
        flex-direction: column;
        max-height: none;
    }
    
    .products-list .product-image-container {
        width: 100%;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const productsContainer = document.getElementById('products-container');
    
    gridView.addEventListener('click', function() {
        this.classList.add('active');
        listView.classList.remove('active');
        productsContainer.classList.remove('products-list');
        productsContainer.innerHTML = '<div class="row g-4">' + productsContainer.querySelector('.row').innerHTML + '</div>';
    });
    
    listView.addEventListener('click', function() {
        this.classList.add('active');
        gridView.classList.remove('active');
        productsContainer.classList.add('products-list');
        // Convert grid to list view (simplified for this example)
        const gridItems = productsContainer.querySelectorAll('.col-lg-4, .col-md-6');
        gridItems.forEach(item => {
            item.className = 'col-12 product-item';
        });
    });

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';
            this.disabled = true;
            
            fetch('<?php echo e(route("api.cart.add-ajax")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCountElement = document.querySelector('.badge.bg-danger');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cart_count;
                    }
                    
                    showToast(productName + ' added to cart!', 'success');
                    this.innerHTML = '<i class="bi bi-check"></i> Added';
                    setTimeout(() => {
                        this.innerHTML = '<i class="bi bi-cart-plus"></i> Add to Cart';
                        this.disabled = false;
                    }, 2000);
                } else {
                    showToast(data.message || 'Error adding to cart', 'error');
                    this.innerHTML = '<i class="bi bi-cart-plus"></i> Add to Cart';
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to cart', 'error');
                this.innerHTML = '<i class="bi bi-cart-plus"></i> Add to Cart';
                this.disabled = false;
            });
        });
    });

    // Quick view functionality
    document.querySelectorAll('.quick-view').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            // Implement quick view modal
            alert('Quick view for product ' + productId);
        });
    });

    // Wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            fetch('<?php echo e(route("wishlist.add")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('btn-light');
                    this.classList.toggle('btn-danger');
                    const icon = this.querySelector('i');
                    icon.classList.toggle('bi-heart');
                    icon.classList.toggle('bi-heart-fill');
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Error updating wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating wishlist', 'error');
            });
        });
    });

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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/products/index.blade.php ENDPATH**/ ?>