

<?php $__env->startSection('title', 'About Us'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- About Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">About Sisters Station</h1>
                <p class="lead text-muted">Your trusted marketplace for quality baby wear and children's clothing</p>
            </div>

            <!-- Our Story -->
            <section class="mb-5">
                <h2 class="h3 mb-4">Our Story</h2>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-3">
                            Welcome to Sisters Station, your premier online marketplace for adorable and high-quality baby wear and children's clothing. Founded by two sisters with a passion for children's fashion, we've created a platform that connects loving parents with independent sellers who share our commitment to quality, comfort, and style.
                        </p>
                        <p class="mb-3">
                            What started as a small dream has grown into a thriving community of sellers and customers who believe that every child deserves to wear comfortable, safe, and beautiful clothing. We carefully curate our marketplace to ensure that every product meets our high standards for quality and safety.
                        </p>
                        <p>
                            Whether you're looking for everyday essentials, special occasion outfits, or unique handmade items, Sisters Station is here to help you find the perfect pieces for your little ones.
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-balloon-heart" style="font-size: 150px; opacity: 0.3; color: #667eea;"></i>
                    </div>
                </div>
            </section>

            <!-- Our Mission -->
            <section class="mb-5 bg-light p-4 rounded">
                <h2 class="h3 mb-4 text-center">Our Mission</h2>
                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <i class="bi bi-shield-check fs-1 text-primary mb-3"></i>
                        <h5>Quality Assurance</h5>
                        <p class="text-muted">We ensure every product meets our strict quality and safety standards for your peace of mind.</p>
                    </div>
                    <div class="col-md-4 mb-4">
                        <i class="bi bi-people fs-1 text-success mb-3"></i>
                        <h5>Support Small Business</h5>
                        <p class="text-muted">We empower independent sellers and small businesses to reach customers worldwide.</p>
                    </div>
                    <div class="col-md-4 mb-4">
                        <i class="bi bi-heart fs-1 text-danger mb-3"></i>
                        <h5>Community Focus</h5>
                        <p class="text-muted">Building a community where parents can find unique, lovingly-made products.</p>
                    </div>
                </div>
            </section>

            <!-- Why Choose Us -->
            <section class="mb-5">
                <h2 class="h3 mb-4">Why Choose Sisters Station?</h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <div>
                                <h5>Curated Selection</h5>
                                <p class="text-muted mb-0">Every seller and product is carefully reviewed to ensure quality and authenticity.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <div>
                                <h5>Secure Shopping</h5>
                                <p class="text-muted mb-0">Safe and secure payment processing with buyer protection guarantees.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <div>
                                <h5>Unique Finds</h5>
                                <p class="text-muted mb-0">Discover one-of-a-kind items you won't find in big box stores.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                            <div>
                                <h5>Customer Support</h5>
                                <p class="text-muted mb-0">Friendly and responsive customer service to help with any questions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="text-center bg-primary text-white p-5 rounded">
                <h2 class="mb-3">Join Our Community</h2>
                <p class="mb-4">Whether you're a parent looking for quality products or a seller wanting to share your creations, we'd love to have you!</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-person-plus me-2"></i>Sign Up
                    </a>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-bag me-2"></i>Shop Now
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\babywear\resources\views/about.blade.php ENDPATH**/ ?>