@extends('layouts.adminbase')

@section('title', 'Products')

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
                <div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h1>Products</h1>
                        <p class="text-muted mb-0">Add real catalog items here. If you only want photos on the public Products page, use the gallery tab instead.</p>
                    </div>
                    <a href="{{ route('catalogProducts.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Add product</a>
                </div>

                @include('admin.includes.products-tabs', ['tab' => 'products'])

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 4.5rem;">Photo</th>
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
                                            <td>
                                                @if($p->adminThumbUrl())
                                                    <img src="{{ $p->adminThumbUrl() }}" alt="" width="56" height="72" class="rounded border" style="object-fit: cover;">
                                                @else
                                                    <span class="text-muted small">No photo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $p->title }}</div>
                                            </td>
                                            <td>{{ $p->category->name ?? '—' }}</td>
                                            <td>RWF {{ number_format((float) $p->price, 0) }}</td>
                                            <td>
                                                @if($p->is_active)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-secondary">Hidden</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('catalogProducts.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <a href="{{ route('catalogProducts.destroy', $p->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">No catalog products yet. Add one, or fill the Products page gallery with photos instead.</td>
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
