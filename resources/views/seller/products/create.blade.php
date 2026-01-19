@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Add New Product</h2>
            <p class="text-muted mb-0">Create a new product listing for your store</p>
        </div>
        <div>
            <a href="{{ route('seller.products') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Product Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Product Name *</label>
                                <input type="text" name="name" class="form-control" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SKU *</label>
                                <input type="text" name="sku" class="form-control" 
                                       value="{{ old('sku') }}" required>
                                @error('sku')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Brand</label>
                            <input type="text" name="brand" class="form-control" 
                                   value="{{ old('brand') }}" placeholder="e.g., Carter's, Gerber, etc.">
                            @error('brand')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" class="form-control" rows="2" 
                                      placeholder="Brief product description (max 500 characters)">{{ old('short_description') }}</textarea>
                            <small class="text-muted">This will appear in product listings</small>
                            @error('short_description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Full Description *</label>
                            <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pricing -->
                        <h6 class="mb-3">Pricing</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Sale Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="price" class="form-control" 
                                           step="0.01" min="0" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Compare Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="compare_price" class="form-control" 
                                           step="0.01" min="0" value="{{ old('compare_price') }}">
                                </div>
                                <small class="text-muted">Original price for sale display</small>
                                @error('compare_price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cost Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="cost_price" class="form-control" 
                                           step="0.01" min="0" value="{{ old('cost_price') }}">
                                </div>
                                <small class="text-muted">For your reference only</small>
                                @error('cost_price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Inventory -->
                        <h6 class="mb-3">Inventory</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="track_quantity" 
                                           id="trackQuantity" {{ old('track_quantity', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="trackQuantity">
                                        Track Quantity
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quantity *</label>
                                <input type="number" name="quantity" class="form-control" 
                                       min="0" value="{{ old('quantity', 0) }}" required>
                                @error('quantity')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Min Quantity</label>
                                <input type="number" name="min_quantity" class="form-control" 
                                       min="1" value="{{ old('min_quantity', 1) }}">
                                @error('min_quantity')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Weight (lbs)</label>
                                <input type="number" name="weight" class="form-control" 
                                       step="0.01" min="0" value="{{ old('weight') }}">
                                @error('weight')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Shipping -->
                        <h6 class="mb-3">Shipping</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="requires_shipping" 
                                           id="requiresShipping" {{ old('requires_shipping', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requiresShipping">
                                        Requires Shipping
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="taxable" 
                                           id="taxable" {{ old('taxable', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="taxable">
                                        Taxable
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="mb-4">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags[]" class="form-control" 
                                   placeholder="Enter tags separated by commas"
                                   value="{{ old('tags') ? implode(', ', old('tags')) : '' }}">
                            <small class="text-muted">e.g., baby boy, newborn, cotton, summer</small>
                        </div>

                        <!-- Images -->
                        <h6 class="mb-3">Product Images</h6>
                        <div class="mb-4">
                            <label class="form-label">Upload Images</label>
                            <input type="file" name="images[]" class="form-control" 
                                   accept="image/*" multiple>
                            <small class="text-muted">You can upload multiple images. First image will be the primary image.</small>
                            <div class="mt-3" id="imagePreview"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Product
                            </button>
                            <a href="{{ route('seller.products') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Tips -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Tips for Better Listings
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Use clear, descriptive product names
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Include size, age range, and material details
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Upload high-quality images from multiple angles
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Set competitive pricing based on market research
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Use relevant tags to improve discoverability
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Keep inventory levels accurate to avoid overselling
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Category Guidelines -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Category Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">Choose the most specific category for your product:</p>
                    <ul class="small mb-0">
                        <li>Clothing → Onesies, Dresses, Sets</li>
                        <li>Accessories → Hats, Socks, Bibs</li>
                        <li>Toys → Soft Toys, Educational Toys</li>
                        <li>Nursery → Bedding, Decor, Storage</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.querySelector('input[name="images[]"]');
    const previewContainer = document.getElementById('imagePreview');
    
    imageInput.addEventListener('change', function(e) {
        previewContainer.innerHTML = '';
        
        Array.from(e.target.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'col-md-3 mb-2';
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="img-fluid rounded border" 
                             style="height: 100px; object-fit: cover;">
                        <small class="d-block text-muted mt-1">Image ${index + 1}</small>
                    `;
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Tags input handling
    const tagsInput = document.querySelector('input[name="tags[]"]');
    tagsInput.addEventListener('blur', function() {
        const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
        this.value = tags.join(', ');
    });
});
</script>
@endpush
