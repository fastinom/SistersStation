@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Add Product</h2>
            <p class="text-muted mb-0">Create a new product in the catalog</p>
        </div>
        <div>
            <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">SKU *</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                        @error('sku')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Seller</label>
                        <select name="seller_id" class="form-select">
                            <option value="">Auto-pick first seller</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->store_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('seller_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                        @error('brand')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Price *</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}" required>
                        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Compare Price</label>
                        <input type="number" step="0.01" min="0" name="compare_price" class="form-control" value="{{ old('compare_price') }}">
                        @error('compare_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Quantity *</label>
                        <input type="number" min="0" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
                        @error('quantity')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            @foreach(['draft','active','inactive','archived'] as $st)
                                <option value="{{ $st }}" {{ old('status','active') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">Featured</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description') }}</textarea>
                        @error('short_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Images</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        @error('images')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @error('images.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        <small class="text-muted">Uploads are saved to <code>public/images/products</code>. First image becomes primary.</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create
                    </button>
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
