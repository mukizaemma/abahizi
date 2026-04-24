@extends('layouts.adminbase')

@section('title', 'Product categories')

@section('sidebar')
    @parent
@endsection

@section('content')
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                    <div>
                        <h1>Product categories</h1>
                        <p class="text-muted mb-0">Used to group bags, accessories, men, women, etc.</p>
                    </div>
                    <a href="{{ route('catalogProducts.index') }}" class="btn btn-outline-primary">Back to products</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">Add category</div>
                    <div class="card-body">
                        <form action="{{ route('productCategories.store') }}" method="POST" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Women, Bags, Accessories">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sort order</label>
                                <input type="number" name="sort_order" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-2 form-check mt-4 pt-2">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="newActive" checked>
                                <label class="form-check-label" for="newActive">Active</label>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Add</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">All categories</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Sort</th>
                                        <th>Active</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $cat)
                                        <tr>
                                            <td colspan="5" class="p-0 border-0">
                                                <form action="{{ route('productCategories.update', $cat->id) }}" method="POST" class="row g-2 align-items-center p-3 border-bottom m-0">
                                                    @csrf
                                                    <div class="col-md-4">
                                                        <input type="text" name="name" value="{{ $cat->name }}" class="form-control form-control-sm" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span class="text-muted small">{{ $cat->slug }}</span>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="number" name="sort_order" value="{{ $cat->sort_order }}" class="form-control form-control-sm" min="0">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="a{{ $cat->id }}" {{ $cat->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="a{{ $cat->id }}">Active</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <button type="submit" class="btn btn-sm btn-primary me-1">Save</button>
                                                        <a href="{{ route('productCategories.destroy', $cat->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')">Delete</a>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">No categories yet. Add one above.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
