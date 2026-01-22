<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sisters Station - Baby Wear Marketplace'); ?> | Sisters Station</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-light">
    <!-- Top Bar -->
    <div class="bg-dark text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="d-inline-block me-3">
                        <i class="bi bi-telephone me-1"></i> +1 (555) 123-4567
                    </small>
                    <small class="d-inline-block">
                        <i class="bi bi-envelope me-1"></i> info@sistersstation.com
                    </small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="d-inline-block me-3">
                        <i class="bi bi-truck me-1"></i> Free Shipping on Orders $50+
                    </small>
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-white text-decoration-none me-2">
                            <i class="bi bi-person me-1"></i> Login
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="text-white text-decoration-none">
                            <i class="bi bi-person-plus me-1"></i> Register
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isSeller() ? route('seller.dashboard') : route('customer.dashboard'))); ?>" class="text-white text-decoration-none me-2">
                            <i class="bi bi-person me-1"></i> <?php echo e(Auth::user()->name); ?>

                        </a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-link text-white text-decoration-none p-0">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="<?php echo e(Auth::check() && Auth::user()->isAdmin() ? route('admin.dashboard') : route('home')); ?>">
                <i class="bi bi-shop me-2"></i>Sisters Station
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('home') || request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(Auth::check() && Auth::user()->isAdmin() ? route('admin.dashboard') : route('home')); ?>">
                            Home
                        </a>
                    </li>
                    <?php if(Auth::check() && Auth::user()->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.categories*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.categories')); ?>">
                                Categories
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Categories
                            </a>
                            <ul class="dropdown-menu">
                                <?php $__currentLoopData = ($navCategories ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo e(route('categories.show', $category->slug)); ?>">
                                            <?php echo e($category->name); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('categories.index')); ?>">All Categories</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" href="<?php echo e(route('products.index')); ?>">
                            Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('about') ? 'active' : ''); ?>" href="<?php echo e(route('about')); ?>">
                            About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>" href="<?php echo e(route('contact')); ?>">
                            Contact
                        </a>
                    </li>
                </ul>
                
                <!-- Search and Cart -->
                <div class="d-flex align-items-center">
                    <!-- Search -->
                    <form class="d-flex me-3" action="<?php echo e(route('products.search')); ?>" method="GET">
                        <input class="form-control form-control-sm" type="search" name="q" placeholder="Search products..." value="<?php echo e(request('q')); ?>">
                        <button class="btn btn-outline-primary btn-sm ms-1" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    
                    <!-- Wishlist -->
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('wishlist.index')); ?>" class="btn btn-outline-secondary btn-sm me-2 position-relative">
                            <i class="bi bi-heart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo e(Auth::user()->wishlist()->count()); ?>

                            </span>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Cart -->
                    <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-primary btn-sm position-relative">
                        <i class="bi bi-cart3"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo e(\App\Helpers\CartHelper::getCartCount()); ?>

                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-shop me-2"></i>Sisters Station
                    </h5>
                    <p class="text-white-50">
                        Your trusted marketplace for quality baby wear and children's clothing. 
                        Connect with independent sellers and find the perfect items for your little ones.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white-50"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-white-50"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-white-50"><i class="bi bi-pinterest fs-5"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo e(route('home')); ?>" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="<?php echo e(route('products.index')); ?>" class="text-white-50 text-decoration-none">Products</a></li>
                        <li class="mb-2"><a href="<?php echo e(route('categories.index')); ?>" class="text-white-50 text-decoration-none">Categories</a></li>
                        <li class="mb-2"><a href="<?php echo e(route('about')); ?>" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="<?php echo e(route('contact')); ?>" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Customer Service</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Shipping Info</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Returns</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Size Guide</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Care Instructions</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Newsletter</h6>
                    <p class="text-white-50 mb-3">Subscribe to get special offers and updates</p>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Your email">
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                    
                    <div class="mt-4">
                        <h6 class="mb-3">Payment Methods</h6>
                        <div class="d-flex gap-2">
                            <i class="bi bi-credit-card fs-4"></i>
                            <i class="bi bi-paypal fs-4"></i>
                            <i class="bi bi-apple fs-4"></i>
                            <i class="bi bi-google fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="border-white-50 my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0">
                        &copy; <?php echo e(date('Y')); ?> Sisters Station. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50 text-decoration-none me-3">Terms of Service</a>
                    <a href="#" class="text-white-50 text-decoration-none">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\babywear\resources\views/layouts/app.blade.php ENDPATH**/ ?>