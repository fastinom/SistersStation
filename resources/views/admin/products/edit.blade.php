@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Edit Product</h2>
            <p class="text-muted mb-0">Update product details</p>
        </div>
        <div>
            <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">SKU *</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Seller *</label>
                        <select name="seller_id" class="form-select" required>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ old('seller_id', $product->seller_id) == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->store_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('seller_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                        @error('brand')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Price *</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Compare Price</label>
                        <input type="number" step="0.01" min="0" name="compare_price" class="form-control" value="{{ old('compare_price', $product->compare_price) }}">
                        @error('compare_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Quantity *</label>
                        <input type="number" min="0" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" required>
                        @error('quantity')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            @foreach(['draft','active','inactive','archived'] as $st)
                                <option value="{{ $st }}" {{ old('status', $product->status) === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">Featured</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="6" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Current Images</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->images as $img)
                                <img src="{{ $img->image_url }}" alt="{{ $img->alt_text ?? $product->name }}" class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
                            @endforeach
                            @if($product->images->count() === 0)
                                <div class="text-muted">No images uploaded yet.</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Add New Images</label>
                        <input type="file" name="new_images[]" class="form-control" accept="image/*" multiple>
                        @error('new_images')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @error('new_images.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        <small class="text-muted">Uploads are saved to <code>public/images/products</code>.</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save
                    </button>
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
