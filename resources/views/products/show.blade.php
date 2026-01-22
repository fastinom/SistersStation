@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('categories.show', $product->category->slug) }}" class="text-decoration-none">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image mb-3">
                    <img id="mainProductImage" 
                         src="{{ $product->images->where('is_primary', true)->first()?->image_url ?? $product->images->first()?->image_url ?? '/images/placeholder-product.jpg' }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded"
                         style="width: 100%; height: 500px; object-fit: cover;">
                </div>
                
                <!-- Thumbnail Images -->
                @if($product->images->count() > 1)
                    <div class="thumbnail-images d-flex gap-2 overflow-auto">
                        @foreach($product->images as $image)
                            <img src="{{ $image->image_url }}" 
                                 alt="{{ $image->alt_text ?? $product->name }}" 
                                 class="thumbnail img-fluid rounded border"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                 onclick="changeMainImage('{{ $image->image_url }}')">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6 mb-4">
            <div class="product-details">
                <h1 class="h3 mb-3">{{ $product->name }}</h1>
                
                <!-- Seller Info -->
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        @if($product->seller->store_logo)
                            <img src="{{ $product->seller->store_logo }}" 
                                 alt="{{ $product->seller->store_name }}" 
                                 class="rounded-circle border"
                                 style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-shop"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="fw-bold">{{ $product->seller->store_name }}</div>
                        <div class="text-warning small">
                            <i class="bi bi-star-fill"></i> {{ number_format($product->seller->rating, 1) }}
                            <small class="text-muted">({{ $product->seller->review_count }} reviews)</small>
                        </div>
                    </div>
                </div>

                <!-- Rating -->
                <div class="d-flex align-items-center mb-3">
                    <div class="text-warning me-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($reviewStats['average']) ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="me-2">{{ number_format($reviewStats['average'], 1) }}</span>
                    <a href="#reviews" class="text-decoration-none">({{ $reviewStats['count'] }} reviews)</a>
                </div>

                <!-- Price -->
                <div class="price-section mb-4">
                    @if($product->compare_price && $product->compare_price > $product->price)
                        <span class="h2 text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                        <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->compare_price, 2) }}</span>
                        <span class="badge bg-danger ms-2">Save {{ $product->getDiscountPercentage() }}%</span>
                    @else
                        <span class="h2 text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Description -->
                <div class="description mb-4">
                    <h6>Description</h6>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>

                <!-- Product Variants -->
                @if($product->variants->count() > 0)
                    <div class="variants mb-4">
                        <h6>Options</h6>
                        @foreach($product->variants->groupBy('option1') as $option1 => $variants)
                            <div class="mb-3">
                                <label class="form-label">{{ $option1 }}</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($variants as $variant)
                                        <button type="button" 
                                                class="btn btn-outline-secondary variant-option"
                                                data-variant-id="{{ $variant->id }}"
                                                data-price="{{ $variant->price }}"
                                                onclick="selectVariant(this)">
                                            {{ $variant->value1 }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Quantity -->
                <div class="quantity mb-4">
                    <label class="form-label">Quantity</label>
                    <div class="input-group" style="max-width: 200px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" id="quantity" class="form-control text-center" 
                               value="1" min="1" max="{{ $product->quantity }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    @if($product->track_quantity)
                        <small class="text-muted">{{ $product->quantity }} available</small>
                    @endif
                </div>

                <!-- Actions -->
                <div class="actions mb-4">
                    <div class="d-grid gap-2 d-md-flex">
                        <button class="btn btn-primary btn-lg flex-fill" onclick="addToCart()">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        @auth
                            <button class="btn btn-outline-secondary btn-lg" onclick="addToWishlist()">
                                <i class="bi bi-heart"></i>
                            </button>
                        @endauth
                    </div>
                </div>

                <!-- Product Features -->
                <div class="features">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-truck text-primary me-2"></i>
                                <span class="small">Free Shipping</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-arrow-repeat text-primary me-2"></i>
                                <span class="small">30-Day Returns</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-check text-primary me-2"></i>
                                <span class="small">Secure Payment</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-headset text-primary me-2"></i>
                                <span class="small">24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">
                        Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                        Reviews ({{ $reviewStats['count'] }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button">
                        Shipping & Returns
                    </button>
                </li>
            </ul>
            
            <div class="tab-content mt-3" id="productTabsContent">
                <!-- Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Product Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>SKU:</strong></td>
                                    <td>{{ $product->sku }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Brand:</strong></td>
                                    <td>{{ $product->brand ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                                @if($product->weight)
                                    <tr>
                                        <td><strong>Weight:</strong></td>
                                        <td>{{ $product->weight }} lbs</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Additional Information</h6>
                            <p>{{ $product->short_description ?? $product->description }}</p>
                            @if($product->tags)
                                <div class="mt-3">
                                    <strong>Tags:</strong>
                                    <div class="mt-2">
                                        @foreach($product->tags as $tag)
                                            <span class="badge bg-secondary me-1">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h2 class="display-4">{{ number_format($reviewStats['average'], 1) }}</h2>
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($reviewStats['average']) ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-muted">Based on {{ $reviewStats['count'] }} reviews</p>
                                
                                <!-- Rating Distribution -->
                                @foreach($reviewStats['distribution'] as $rating => $data)
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="me-2">{{ $rating }}★</span>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $data['percentage'] }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $data['count'] }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-8">
                            @auth
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h6>Write a Review</h6>
                                        <form method="POST" action="{{ route('customer.reviews.store') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="mb-3">
                                                <label class="form-label">Rating</label>
                                                <div class="rating-input">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <button type="button" class="btn btn-outline-warning rating-star" data-rating="{{ $i }}">
                                                            <i class="bi bi-star"></i>
                                                        </button>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Your Review</label>
                                                <textarea name="content" class="form-control" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit Review</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <a href="{{ route('login') }}">Login</a> to write a review.
                                </div>
                            @endif

                            <!-- Reviews List -->
                            <div id="reviews-list">
                                @foreach($reviews as $review)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <strong>{{ $review->user->name }}</strong>
                                                    <div class="text-warning small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if($review->title)
                                                <h6 class="mb-2">{{ $review->title }}</h6>
                                            @endif
                                            <p class="mb-0">{{ $review->content }}</p>
                                            @if($review->is_verified)
                                                <small class="text-success"><i class="bi bi-check-circle"></i> Verified Purchase</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($reviews->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $reviews->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Tab -->
                <div class="tab-pane fade" id="shipping" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Shipping Information</h6>
                            <ul>
                                <li>Free shipping on orders over $50</li>
                                <li>Standard shipping: 5-7 business days</li>
                                <li>Express shipping: 2-3 business days</li>
                                <li>International shipping available</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Return Policy</h6>
                            <ul>
                                <li>30-day return policy</li>
                                <li>Items must be unused and in original packaging</li>
                                <li>Free returns on defective items</li>
                                <li>Refunds processed within 5-7 business days</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h4 class="mb-4">Related Products</h4>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="product-image-container position-relative">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                    <img src="{{ $relatedProduct->getPrimaryImageUrl() }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         class="card-img-top product-image"
                                         style="height: 200px; object-fit: cover;">
                                </a>
                                @if($relatedProduct->getDiscountPercentage())
                                    <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                        -{{ $relatedProduct->getDiscountPercentage() }}%
                                    </span>
                                @endif
                            </div>
                            <div class="card-body p-3">
                                <h6 class="card-title">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($relatedProduct->name, 50) }}
                                    </a>
                                </h6>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        @if($relatedProduct->compare_price && $relatedProduct->compare_price > $relatedProduct->price)
                                            <span class="text-danger fw-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                            <span class="text-muted text-decoration-line-through small">${{ number_format($relatedProduct->compare_price, 2) }}</span>
                                        @else
                                            <span class="text-danger fw-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="text-warning small">
                                        <i class="bi bi-star-fill"></i> {{ number_format($relatedProduct->getAverageRating(), 1) }}
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-sm w-100 add-to-cart" 
                                        data-product-id="{{ $relatedProduct->id }}"
                                        data-product-name="{{ $relatedProduct->name }}"
                                        data-product-price="{{ $relatedProduct->price }}">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
let selectedVariantId = null;
let currentPrice = {{ $product->price }};

function changeMainImage(imageSrc) {
    document.getElementById('mainProductImage').src = imageSrc;
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const maxValue = parseInt(input.max);
    const currentValue = parseInt(input.value);
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

function selectVariant(button) {
    // Remove active class from all variant options in the same group
    button.parentElement.querySelectorAll('.variant-option').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-secondary');
    });
    
    // Add active class to selected variant
    button.classList.remove('btn-outline-secondary');
    button.classList.add('btn-primary');
    
    // Update selected variant and price
    selectedVariantId = button.dataset.variantId;
    currentPrice = parseFloat(button.dataset.price);
    
    // Update price display
    updatePriceDisplay();
}

function updatePriceDisplay() {
    const priceElement = document.querySelector('.price-section .h2');
    if (priceElement) {
        priceElement.textContent = '$' + currentPrice.toFixed(2);
    }
}

function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';
    button.disabled = true;
    
    fetch('{{ route("api.cart.add-ajax") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            variant_id: selectedVariantId,
            quantity: quantity
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
            
            showToast('{{ $product->name }} added to cart!', 'success');
            button.innerHTML = '<i class="bi bi-check"></i> Added';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        } else {
            showToast(data.message || 'Error adding to cart', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function addToWishlist() {
    fetch('{{ route("wishlist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: {{ $product->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = event.target.closest('button');
            button.classList.toggle('btn-outline-secondary');
            button.classList.toggle('btn-danger');
            const icon = button.querySelector('i');
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
}

// Rating stars functionality
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.querySelectorAll('.rating-star').forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('btn-outline-warning');
                s.classList.add('btn-warning');
                s.querySelector('i').classList.remove('bi-star');
                s.querySelector('i').classList.add('bi-star-fill');
            } else {
                s.classList.remove('btn-warning');
                s.classList.add('btn-outline-warning');
                s.querySelector('i').classList.remove('bi-star-fill');
                s.querySelector('i').classList.add('bi-star');
            }
        });
        document.querySelector('input[name="rating"]').value = rating;
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
</script>
@endpush
