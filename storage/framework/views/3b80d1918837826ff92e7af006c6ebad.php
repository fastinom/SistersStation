

<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Carousel -->
<section class="hero-carousel mb-5">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide bg-primary text-white d-flex align-items-center" style="height: 500px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h1 class="display-4 fw-bold mb-4">Welcome to Sisters Station</h1>
                                <p class="lead mb-4">Discover adorable baby wear and children's clothing from independent sellers. Quality, comfort, and style for your little ones.</p>
                                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-light btn-lg">Shop Now</a>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-balloon-heart" style="font-size: 200px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide bg-success text-white d-flex align-items-center" style="height: 500px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="display-4 fw-bold mb-4">50% Off Summer Collection</h2>
                                <p class="lead mb-4">Get ready for sunny days with our amazing summer sale. Limited time offer!</p>
                                <a href="<?php echo e(route('products.index', ['sale' => true])); ?>" class="btn btn-light btn-lg">View Sale</a>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-sun" style="font-size: 200px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide bg-info text-white d-flex align-items-center" style="height: 500px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="display-4 fw-bold mb-4">Join Our Marketplace</h2>
                                <p class="lead mb-4">Are you a seller? Join Sisters Station and reach thousands of parents looking for quality baby wear.</p>
                                <a href="<?php echo e(route('register')); ?>" class="btn btn-light btn-lg">Become a Seller</a>
                            </div>
                            <div class="col-lg-6 text-center">
                                <i class="bi bi-shop" style="font-size: 200px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Categories Section -->
<?php if($babyCategories->count() > 0): ?>
<section class="categories-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Shop by Category</h2>
            <p class="lead text-muted">Find exactly what you're looking for in our curated categories</p>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $babyCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3 col-sm-6">
                    <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="text-decoration-none">
                        <div class="card category-card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="category-icon mb-3">
                                    <?php if($category->icon): ?>
                                        <i class="<?php echo e($category->icon); ?> fs-1 text-primary"></i>
                                    <?php else: ?>
                                        <i class="bi bi-box fs-1 text-primary"></i>
                                    <?php endif; ?>
                                </div>
                                <h5 class="card-title"><?php echo e($category->name); ?></h5>
                                <p class="text-muted small"><?php echo e($category->products_count ?? 0); ?> Products</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline-primary">View All Categories</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if($featuredProducts->count() > 0): ?>
<section class="featured-products py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Featured Products</h2>
            <p class="lead text-muted">Hand-picked items from our trusted sellers</p>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
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
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-fill add-to-cart" 
                                        data-product-id="<?php echo e($product->id); ?>"
                                        data-product-name="<?php echo e($product->name); ?>"
                                        data-product-price="<?php echo e($product->price); ?>">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                                <?php if(auth()->guard()->check()): ?>
                                    <button class="btn btn-outline-secondary btn-sm add-to-wishlist" 
                                            data-product-id="<?php echo e($product->id); ?>">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-primary">View All Products</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sale Products Section -->
<?php if($saleProducts->count() > 0): ?>
<section class="sale-products py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-danger">Hot Deals</h2>
            <p class="lead text-muted">Amazing discounts on quality baby wear</p>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $saleProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-image-container position-relative">
                            <a href="<?php echo e(route('products.show', $product->slug)); ?>">
                                <img src="<?php echo e($product->getPrimaryImageUrl()); ?>" 
                                     alt="<?php echo e($product->name); ?>" 
                                     class="card-img-top product-image"
                                     style="height: 250px; object-fit: cover;">
                            </a>
                            <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                -<?php echo e($product->getDiscountPercentage()); ?>%
                            </span>
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
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <span class="text-danger fw-bold">$<?php echo e(number_format($product->price, 2)); ?></span>
                                    <span class="text-muted text-decoration-line-through small">$<?php echo e(number_format($product->compare_price, 2)); ?></span>
                                </div>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i> <?php echo e(number_format($product->getAverageRating(), 1)); ?>

                                </div>
                            </div>
                            <button class="btn btn-danger btn-sm w-100 add-to-cart" 
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
</section>
<?php endif; ?>

<!-- Top Sellers Section -->
<?php if($topSellers->count() > 0): ?>
<section class="top-sellers py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Top Sellers</h2>
            <p class="lead text-muted">Trusted sellers with excellent ratings</p>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $topSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="text-center">
                        <a href="#" class="text-decoration-none">
                            <div class="seller-avatar mb-3">
                                <?php if($seller->store_logo): ?>
                                    <img src="<?php echo e($seller->store_logo); ?>" 
                                         alt="<?php echo e($seller->store_name); ?>" 
                                         class="rounded-circle border"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-shop fs-3"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h6 class="text-dark"><?php echo e($seller->store_name); ?></h6>
                            <div class="text-warning small mb-1">
                                <i class="bi bi-star-fill"></i> <?php echo e(number_format($seller->rating, 1)); ?>

                                <small class="text-muted">(<?php echo e($seller->review_count); ?>)</small>
                            </div>
                            <small class="text-muted"><?php echo e($seller->products->count()); ?> Products</small>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="feature-icon mb-3">
                    <i class="bi bi-truck fs-1 text-primary"></i>
                </div>
                <h5>Free Shipping</h5>
                <p class="text-muted">Free shipping on orders over $50</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon mb-3">
                    <i class="bi bi-shield-check fs-1 text-success"></i>
                </div>
                <h5>Secure Payment</h5>
                <p class="text-muted">100% secure payment processing</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon mb-3">
                    <i class="bi bi-arrow-repeat fs-1 text-info"></i>
                </div>
                <h5>Easy Returns</h5>
                <p class="text-muted">30-day return policy</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon mb-3">
                    <i class="bi bi-headset fs-1 text-warning"></i>
                </div>
                <h5>24/7 Support</h5>
                <p class="text-muted">Dedicated customer support</p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.hero-carousel {
    margin-top: -1px; /* Remove gap between top bar and carousel */
}

.category-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

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

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0,123,255,0.1);
}

.carousel-item {
    transition: transform 0.6s ease-in-out;
}

@media (max-width: 768px) {
    .hero-slide {
        height: 400px !important;
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .display-5 {
        font-size: 1.5rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Add to cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';
            this.disabled = true;
            
            // Send AJAX request
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
                    
                    // Show success message
                    showToast(productName + ' added to cart!', 'success');
                    
                    // Reset button
                    this.innerHTML = originalText;
                    this.disabled = false;
                } else {
                    showToast(data.message || 'Error adding to cart', 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to cart', 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
    
    // Toast notification function
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
        
        // Remove toast element after it's hidden
        toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', () => {
            toastContainer.remove();
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/home.blade.php ENDPATH**/ ?>