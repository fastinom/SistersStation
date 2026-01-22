@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Products</h2>
            <p class="text-muted mb-0">Manage the product catalog</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Product
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
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Seller</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="rounded me-3" style="width: 56px; height: 56px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->category?->name ?? '-' }}</td>
                                    <td>{{ $product->seller?->store_name ?? '-' }}</td>
                                    <td>
                                        @if($product->compare_price && $product->compare_price > $product->price)
                                            <div>
                                                <span class="text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                                                <br>
                                                <small class="text-muted text-decoration-line-through">${{ number_format($product->compare_price, 2) }}</small>
                                            </div>
                                        @else
                                            <span class="text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : ($product->status === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box fs-1 text-muted"></i>
                    <h5 class="mt-3">No products yet</h5>
                    <p class="text-muted">Add your first product to the catalog.</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
