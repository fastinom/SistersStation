@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Categories</h2>
            <p class="text-muted mb-0">Manage category structure</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Category
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
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Parent</th>
                                <th>Products</th>
                                <th>Active</th>
                                <th>Featured</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td class="fw-semibold">{{ $category->name }}</td>
                                    <td><code>{{ $category->slug }}</code></td>
                                    <td>{{ $category->parent?->name ?? '-' }}</td>
                                    <td>{{ $category->products_count ?? 0 }}</td>
                                    <td>
                                        <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                            {{ $category->is_active ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $category->is_featured ? 'warning' : 'secondary' }}">
                                            {{ $category->is_featured ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.delete', $category) }}" class="d-inline" onsubmit="return confirm('Delete this category?');">
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
                    {{ $categories->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-tags fs-1 text-muted"></i>
                    <h5 class="mt-3">No categories yet</h5>
                    <p class="text-muted">Create your first category.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
