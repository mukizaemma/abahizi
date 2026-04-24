@extends('layouts.adminbase')

@section('title', 'Add product')

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
                <div class="mb-4">
                    <a href="{{ route('catalogProducts.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
                </div>
                <h1 class="mb-4">Add product</h1>

                @if($categories->isEmpty())
                    <div class="alert alert-warning">Create at least one <a href="{{ route('productCategories.index') }}">category</a> first.</div>
                @endif

                <form action="{{ route('catalogProducts.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
                    @csrf
                    <div class="card-body row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select name="product_category_id" class="form-select" required>
                                <option value="">— Select —</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('product_category_id') == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="8" class="form-control">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price (RWF)</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" required value="{{ old('price', 0) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Compare at price (optional)</label>
                            <input type="number" name="compare_at_price" class="form-control" step="0.01" min="0" value="{{ old('compare_at_price') }}" placeholder="Original price">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Color / variant label (optional)</label>
                            <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="e.g. Black">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sort order</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', 0) }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-3 pb-2">
                            <div class="form-check">
                                <input type="checkbox" name="is_new" value="1" class="form-check-input" id="is_new" @checked(old('is_new'))>
                                <label class="form-check-label" for="is_new">New badge</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', true))>
                                <label class="form-check-label" for="is_active">Published</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cover image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gallery images (optional)</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-primary">Save product</button>
                    </div>
                </form>
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>
@endsection
