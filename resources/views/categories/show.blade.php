@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold">{{ $category->name }}</h1>
            <p class="lead text-muted">{{ $category->description }}</p>
            <p class="text-muted">{{ $products->total() }} products found</p>
        </div>
        <div class="col-md-4 text-center">
            @if($category->icon)
                <i class="{{ $category->icon }}" style="font-size: 100px; opacity: 0.3;"></i>
            @else
                <i class="bi bi-box" style="font-size: 100px; opacity: 0.3;"></i>
            @endif
        </div>
    </div>

    <!-- Subcategories -->
    @if($category->children->count() > 0)
        <div class="mb-5">
            <h3 class="mb-3">Subcategories</h3>
            <div class="row g-3">
                @foreach($category->children as $child)
                    <div class="col-md-4 col-sm-6">
                        <a href="{{ route('categories.show', $child->slug) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-3">
                                    <div class="mb-2">
                                        @if($child->icon)
                                            <i class="{{ $child->icon }} fs-3 text-primary"></i>
                                        @else
                                            <i class="bi bi-box fs-3 text-primary"></i>
                                        @endif
                                    </div>
                                    <h6 class="card-title mb-1">{{ $child->name }}</h6>
                                    <small class="text-muted">{{ $child->description }}</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Products -->
    @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-image-container position-relative">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $product->getPrimaryImageUrl() }}" 
                                     alt="{{ $product->name }}" 
                                     class="card-img-top product-image"
                                     style="height: 250px; object-fit: cover;">
                            </a>
                            @if($product->getDiscountPercentage())
                                <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                    -{{ $product->getDiscountPercentage() }}%
                                </span>
                            @endif
                            @if($product->is_featured)
                                <span class="position-absolute top-0 end-0 badge bg-warning m-2">
                                    <i class="bi bi-star-fill"></i> Featured
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-2">
                                <small class="text-muted">{{ $product->category->name }}</small>
                            </div>
                            <h6 class="card-title">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 50) }}
                                </a>
                            </h6>
                            <div class="mb-2">
                                <small class="text-muted">{{ $product->seller->store_name }}</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    @if($product->compare_price && $product->compare_price > $product->price)
                                        <span class="text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-muted text-decoration-line-through small">${{ number_format($product->compare_price, 2) }}</span>
                                    @else
                                        <span class="text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i> {{ number_format($product->getAverageRating(), 1) }}
                                    <small class="text-muted">({{ $product->getReviewCount() }})</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-fill add-to-cart" 
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->price }}">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                                @auth
                                    <button class="btn btn-outline-secondary btn-sm add-to-wishlist" 
                                            data-product-id="{{ $product->id }}">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-box fs-1 text-muted"></i>
            <h3 class="mt-3">No Products Available</h3>
            <p class="text-muted">There are currently no products in this category.</p>
        </div>
    @endif
</div>
@endsection
