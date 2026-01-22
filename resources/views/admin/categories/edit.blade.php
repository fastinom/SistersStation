@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Edit Category</h2>
            <p class="text-muted mb-0">Update category details</p>
        </div>
        <div>
            <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
                        @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Parent</label>
                        <select name="parent_id" class="form-select">
                            <option value="">None (root)</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}">
                        @error('sort_order')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                            @error('is_active')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-check form-switch ms-3">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">Featured</label>
                            @error('is_featured')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save
                    </button>
                    <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
