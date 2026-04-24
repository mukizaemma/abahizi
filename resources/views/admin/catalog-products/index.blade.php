@extends('layouts.adminbase')

@section('title', 'Products catalog')

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
                        <h1>Products (Abahizi Manufacturing)</h1>
                        <p class="text-muted mb-0">Made in Rwanda — manage catalog, pricing, and galleries (orders via “Request order” form).</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('productCategories.index') }}" class="btn btn-outline-secondary">Categories</a>
                        <a href="{{ route('catalogProducts.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Add product</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $p)
                                        <tr>
                                            <td class="fw-semibold">{{ $p->title }}</td>
                                            <td>{{ $p->category->name ?? '—' }}</td>
                                            <td>RWF {{ number_format((float) $p->price, 0) }}</td>
                                            <td>
                                                @if($p->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Hidden</span>
                                                @endif
                                                @if($p->is_new)
                                                    <span class="badge bg-info text-dark">New</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('catalogProducts.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <a href="{{ route('productShow', $p->slug) }}" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>
                                                <a href="{{ route('catalogProducts.destroy', $p->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">No products yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($products->hasPages())
                        <div class="card-footer">{{ $products->links() }}</div>
                    @endif
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
