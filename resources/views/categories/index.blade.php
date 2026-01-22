@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Shop by Category</h1>
        <p class="lead text-muted">Browse our wide selection of baby products by category</p>
    </div>

    @if($categories->count() > 0)
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6">
                    <div class="card category-card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fs-1 text-primary"></i>
                                @else
                                    <i class="bi bi-box fs-1 text-primary"></i>
                                @endif
                            </div>
                            <h4 class="card-title">{{ $category->name }}</h4>
                            <p class="text-muted">{{ $category->description }}</p>
                            <p class="text-muted small">{{ $category->products_count ?? 0 }} Products</p>
                            
                            @if($category->children && $category->children->count() > 0)
                                <div class="mt-3">
                                    <small class="text-muted d-block mb-2">Subcategories:</small>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        @foreach(collect($category->children)->take(3) as $child)
                                            <span class="badge bg-light text-dark">{{ $child->name }}</span>
                                        @endforeach
                                        @if(collect($category->children)->count() > 3)
                                            <span class="badge bg-light text-dark">+{{ collect($category->children)->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-primary">
                                Browse {{ $category->name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-box fs-1 text-muted"></i>
            <h3 class="mt-3">No Categories Available</h3>
            <p class="text-muted">Check back later for new categories.</p>
        </div>
    @endif
</div>
@endsection
