@extends('layouts.app')

@section('title', 'My Products')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">My Products</h2>
            <p class="text-muted mb-0">Manage your product inventory</p>
        </div>
        <div>
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Product
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="mb-0">Products ({{ $products->total() }})</h6>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('seller.products') }}">All Products</a></li>
                            <li><a class="dropdown-item" href="{{ route('seller.products', ['status' => 'active']) }}">Active</a></li>
                            <li><a class="dropdown-item" href="{{ route('seller.products', ['status' => 'draft']) }}">Draft</a></li>
                            <li><a class="dropdown-item" href="{{ route('seller.products', ['status' => 'inactive']) }}">Inactive</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Sales</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->getPrimaryImageUrl() }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded me-3"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('products.show', $product->slug) }}" 
                                                       target="_blank" class="text-decoration-none">
                                                        {{ Str::limit($product->name, 40) }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">{{ $product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->sku }}</td>
                                    <td>
                                        @if($product->compare_price && $product->compare_price > $product->price)
                                            <div>
                                                <span class="text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                                                <br>
                                                <small class="text-muted text-decoration-line-through">
                                                    ${{ number_format($product->compare_price, 2) }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-danger fw-bold">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->quantity > 10 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }}">
                                            {{ $product->quantity }} in stock
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : ($product->status === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $product->order_items_count }}</strong> sold
                                            <br>
                                            <small class="text-muted">
                                                ${{ number_format($product->order_items_sum_total_price ?? 0, 2) }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('seller.products.edit', $product) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box fs-1 text-muted"></i>
                    <h5 class="mt-3">No products yet</h5>
                    <p class="text-muted">Start by adding your first product to the marketplace</p>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Your First Product
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete "<span id="deleteProductName"></span>"?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(productId, productName) {
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteForm').action = '{{ route('seller.products.delete', ':id') }}'.replace(':id', productId);
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
