@extends('layouts.adminbase')

@section('title', 'Products page gallery')

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
                <div class="admin-page-header mb-3">
                    <h1>Products</h1>
                    <p class="text-muted mb-0">Photos on the public Products page. Use this when you do not want to list sellable catalog items. Headings for that page (Work with us, gallery, catalog) are on the Final products tab.</p>
                </div>
                @include('admin.includes.products-tabs', ['tab' => 'gallery'])
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h5">Add a photo</h2>
                        <form action="{{ route('catalogProducts.pageGallery.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            <div class="col-md-8">
                                <label class="form-label">Photo</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Caption <span class="text-muted">(optional)</span></label>
                                <input type="text" name="caption" class="form-control" maxlength="255" value="{{ old('caption') }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Add to gallery</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row g-3">
                    @forelse($images as $image)
                        <div class="col-md-4 col-lg-3">
                            <div class="border rounded-3 overflow-hidden h-100">
                                <img src="{{ $image->url() }}" alt="{{ $image->caption ?: 'Product photo' }}" class="w-100" style="height: 200px; object-fit: cover;">
                                <div class="p-2">
                                    <div class="small text-muted mb-2">{{ $image->caption ?: 'No caption' }}</div>
                                    <form action="{{ route('catalogProducts.pageGallery.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Remove this photo from the products page?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="admin-empty-state">
                                <i class="fas fa-images d-block"></i>
                                <p class="mb-0">No products-page photos yet.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
