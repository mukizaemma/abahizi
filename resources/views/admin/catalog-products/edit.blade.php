@extends('layouts.adminbase')

@section('title', 'Edit product')

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
                <div class="mb-4 d-flex justify-content-between flex-wrap gap-2">
                    <a href="{{ route('catalogProducts.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
                    <a href="{{ route('productShow', $product->slug) }}" class="btn btn-outline-primary btn-sm" target="_blank">View on site</a>
                </div>
                <h1 class="mb-4">Edit: {{ $product->title }}</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('catalogProducts.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm mb-4">
                    @csrf
                    <div class="card-body row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title', $product->title) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select name="product_category_id" class="form-select" required>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('product_category_id', $product->product_category_id) == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="8" class="form-control">{!! old('description', $product->description) !!}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price (RWF)</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" required value="{{ old('price', $product->price) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Compare at price</label>
                            <input type="number" name="compare_at_price" class="form-control" step="0.01" min="0" value="{{ old('compare_at_price', $product->compare_at_price) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sort order</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $product->sort_order) }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-3 pb-2">
                            <div class="form-check">
                                <input type="checkbox" name="is_new" value="1" class="form-check-input" id="is_new" @checked(old('is_new', $product->is_new))>
                                <label class="form-check-label" for="is_new">New</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $product->is_active))>
                                <label class="form-check-label" for="is_active">Published</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Replace cover image</label>
                            @if($product->image)
                                <div class="mb-2"><img src="{{ asset('storage/' . $product->image) }}" alt="" height="80" class="rounded border"></div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Add gallery images</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>

                <div class="card">
                    <div class="card-header">Gallery images</div>
                    <div class="card-body">
                        @if($product->images->isEmpty())
                            <p class="text-muted mb-0">No extra gallery images yet.</p>
                        @else
                            <div class="row g-3">
                                @foreach($product->images as $img)
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded p-2 position-relative">
                                            <img src="{{ asset('storage/' . $img->image) }}" class="img-fluid rounded" alt="">
                                            <a href="{{ route('catalogProducts.deleteImage', $img->id) }}" class="btn btn-sm btn-danger mt-2 w-100" onclick="return confirm('Remove?')">Remove</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
