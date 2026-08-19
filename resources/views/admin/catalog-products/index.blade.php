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
                        <p class="text-muted mb-0">The catalog is for items you sell or quote. The three homepage cards below can show photos even when there is nothing in the catalog.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('productCategories.index') }}" class="btn btn-outline-secondary">Categories</a>
                        <a href="{{ route('catalogProducts.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Add product</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-1">Homepage cards</h2>
                        <p class="text-muted mb-4">These three photos appear on the homepage under <strong>Built for partners who care how things are made.</strong> You do not need a product in the catalog. Portrait photos about 900×1200 work well.</p>
                        <form action="{{ route('catalogProducts.homepageCards') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                @foreach($homepageSlots as $slot)
                                    <div class="col-md-4">
                                        <div class="admin-image-card h-100">
                                            <label class="form-label fw-semibold">Card {{ $slot['slot'] }}</label>
                                            <input type="text" class="form-control mb-2" name="{{ $slot['title_field'] }}" value="{{ old($slot['title_field'], $slot['title']) }}" placeholder="{{ $slot['placeholder'] }}" maxlength="80">
                                            <input type="file" class="form-control" name="{{ $slot['image_field'] }}" accept="image/*">
                                            @if($slot['src'])
                                                <img src="{{ $slot['src'] }}" class="admin-preview-img mt-2" alt="Homepage card {{ $slot['slot'] }}">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input" name="clear_{{ $slot['image_field'] }}" value="1" id="clear_card_{{ $slot['slot'] }}">
                                                    <label class="form-check-label small" for="clear_card_{{ $slot['slot'] }}">Remove this photo</label>
                                                </div>
                                            @endif
                                            @error($slot['image_field'])
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save me-1"></i> Save homepage cards</button>
                        </form>
                    </div>
                </div>

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
                                            <td colspan="6" class="text-center text-muted py-5">No products yet.</td>
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
